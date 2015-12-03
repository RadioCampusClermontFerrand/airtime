<?php

use GeoIp2\Database\Reader;

class ListenerStatNotFoundException extends Exception {}

/**
 * Skeleton subclass for representing a row from the 'listener_stats' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.airtime
 */
class ListenerStats extends BaseListenerStats
{
    public static function getGeoLocationsStats($start=null, $end=null)
    {
        if(is_null($start) && is_null($end)) {
            $ips = ListenerStatsQuery::create()
                ->select(array('geo_ip'))
                ->find();
        } else {
            $ips = ListenerStatsQuery::create()
                ->select(array('geo_ip'))
                ->filterByDbDisconnectTimestamp($start, Criteria::GREATER_EQUAL)
                ->filterByDbDisconnectTimestamp($end, Criteria::LESS_THAN)
                ->find();
        }

        $result = array();

        //TODO: fix this path
        $reader = new Reader('/home/denise/Airtime/GeoLite2-City.mmdb');

        foreach($ips as $ip) {
            try {
                $record = $reader->city($ip);
            } catch (GeoIp2\Exception\AddressNotFoundException $e) {
                continue;
            } catch (MaxMind\Db\InvalidDatabaseException $e) {
                continue;
            }

            if (!isset($result[$record->country->isoCode])) {
                $result[$record->country->isoCode] = array();
            }
            array_push($result[$record->country->isoCode], $ip);
        }
        return $result;
    }

    public static function getListenerStatById($id)
    {
        $stat = ListenerStatsQuery::create()->findPk($id);
        if (!$stat) {
            throw new ListenerStatNotFoundException("Listener stat not found with id: ".$id);
        }

        $stat = $stat->toArray(BasePeer::TYPE_FIELDNAME);
        return $stat;
    }
}
