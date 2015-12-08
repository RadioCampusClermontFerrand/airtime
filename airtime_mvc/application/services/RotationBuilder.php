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
    public function __construct(CcShowInstances $instance, $length = null) {
        $this->_history = $this->_getHistory();
        $this->_showInstance = $instance;
        $this->_timeToFill = is_null($length) ? static::$_DEFAULT_ROTATION_LENGTH : $length;
        $this->_rotation = RotationQuery::create()->findPk($instance->getDbRotation());
        $this->_filters = json_decode($this->_rotation->getDbCriteria());
    }

    /**
     * Add a criteria filter to be used to narrow track selection
     *
     * @param string $column        the database column the filter is applied to
     * @param mixed  $value         the filter value
     * @param string [$comparison]  a Criteria comparison type
     *
     * @see Criteria
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
     * Schedule the internal tracks array to each of the internal instances,
     * defaulting to the current instance, if it exists.
     *
     * @return boolean true if scheduling was successful, otherwise false
     */
    public function schedule() {
        $this->_accountForRemainingShowTime();
        $this->_accountForScheduledTracks();

        $this->_build();
        $after = $this->_lastScheduled ? $this->_lastScheduled : 0;
        return $this->_addToSchedule($after);
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
     * If there are currently scheduled tracks in the given instance, remove their
     * cumulative duration from the total Playlist time and adjust start time
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

        // We only want to take the history and blacklist into account on the first pass
        // TODO: should this be incremental instead?
        $firstPass = true;
        try {
            // Make multiple passes, where each pass is one instance of the Rotation, until the timebox is filled
            while ($this->_timeToFill > 0) {
                // Reset the database seed each pass so we get consistent ordering
                $this->_seedResultSet($seed);
                $suitableTracks = $this->_getSuitableTracks($firstPass);
                if (empty($suitableTracks) && !$firstPass) { break; }
                foreach ($suitableTracks as $track) {
                    $this->_timeToFill -= Application_Common_DateHelper::calculateLengthInSeconds($track->getDbLength());
                }
                $this->_tracks = array_merge($this->_tracks, $suitableTracks);
                $firstPass = false;
            }
            // If we still have a gap at the end of the show, find one more track long enough to fill it
            if ($this->_timeToFill > 0) {
                $query = $this->_buildQuery(false, gmdate('H:i:s', $this->_timeToFill));
                $track = $query->findOne();
                if (!empty($track)) {
                    $this->_tracks[] = $track;
                }
            }
            $this->_con->commit();
        } catch (Exception $e) {
            Logging::error($e->getMessage());
            $this->_con->rollBack();
            throw $e;
        }
    }

    /**
     * Seed the database's random number generator so we get the same set of tracks
     * for each iteration of the Rotation.
     *
     * @param $seed
     */
    protected function _seedResultSet($seed) {
        $sql = "SELECT setseed(:seed)";
        $st = $this->_con->prepare($sql);
        $st->execute(array(":seed" => $seed));
    }

    /**
     * Get an array of all CcFiles rows that fit the Rotation criteria
     *
     * @param boolean [$excludeBlacklist]
     *
     * @return CcFiles[]
     */
    protected function _getSuitableTracks($excludeBlacklist = true) {
        $subQuery = $this->_buildQuery($excludeBlacklist);

        // Build the base query. We want to search exclusively on the subquery,
        // so we do the select from the BasePeer.
        $c = (new Criteria())
            ->addSelectColumn("a.*")
            ->addSelectQuery($subQuery, 'a')
            // This is what ModelCriteria::where() is doing under the hood, but without the enforced binding
            ->addUsingOperator("total", gmdate('H:i:s', $this->_timeToFill), Criteria::LESS_EQUAL);
        $st = BasePeer::doSelect($c, $this->_con);
        $rows = $st->fetchAll(PDO::FETCH_NUM);

        // There's probably a better way of doing this. PropelObjectCollection::fromArray() could work if we
        // use FETCH_ASSOC and change the keys to their BasePeer::TYPE_PHPNAME counterparts, but that would
        // probably be just as slow... -- Duncan
        $data = array();
        foreach ($rows as $row) {
            $obj = new CcFiles();
            $obj->hydrate($row);
            $data[] = $obj;
        }

        return $data;
    }

    /**
     * Build a CcFilesQuery object with the internal Rotation filters
     *
     * @param bool   [$excludeBlacklist]    if false, don't remove tracks from contention based on
     *                                      the history or the blacklist. Defaults to true
     * @param string [$greaterThanInterval] only find tracks greater than this interval. Defaults to zero
     *
     * @return CcFilesQuery
     */
    protected function _buildQuery($excludeBlacklist = true, $greaterThanInterval = '00:00:00') {
        $query = CcFilesQuery::create()
            ->withColumn("SUM(length) OVER (ORDER BY random())", "total")
            ->filterByDbLength(gmdate("H:i:s", $this->_timeToFill), Criteria::LESS_EQUAL)
            ->_if($excludeBlacklist)
                ->filterByDbId($this->_getExcludeArray(), Criteria::NOT_IN)
            ->_endif()
            ->filterByDbLength($greaterThanInterval, Criteria::GREATER_THAN);
        foreach ($this->_filters as $filter) {
            $query->filterBy($filter->column, $filter->value, $filter->comparison);
        }

        return $query;
    }

    /**
     * Combine tracks in recent history, tracks already added to the Rotation, and any
     * blacklisted tracks to create an exclusion array when finding suitable tracks.
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
     * Find any tracks that ended in the past $_HISTORY_LOOKUP_SECONDS seconds and add
     * them to the internal history array so we can remove them from contention
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
     * Add all tracks in the internal tracks array to the schedule
     *
     * @param int [$scheduleAfter] track to schedule the Rotation after,
     *                             defaults to the beginning of the show
     *
     * @return boolean true if the operation succeeded, otherwise false
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