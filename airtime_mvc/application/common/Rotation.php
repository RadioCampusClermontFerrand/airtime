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
     * @var array internal rotation tracks array
     */
    private $_tracks = array();

    /**
     *
     * @return $this self, for chaining
     */
    public function addCriteria() {
        // TODO
        return $this;
    }

    /**
     * Create an array of CcFiles objects based on added criteria to fill up to an hour of unscheduled time.
     *
     * @param string $startTime string representation of the start time of the next scheduled track.
     *                          If set, only add as many tracks as will fit in the time between now and
     *                          $startTime to the internal tracks array
     * @return $this self, for chaining
     */
    public function build($startTime = null) {
        $this->_history = $this->_getHistory();
        $timeToFill = is_null($startTime) ? self::HISTORY_LOOKUP_SECONDS  // Fill up to HISTORY_LOOKUP_SECONDS seconds
                                          : min(self::HISTORY_LOOKUP_SECONDS, (strtotime($startTime) - time()) / 1000);
        while ($timeToFill > 0) {
            $track = $this->_getSuitableTrack($timeToFill);
            if (!$track) break;
            $timeToFill -= Application_Common_DateHelper::playlistTimeToSeconds($track->getDbLength());
            $this->_tracks[] = $track;
            // TODO: pop tracks off the history to keep it as close to HISTORY_LOOKUP_SECONDS as possible?
            $this->_history->prepend($track);  // Add each track to history so our heuristics use it for future tracks
        }

        return $this;
    }

    /**
     * Schedule the internal tracks array.
     *
     * TODO: should this function call build() if _tracks is empty?
     *
     * @see Rotation::build()
     */
    public function schedule() {
        $future = $now = new DateTime(null, new DateTimeZone("UTC"));
        $timespan = self::HISTORY_LOOKUP_SECONDS;
        $future->add(new DateInterval("PT{$timespan}S"));
        $shows = Application_Model_Show::getPrevCurrentNext($now, $future->format(DEFAULT_TIMESTAMP_FORMAT), 1);
        $currentShowInstance = count($shows['currentShow']) > 0 ? $shows['currentShow']['instance_id'] : null;

        if ($currentShowInstance) {
            $this->_scheduleFallbackRotation($currentShowInstance, $this->_tracks);
        }
        // TODO: if there isn't a current show instance, make one...??
    }

    /**
     * @param int $timeToFill
     * @return CcFiles
     */
    private function _getSuitableTrack($timeToFill) {
        $track = CcFilesQuery::create()
            ->filterByDbLength($timeToFill, Criteria::LESS_EQUAL)
            ->filterByDbId($this->_history->getPrimaryKeys(), Criteria::NOT_IN)
            ->filterByDbLength('00:00:00', Criteria::GREATER_THAN)
            // TODO: build and apply rotation heuristics (based on user input?)
            ->addAscendingOrderByColumn("random()")
            ->findOne();
        return $track;
    }

    /**
     * @return PropelObjectCollection
     */
    private function _getHistory() {
        $pastTimestamp = gmdate(DEFAULT_TIMESTAMP_FORMAT, (microtime(true) - self::HISTORY_LOOKUP_SECONDS));
        $history = CcPlayoutHistoryQuery::create()
            ->filterByDbEnds($pastTimestamp, Criteria::GREATER_EQUAL)
            ->find();
        $files = array();
        foreach ($history as $item) {
            $files[] = $item->getDbFileId();
        }
        $history = CcFilesQuery::create()->findPks($files);
        return $history;
    }

    /**
     * @param int $showInstance
     * @param CcFiles[] $tracks
     */
    private function _scheduleFallbackRotation($showInstance, $tracks) {
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
        foreach ($tracks as $track) {
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