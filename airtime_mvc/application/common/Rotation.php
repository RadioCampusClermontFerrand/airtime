<?php

class Rotation {

    /**
     * @var int length of time, in seconds, to get history for when finding new tracks for rotation
     */
    const HISTORY_LOOKUP_SECONDS = 3600;

    /**
     * @var PropelObjectCollection stores played track history from the past HISTORY_LOOKUP_SECONDS seconds.
     *                             Tracks in history are ignored when finding new tracks for rotation
     * @see Rotation::HISTORY_LOOKUP_SECONDS
     */
    private $_history;

    /**
     * @var CcFiles[] internal rotation tracks array
     */
    private $_tracks = array();

    private $_filters = array();

    private $_timeToFill;

    /**
     * Rotation constructor.
     *
     * @param string|null $startTime string representation of the start time of the next scheduled track.
     *                               If set, only add as many tracks as will fit in the time between now and
     *                               $startTime to the internal tracks array
     */
    public function __construct($startTime = null) {
        $this->_history = $this->_getHistory();
        $this->_timeToFill = is_null($startTime) ? self::HISTORY_LOOKUP_SECONDS
            : min(self::HISTORY_LOOKUP_SECONDS, (strtotime($startTime) - time()) / 1000);
    }

    /**
     * Fills in any remaining time in the rotation
     *
     * @return $this self, for chaining
     */
    public function build() {
        // Get a PropelObjectCollection of all suitable tracks so we only have to go to the database once
        /** @var CcFiles[] $suitableTracks */
        $suitableTracks = $this->_getSuitableTracks($this->_timeToFill)->getData();
        while ($this->_timeToFill > 0) {
            while (!empty($suitableTracks)) {
                $key = array_rand($suitableTracks);
                $track = $suitableTracks[$key];
                if (Application_Common_DateHelper::playlistTimeToSeconds($track->getDbLength()) > $this->_timeToFill) {
                    array_splice($suitableTracks, $key, 1);
                } else {
                    break;
                }
            }
            if (!isset($track) || !$track) break;
            $this->_timeToFill -= Application_Common_DateHelper::playlistTimeToSeconds($track->getDbLength());
            $this->_tracks[] = $track;
            array_splice($suitableTracks, $key, 1);
        }

        return $this;
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
        $filter->column = $column;
        $filter->value = $value;
        $filter->comparison = $comparison;
        $this->_filters[] = $filter;
        return $this;
    }

    /**
     * Include the given track in the rotation.
     *
     * @param CcFiles $track the track to include
     *
     * @return $this self, for chaining
     */
    public function includeTrack(CcFiles $track) {
        $length = Application_Common_DateHelper::playlistTimeToSeconds($track->getDbLength());
        if ($length < $this->_timeToFill) {
            Logging::warn("Couldn't include track $track; track length is greater than remaining rotation length");
            $this->_timeToFill -= $length;
            $this->_tracks[] = $track;
        }
        return $this;
    }

    /**
     * Schedule the internal tracks array.
     */
    public function schedule() {
        $future = $now = new DateTime(null, new DateTimeZone("UTC"));
        $timespan = self::HISTORY_LOOKUP_SECONDS;
        $future->add(new DateInterval("PT{$timespan}S"));
        $shows = Application_Model_Show::getPrevCurrentNext($now, $future->format(DEFAULT_TIMESTAMP_FORMAT), 1);
        $currentShowInstance = count($shows['currentShow']) > 0 ? $shows['currentShow']['instance_id'] : null;

        if ($currentShowInstance) {
            $this->_scheduleFallbackRotation($currentShowInstance);
        }
        // TODO: if there isn't a current show instance, make one...??
    }

    /**
     *
     * @param int $timeToFill
     *
     * @return PropelObjectCollection
     */
    private function _getSuitableTracks($timeToFill) {
        $tracks = CcFilesQuery::create()
            ->filterByDbLength($timeToFill, Criteria::LESS_EQUAL)
            ->filterByDbId($this->_getExcludeArray(), Criteria::NOT_IN)
            ->filterByDbLength('00:00:00', Criteria::GREATER_THAN);
        foreach ($this->_filters as $filter) {
            $tracks->filterBy($filter->column, $filter->value, $filter->comparsion);
        }
        // TODO: build and apply rotation heuristics (based on user input?)
        return $tracks->find();
    }

    /**
     *
     * @return array
     */
    private function _getExcludeArray() {
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
    private function _getHistory() {
        $pastTimestamp = gmdate(DEFAULT_TIMESTAMP_FORMAT, (microtime(true) - self::HISTORY_LOOKUP_SECONDS));
        $history = CcPlayoutHistoryQuery::create()
            ->filterByDbEnds($pastTimestamp, Criteria::GREATER_EQUAL)
            ->find();
        $history = CcFilesQuery::create()->findPks($history->toKeyValue('PrimaryKey', 'DbFileId'));
        return $history;
    }

    /**
     *
     * @param int $showInstance
     */
    private function _scheduleFallbackRotation($showInstance) {
        if (!Zend_Session::isStarted()) Zend_Session::start();
        $scheduler = new Application_Model_Scheduler();
        // Ignore user permissions so the fallbacks can be set on non-user (pypo) requests
        $scheduler->setCheckUserPermissions(false);
        $scheduledItems = array(
            array(
                "id" => 0,
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
        } catch (Exception $e) {
            Logging::error($e);
        }
    }

}