<?php



/**
 * Skeleton subclass for representing a row from the 'rotation' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.airtime
 */
class Rotation extends BaseRotation
{

    /**
     * TODO
     *
     * @param $v
     * @return $this
     */
    public function setDbCriteria($v) {
        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = RotationPeer::NAME;
        }

        return $this;
    }

}
