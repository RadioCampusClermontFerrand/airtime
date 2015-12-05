<?php

class RotationBuilder {

    /** @var int length of time, in seconds, to get history for when finding new tracks for rotation */
    protected static $_HISTORY_LOOKUP_SECONDS = 3600;

    /** @var int default Rotation duration */
    protected static $_DEFAULT_ROTATION_LENGTH = 3600;

    /** @var Rotation $rotation */
    protected $_rotation;

    /** @var PDO $_con database connection */
    protected $_con;

    /**
     * @var PropelObjectCollection stores played track history from the past $_HISTORY_LOOKUP_SECONDS seconds.
     *                             Tracks in history are ignored when finding new tracks for rotation
     * @see RotationBuilder::$_HISTORY_LOOKUP_SECONDS
     */
    protected $_history;

    /** @var CcFiles[] internal rotation tracks array */
    protected $_tracks = array();

    /** @var int[] array of CcFiles ids to exclude */
    protected $_blacklist = array();

    /** @var stdClass[] array of filters to narrow Rotation criteria */
    protected $_filters = array();

    /** @var CcShowInstances show instance to schedule the Rotation in */
    protected $_showInstance;

    /** @var int last track scheduled in the instance so we know where to schedule after */
    protected $_lastScheduled;

    /** @var int time, in seconds, left to fill in the Rotation */
    protected $_timeToFill;

    /**
     * @var int shortest track length in the Rotation.
     *          Used to determine whether to reschedule tracks to fill the Rotation
     */
    protected $_trackLengthMin;

    /**
     * Rotation constructor.
     *
     * @param CcShowInstances $instance show instance to schedule the Rotation in
     * @param int|null        [$length] optional override for the default Rotation length
     */
    public function __construct($instance, $length = null) {
        $this->_history = $this->_getHistory();
        $this->_showInstance = $instance;
        $this->_timeToFill = is_null($length) ? static::$_DEFAULT_ROTATION_LENGTH : $length;
        $this->_rotation = RotationQuery::create()->findPk($instance->getDbRotation());
    }

    /**
     *
     * @param string $column
     * @param mixed $value
     * @param string $comparison
     *
     * @return $this self, for chaining
     */
    public function addFilter($column, $value, $comparison = Criteria::EQUAL) {
        $filter = new stdClass();
        $filter->column     = $column;
        $filter->value      = $value;
        $filter->comparison = $comparison;
        $this->_filters[]   = $filter;
        return $this;
    }

    /**
     * Encode the internal Rotation filters so we can store them in the database
     *
     * @return string the json-encoded array of filter objects
     */
    public function encodeCriteriaFilters() {
        return json_encode($this->_filters);
    }

    /**
     * Hydrate the internal Rotation filters with a decoded json string
     *
     * @param string $str the json-encoded array of filter objects
     */
    public function decodeCriteriaString($str) {
        $this->_filters = json_decode($str);
    }

    /**
     * Include the given track in the rotation.
     *
     * @param CcFiles $track the track to include
     *
     * @return $this self, for chaining
     *
     * @throws Exception
     */
    public function includeTrack(CcFiles $track) {
        $length = Application_Common_DateHelper::playlistTimeToSeconds($track->getDbLength());
        if ($length < $this->_timeToFill) {
            $this->_timeToFill -= $length;
            $this->_tracks[] = $track;
        } else {
            throw new Exception("Couldn't include track; track length is greater than remaining rotation length");
        }
        return $this;
    }

    /**
     * Schedule the internal tracks array to each of the internal instances,
     * defaulting to the current instance, if it exists.
     *
     * @return boolean true if scheduling was successful, otherwise false
     */
    public function schedule() {
        $this->_accountForRemainingShowTime();
        $this->_accountForScheduledTracks();

        $this->_build();
        $result = true;
        $after = $this->_lastScheduled ? $this->_lastScheduled : 0;
        $result = $result && $this->_addToSchedule($after);
        return $result;
    }

    /**
     * If the time left to schedule would go over the remaining time in the show instance,
     * subtract the difference from the Rotation time to fill.
     */
    protected function _accountForRemainingShowTime() {
        $instanceEnd = DateTime::createFromFormat(
            DEFAULT_TIMESTAMP_FORMAT, $this->_showInstance->getDbEnds(), new DateTimeZone("UTC")
        );
        $instanceEndUnix = strtotime($instanceEnd->format(DEFAULT_TIMEZONE_FORMAT));
        $secondsRemaining = ($instanceEndUnix - time());
        if ($secondsRemaining < $this->_timeToFill) {
            $this->_timeToFill -= ($this->_timeToFill - $secondsRemaining);
        }
    }

