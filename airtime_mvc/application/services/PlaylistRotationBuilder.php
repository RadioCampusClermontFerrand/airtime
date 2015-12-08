<?php

class PlaylistRotationBuilder extends RotationBuilder {

    /**
     * PlaylistRotationBuilder constructor.
     *
     * @param CcShowInstances $instance show instance to schedule the Rotation in
     * @param int|null        [$length] optional override for the default Rotation length
     */
    public function __construct(CcShowInstances $instance, $length = null) {
        parent::__construct($instance, $length);
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
        $query = CcFilesQuery::create('a')
            ->joinCcPlaylistcontents('c', Criteria::INNER_JOIN)
            ->where("c.playlist_id = ?", $this->_rotation->getDbPlaylist())
            ->where("a.id = c.file_id")
            ->withColumn("SUM(length) OVER (ORDER BY c.position)", "total")
            ->filterByDbLength(gmdate("H:i:s", $this->_timeToFill), Criteria::LESS_EQUAL)
            ->_if($excludeBlacklist)
                ->filterByDbId($this->_getExcludeArray(), Criteria::NOT_IN)
            ->_endif()
            ->filterByDbLength($greaterThanInterval, Criteria::GREATER_THAN)
            ->orderBy('c.position');  // TODO: test me!

        return $query;
    }

}