<?php

class RotationBuilder {

    /** @var int length of time, in seconds, to get history for when finding new tracks for rotation */
    protected static $_HISTORY_LOOKUP_SECONDS = 3600;

    /** @var int default Rotation duration */
    protected static $_DEFAULT_ROTATION_LENGTH = 3600;

    /**
     * @var PropelObjectCollection stores played track history from the past $_HISTORY_LOOKUP_SECONDS seconds.
     *                             Tracks in history are ignored when finding new tracks for rotation
     * @see Rotation::$_HISTORY_LOOKUP_SECONDS
     */
    protected $_history;

    /** @var CcFiles[] internal rotation tracks array */
    protected $_tracks = array();

    /** @var stdClass[] array of filters to narrow Rotation criteria */
    protected $_filters = array();

    /** @var int show instance to schedule the Rotation in */
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
     * @param int      $instance show instance to schedule the Rotation in
     * @param int|null [$length] optional override for the default Rotation length
     */
    public function __construct($instance, $length = null) {
        $this->_history = $this->_getHistory();
        $this->_showInstance = $instance;
        $this->_timeToFill = is_null($length) ? static::$_DEFAULT_ROTATION_LENGTH : $length;
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
        $this->_accountForScheduledTracks();
        $this->_build();
        $result = true;
        $after = $this->_lastScheduled ? $this->_lastScheduled : 0;
        $result = $result && $this->_addToSchedule($this->_showInstance, $after);
        return $result;
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
            ->filterByDbInstanceId($this->_showInstance)
            ->filterByDbStarts($future->format(DEFAULT_TIMESTAMP_FORMAT), Criteria::LESS_THAN)
            ->filterByDbEnds($now->format(DEFAULT_TIMESTAMP_FORMAT), Criteria::GREATER_THAN)
            ->orderByDbEnds()
            ->find();
        foreach ($tracks as $track) {
            $this->_lastScheduled = $track->getDbId();
            $this->_timeToFill -= Application_Common_DateHelper::playlistTimeToSeconds($track->getDbClipLength());
        }
        // Don't repeat any existing tracks
        $this->_history->setData(array_merge($this->_history->getData(), $tracks->getData()));
    }

    /**
     * Fill in any remaining time in the rotation.
     */
    protected function _build() {
        // Get a PropelObjectCollection of all suitable tracks so we only have to go to the database once
        $suitableTracks = $this->_getSuitableTracks($this->_timeToFill)->getData();
        while (!empty($suitableTracks) && $this->_timeToFill > 0) {
            $track = $this->_pickSuitableTrack($suitableTracks, $key);
            if (empty($track)) {
                if (empty($suitableTracks) && $this->_timeToFill > $this->_trackLengthMin) {
                    // TODO: make this more sophisticated - we can check the history and loosen our heuristics
                    $suitableTracks = $this->_tracks;
                    continue;
                } else {
                    break;
                }
            }
            $this->_timeToFill -= Application_Common_DateHelper::playlistTimeToSeconds($track->getDbLength());
            $this->_tracks[] = $track;
            array_splice($suitableTracks, $key, 1);
        }
    }

    /**
     *
     * @param int $timeToFill
     *
     * @return PropelObjectCollection
     */
    protected function _getSuitableTracks($timeToFill) {
        $tracks = CcFilesQuery::create()
            ->filterByDbLength($timeToFill, Criteria::LESS_EQUAL)
            ->filterByDbId($this->_getExcludeArray(), Criteria::NOT_IN)
            ->filterByDbLength('00:00:00', Criteria::GREATER_THAN);
        // TODO: can we avoid both storing a hypothetically infinite library in memory and
        //       running n queries by getting a bounded result set?
        //       (SUM of individual track lengths < rotation length)
        foreach ($this->_filters as $filter) {
            $tracks->filterBy($filter->column, $filter->value, $filter->comparison);
        }
        // TODO: build and apply rotation heuristics (based on user input?)
        return $tracks->find();
    }

    /**
     *
     * @param CcFiles[] $suitableTracks
     * @param int $key
     *
     * @return CcFiles
     */
    protected function _pickSuitableTrack(&$suitableTracks, &$key) {
        $track = null;
        while (!empty($suitableTracks)) {
            $key = array_rand($suitableTracks);
            $track = $suitableTracks[$key];
            $trackLength = Application_Common_DateHelper::playlistTimeToSeconds($track->getDbLength());
            if ($trackLength > $this->_timeToFill) {
                array_splice($suitableTracks, $key, 1);
                $track = null;
            } else {
                if (empty($this->_trackLengthMin) || $this->_trackLengthMin > $trackLength) {
                    $this->_trackLengthMin = $trackLength;
                }
                break;
            }
        }
        return $track;
    }

    /**
     * Get the current show instance ID.
     *
     * @return int the current show instance ID, or 0 if no current instance exists.
     *
     * @throws Exception
     */
    protected function _getCurrentShowInstance() {
        $future = $now = new DateTime(null, new DateTimeZone("UTC"));
        $timespan = static::$_HISTORY_LOOKUP_SECONDS;
        $future->add(new DateInterval("PT{$timespan}S"));
        // TODO: default to the next show instance if no current instance exists?
        $shows = Application_Model_Show::getPrevCurrentNext($now, $future->format(DEFAULT_TIMESTAMP_FORMAT), 1);
        return count($shows['currentShow']) > 0 ? $shows['currentShow']['instance_id'] : 0;
    }

    /**
     *
     * @return array
     */
    protected function _getExcludeArray() {
        $keys = array();
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
     * @param int $showInstance
     * @param int $scheduleAfter track to schedule the Rotation after,
     *                           defaults to the beginning of the show
     *
     * @return boolean
     */
    protected function _addToSchedule($showInstance, $scheduleAfter = 0) {
        if (!Zend_Session::isStarted()) Zend_Session::start();
        $scheduler = new Application_Model_Scheduler();
        // Ignore user permissions so the fallbacks can be set on non-user (pypo) requests
        $scheduler->setCheckUserPermissions(false);
        $scheduledItems = array(
            array(
                "id" => $scheduleAfter,
                "instance" => $showInstance,
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