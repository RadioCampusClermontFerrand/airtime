<?php

class CriteriaBuilder {

    public static $modifier2CriteriaMap = array(
        "contains"         => Criteria::ILIKE,
        "does not contain" => Criteria::NOT_ILIKE,
        "is"               => Criteria::EQUAL,
        "is not"           => Criteria::NOT_EQUAL,
        "starts with"      => Criteria::ILIKE,
        "ends with"        => Criteria::ILIKE,
        "is greater than"  => Criteria::GREATER_THAN,
        "is less than"     => Criteria::LESS_THAN,
        "is in the range"  => Criteria::CUSTOM);

    /**
     *
     *
     * @param BaseObject $obj
     * @param string $type
     *
     * @return string[]
     */
    public static function getObjectFields(BaseObject $obj, $type = BasePeer::TYPE_FIELDNAME) {
        return BasePeer::getFieldnames(get_class($obj), $type);
    }

}