    /**
     * If there are currently scheduled tracks in the given instance, removes their
     * cumulative duration from the total Playlist time and adjusts start time
     * accordingly.
     */
    protected function _accountForScheduledTracks() {
        $future = new DateTime(null, new DateTimeZone("UTC"));
        $now = new DateTime(null, new DateTimeZone("UTC"));
        $future->add(new DateInterval("PT{$this->_timeToFill}S"));
        $tracks = CcScheduleQuery::create()
            ->filterByDbInstanceId($this->_showInstance->getDbId())
            ->filterByDbStarts($future->format(DEFAULT_TIMESTAMP_FORMAT), Criteria::LESS_THAN)
            ->filterByDbEnds($now->format(DEFAULT_TIMESTAMP_FORMAT), Criteria::GREATER_THAN)
            ->orderByDbEnds()
            ->find();
        foreach ($tracks as $track) {
            $this->_blacklist[] = $track->getDbFileId();
            $this->_lastScheduled = $track->getDbId();
            $this->_timeToFill -= Application_Common_DateHelper::playlistTimeToSeconds($track->getDbClipLength());
        }
    }

    /**
     * Fill in any remaining time in the rotation.
     */
    protected function _build() {
        $seed = $this->_rotation->getDbSeed();
        if (!$seed) {
            $seed = mt_rand() / mt_getrandmax();
        }

        $this->_con = Propel::getConnection(CcPrefPeer::DATABASE_NAME);
        $this->_con->beginTransaction();

        try {
            $this->_seedResultSet($seed);
            $this->_tracks = $this->_getSuitableTracks()->getData();
            $this->_con->commit();
        } catch (Exception $e) {
            $this->_con->rollBack();
            throw $e;
        }
    }

    protected function _seedResultSet($seed) {
        $sql = "SELECT setseed(:seed)";
        $st = $this->_con->prepare($sql);
        $st->execute(array(":seed" => $seed));
    }

    /**
     *
     * @param boolean $excludeBlacklist
     *
     * @return PropelObjectCollection
     */
    protected function _getSuitableTracks($excludeBlacklist = true) {

        // TODO: figure out how to alias virtual columns in subqueries!

        $subquery = CcFilesQuery::create()
            ->withColumn("SUM(CcFiles.length) OVER (ORDER BY random())", "total")
            ->filterByDbLength(gmdate("H:i:s", $this->_timeToFill), Criteria::LESS_EQUAL)
            ->_if($excludeBlacklist)
                ->filterByDbId($this->_getExcludeArray(), Criteria::NOT_IN)
            ->_endif()
            ->filterByDbLength('00:00:00', Criteria::GREATER_THAN);
        foreach ($this->_filters as $filter) {
            $subquery->filterBy($filter->column, $filter->value, $filter->comparison);
        }

        $tracks = CcFilesQuery::create()
            ->withColumn("t.total", "total")
            ->addSelectQuery($subquery, "t")
            ->where("total > ?", $this->_timeToFill);

        // TODO: build and apply rotation heuristics (based on user input?)
        return $tracks->find();
    }

    /**
     *
     * @return array
     */
    protected function _getExcludeArray() {
        $keys = $this->_blacklist;
        foreach ($this->_tracks as $t) {
            $keys[] = $t->getDbId();
        }
        return array_merge($this->_history->getPrimaryKeys(), $keys);
    }

    /**
     *
     * @return PropelObjectCollection
     */
    protected function _getHistory() {
        $pastTimestamp = gmdate(DEFAULT_TIMESTAMP_FORMAT, (microtime(true) - static::$_HISTORY_LOOKUP_SECONDS));
        $history = CcPlayoutHistoryQuery::create()
            ->filterByDbEnds($pastTimestamp, Criteria::GREATER_EQUAL)
            ->find();
        $history = CcFilesQuery::create()->findPks($history->toKeyValue('PrimaryKey', 'DbFileId'));
        return $history;
    }

    /**
     *
     * @param int $scheduleAfter track to schedule the Rotation after,
     *                           defaults to the beginning of the show
     *
     * @return boolean
     */
    protected function _addToSchedule($scheduleAfter = 0) {
        if (!Zend_Session::isStarted()) Zend_Session::start();
        $scheduler = new Application_Model_Scheduler();
        // Ignore user permissions so the fallbacks can be set on non-user (pypo) requests
        $scheduler->setCheckUserPermissions(false);
        $scheduledItems = array(
            array(
                "id" => $scheduleAfter,
                "instance" => $this->_showInstance->getDbId(),
                "timestamp" => time()
            )
        );
        $mediaItems = array();
        foreach ($this->_tracks as $track) {
            $mediaItems[] = array(
                "id" => $track->getDbId(),
                "type" => "audioclip"
            );
        }

        try {
            $scheduler->scheduleAfter($scheduledItems, $mediaItems);
            return true;
        } catch (Exception $e) {
            Logging::error($e);
            return false;
        }
    }

}