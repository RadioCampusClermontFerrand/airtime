<?php

class RotationFactory {

    /**
     * Given a show instance, return the appropriate RotationBuilder object
     *
     * @param CcShowInstances $instance
     * @return RotationBuilder
     */
    public static function getRotation(CcShowInstances $instance) {
        $r = RotationQuery::create()->findPk($instance->getDbRotation());
        if ($r->getDbPlaylist() > 0) {
            $rotation = new PlaylistRotationBuilder($instance);
        } else {
            $rotation = new RotationBuilder($instance);
        }
        return $rotation;
    }

}