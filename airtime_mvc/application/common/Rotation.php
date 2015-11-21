<?php

class Rotation {

    /**
     * @var Rotation
     */
    protected static $_instance;

    const HISTORY_LOOKUP_SECONDS = 3600;

    private function __construct() {
    }

    /**
     * @return Rotation
     */
    public static function getInstance() {
        if (!static::$_instance) {
            static::$_instance = new Rotation();
        }
        return static::$_instance;
    }

    public function getTracks($startTime) {
        $history = $this->_getHistory();
        if (is_null($startTime)) {
            $timeToFill = self::HISTORY_LOOKUP_SECONDS;
        } else {
            $timeToFill = (strtotime($startTime) - time()) / 1000;
            $timeToFill = Application_Common_DateHelper::secondsToPlaylistTime($timeToFill);
        }

        $tracks = array();
        while ($timeToFill > 0) {
            $track = CcFilesQuery::create()
                ->filterByDbLength($timeToFill, Criteria::LESS_EQUAL)
                ->filterByDbId($history->getPrimaryKeys(), Criteria::NOT_IN)
                ->filterByDbLength('00:00:00', Criteria::GREATER_THAN)
                ->addAscendingOrderByColumn("random()")
                ->findOne();
            if (!$track) break;
            $timeToFill -= Application_Common_DateHelper::playlistTimeToSeconds($track->getDbLength());
            $tracks[] = $track;
        }

        $this->_addTracksToSchedule($tracks);
        return $tracks;
    }

    private function _getHistory() {
        $oneHourAgo = gmdate(DEFAULT_TIMESTAMP_FORMAT, (microtime(true) - self::HISTORY_LOOKUP_SECONDS));
        $history = CcPlayoutHistoryQuery::create()
            ->filterByDbEnds($oneHourAgo, Criteria::GREATER_EQUAL)
            ->find();
        $files = array();
        foreach ($history as $item) {
            $files[] = $item->getDbFileId();
        }
        $history = CcFilesQuery::create()->findPks($files);
        return $history;
    }

    private function _addTracksToSchedule($tracks) {
        $future = $now = new DateTime(null, new DateTimeZone("UTC"));
        $timespan = self::HISTORY_LOOKUP_SECONDS;
        $future->add(new DateInterval("PT{$timespan}S"));
        $shows = Application_Model_Show::getPrevCurrentNext($now, $future->format(DEFAULT_TIMESTAMP_FORMAT), 1);
        $currentShowInstance = count($shows['currentShow'])>0?$shows['currentShow']['instance_id']:null;

        if ($currentShowInstance) {
            $scheduler = new Application_Model_Scheduler();
            $scheduledItems = array(
                array(
                    "id" => 0,
                    "instance" => $currentShowInstance,
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
        // TODO: if there isn't a current show instance, make one...??
    }

}