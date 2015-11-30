<?php

/**
 * Class Rotation
 *
 * Builds track rotations to be scheduled or deferred
 */
class Rotation {

    /** @var int length of time, in seconds, to get history for when finding new tracks for rotation */
    private static $_HISTORY_LOOKUP_SECONDS = 3600;

    /** @var int default Rotation duration */
    private static $_DEFAULT_ROTATION_LENGTH = 3600;

    /**
     * @var PropelObjectCollection stores played track history from the past HISTORY_LOOKUP_SECONDS seconds.
     *                             Tracks in history are ignored when finding new tracks for rotation
     * @see Rotation::HISTORY_LOOKUP_SECONDS
     */
    private $_history;

    /**
     * @var Bucket[]
     */
    private $_buckets = array();

    /** @var CcFiles[] internal rotation tracks array */
    private $_tracks = array();

    /** @var stdClass[] array of filters to narrow Rotation criteria */
    private $_filters = array();

    /** @var int time, in seconds, left to fill in the Rotation */
    private $_timeToFill;

    /** @var int[] show instances to schedule the Rotation in */
    private $_showInstances;

    /**
     * Wakeup call function, called when Rotation object is successfully rebuilt with unserialize.
     *
     * @see unserialize
     */
    public function __wakeup() {
        $this->schedule();
    }

    /**
     * Rotation constructor.
     *
     * @param int|null [$length] optional override for the default Rotation length
     */
    public function __construct($length = null) {
        $this->_timeToFill = is_null($length) ? self::$_DEFAULT_ROTATION_LENGTH : $length;
    }

    /**
     * Add one or more instances to the internal instance list. Calls to schedule() and defer()
     * will apply to all instances in this list.
     *
     * @param int|int[] $showInstances one or more show instances to add to the internal instances list
     *
     * @return $this self, for chaining
     *
     * @see Rotation::schedule()
     * @see Rotation::defer()
     */
    public function addInstances($showInstances) {
        array_merge($this->_showInstances, is_array($showInstances) ? $showInstances : [$showInstances]);
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
        $filter->column     = $column;
        $filter->value      = $value;
        $filter->comparison = $comparison;
        $this->_filters[]   = $filter;
        return $this;
    }

    /**
     *
     * @param stdClass[] $filters
     * @param int $size
     * @param int $length
     *
     * @return $this self, for chaining
     */
    public function addBucket($filters, $size = 0, $length = 0) {
        $this->_buckets = (new Bucket())
            ->addFilters($filters)
            ->length($length)
            ->size($size);
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
     * Add a promise to schedule this Rotation to each of the internal instances,
     * defaulting to the current instance, if it exists.
     *
     * @param int [$secondsInAdvance] number seconds before the show instance or instances start
     *                                to schedule the Rotation. Defaults to 300 (5 minutes)
     *
     * @return $this self, for chaining
     */
    public function defer($secondsInAdvance = 300) {
        $deferredInstances = array();
        $instances = CcShowInstancesQuery::create()->findPks($this->_showInstances);
        foreach ($instances as $instance) {
            /** @var DateTime $ts */
            $ts = $instance->getDbStarts(null)->sub(new DateInterval("PT{$secondsInAdvance}S"));
            $deferredInstances[$ts->format(DEFAULT_TIMESTAMP_FORMAT)] = $instance->getDbId();
        }
        $this->_showInstances = $deferredInstances;
        // TODO: build deferral task by serializing Rotation object
        return $this;
    }

    /**
     * Schedule the internal tracks array to each of the internal instances,
     * defaulting to the current instance, if it exists.
     *
     * @return $this self, for chaining
     */
    public function schedule() {
        $instances = $this->_showInstances;
        if (empty($instances)) {
            $currentInstance = $this->_getCurrentShowInstance();
            Logging::info($currentInstance);
            if ($currentInstance > 0) $instances[] = $currentInstance;
        }

        $this->_build();

        foreach ($instances as $ts => $instance) {
            $now = DateTime::createFromFormat(DEFAULT_TIMESTAMP_FORMAT, "now", new DateTimeZone("UTC"));
            // Don't schedule deferred instances until we're at or past their deferral time
            if (is_int($ts) || $now >= $ts) {
                $this->_scheduleToInstance($instance);
            }
            // TODO: add instances to blacklist so we don't schedule the same Rotation multiple times?
        }

        $this->_history = $this->_getHistory();
        foreach ($this->_buckets as $bucket) {
            $bucket->fill($this->_getExcludeArray());
        }

        return $this;
    }

    /**
     * Get the current show instance ID.
     *
     * @return int the current show instance ID, or 0 if no current instance exists.
     *
     * @throws Exception
     */
    private function _getCurrentShowInstance() {
        $future = $now = new DateTime(null, new DateTimeZone("UTC"));
        $timespan = self::$_HISTORY_LOOKUP_SECONDS;
        $future->add(new DateInterval("PT{$timespan}S"));
        // TODO: default to the next show instance if no current instance exists?
        $shows = Application_Model_Show::getPrevCurrentNext($now, $future->format(DEFAULT_TIMESTAMP_FORMAT), 1);
        return count($shows['currentShow']) > 0 ? $shows['currentShow']['instance_id'] : 0;
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
        $pastTimestamp = gmdate(DEFAULT_TIMESTAMP_FORMAT, (microtime(true) - self::$_HISTORY_LOOKUP_SECONDS));
        $history = CcPlayoutHistoryQuery::create()
            ->filterByDbEnds($pastTimestamp, Criteria::GREATER_EQUAL)
            ->find();
        $history = CcFilesQuery::create()->findPks($history->toKeyValue('PrimaryKey', 'DbFileId'));
        return $history;
    }

    /**
     *
     * @param int $showInstance
     *
     * @throws Exception
     */
    private function _scheduleToInstance($showInstance) {
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
            // This was dying silently on error, so log it and rethrow
            Logging::error($e);
            throw $e;
        }
    }

}

