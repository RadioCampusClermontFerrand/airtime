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
    public static function create($data)
    {
        $listenerStat = new ListenerStats();
        $listenerStat->setDbBytes($data->bytes)
            ->setDbDisconnectTimestamp($data->timestamp)
            ->setDbIp($data->client_ip)
            ->setDbSessionDuration($data->session_duration)
            ->setDbMount($data->mount)
            ->setDbUserAgent($data->user_agent)
            ->setDbReferrer($data->referrer)
            ->save();


        //TODO: fix this path
        $reader = new Reader('/home/denise/Airtime/GeoLite2-City.mmdb');

        try {
            $record = $reader->city($data->client_ip);
            $listenerStat->setDbCountryIsoCode($record->country->isoCode)
                ->setDbCountryName($record->country->name)
                ->setDbCity($record->city->name)
                ->save();
        } catch (GeoIp2\Exception\AddressNotFoundException $e) {

        } catch (MaxMind\Db\InvalidDatabaseException $e) {

        }

    }

    public static function getGeoLocationsStats($start=null, $end=null)
    {
        if(is_null($start) && is_null($end)) {
            $stats = ListenerStatsQuery::create()
                ->select(array('ip', 'city', 'country_name', 'country_iso_code'))
                ->find();
        } else {
            $stats = ListenerStatsQuery::create()
                ->select(array('ip', 'city', 'country_name', 'country_iso_code'))
                ->filterByDbDisconnectTimestamp($start, Criteria::GREATER_EQUAL)
                ->filterByDbDisconnectTimestamp($end, Criteria::LESS_THAN)
                ->find();
        }

        $result = array();

        foreach($stats as $stat) {
            if (!empty($stat["country_iso_code"])) {
                if (!isset($result[$stat["country_iso_code"]])) {
                    $result[$stat["country_iso_code"]] = array();
                    $result[$stat["country_iso_code"]]["cities"] = array();
                    $result[$stat["country_iso_code"]]["total"] = 1;
                    $result[$stat["country_iso_code"]]["name"] = $stat["country_name"];
                } else {
                    $result[$stat["country_iso_code"]]["total"] += 1;
                }

                if (is_null($stat["city"])) {
                    $stat["city"] = "unknown";
                }



                // listener count by city
                if (!isset($result[$stat["country_iso_code"]]["cities"][$stat["city"]])) {
                    $result[$stat["country_iso_code"]]["cities"][$stat["city"]] = 1;
                } else {
                    $result[$stat["country_iso_code"]]["cities"][$stat["city"]] += 1;
                }
            }
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