class Bucket {

    /** @var CcFiles[] internal rotation tracks array */
    protected $_tracks = array();

    protected $_filters;

    protected $_size = 0;

    protected $_length = 0;

    /**
     * @var int shortest track length in the Bucket.
     *          Used to determine whether to reschedule tracks to fill
     */
    private $_trackLengthMin = 0;

    public function size($size) {
        $this->_size = $size;
        return $this;
    }

    public function length($length) {
        $this->_length = $length;
        return $this;
    }

    public function addFilters($filters) {
        $this->_filters = $filters;
        return $this;
    }

    public function fill($exclude = null) {
        $suitableTracks = $this->_getSuitableTracks($exclude)->getData();

        while ($this->_hasSpace()) {
            $track = $this->_pickSuitableTrack($suitableTracks, $key);
            if (empty($track)) {
                if (empty($suitableTracks) && $this->_hasSpace()) {
                    // TODO: make this more sophisticated - we can check the history and loosen our heuristics
                    $suitableTracks = $this->_tracks;
                    continue;
                } else {
                    break;
                }
            }
            $this->_size -= $this->_size ? 1 : 0;
            $this->_length -= $this->_length ?
                Application_Common_DateHelper::playlistTimeToSeconds($track->getDbLength()) : 0;
            $this->_tracks[] = $track;
            array_splice($suitableTracks, $key, 1);
        }

        // TODO: return tracks to exclude them above..?
    }

    protected function _hasSpace() {
        return ($this->_size && $this->_size > 0) || ($this->_length && $this->_length > $this->_trackLengthMin);
    }

    /**
     *
     * @param int[] $exclude
     *
     * @return PropelObjectCollection
     */
    protected function _getSuitableTracks($exclude) {
        $tracks = CcFilesQuery::create()
            ->filterByDbId($exclude, Criteria::NOT_IN)
            ->filterByDbLength('00:00:00', Criteria::GREATER_THAN);
        foreach ($this->_filters as $filter) {
            $tracks->filterBy($filter->column, $filter->value, $filter->comparsion);
        }
        if ($this->_length && $this->_length > 0) {
            $tracks->filterByDbLength($this->_length, Criteria::LESS_EQUAL);
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
            if ($trackLength && $trackLength > $this->_length) {
                array_splice($suitableTracks, $key, 1);
                // Unset so the last track in $suitableTracks won't get added if it's too long
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

}