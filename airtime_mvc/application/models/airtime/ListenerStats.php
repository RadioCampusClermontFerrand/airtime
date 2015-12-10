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

    public static function getCountryGeoLocationStats($country, $start=null, $end=null)
    {
        $statsQuery = ListenerStatsQuery::create()
            ->select(array('ip', 'city', 'country_name', 'country_iso_code'))
            ->filterByDbCountryName(ucwords(strtolower($country)));

        if(!is_null($start) && !is_null($end)) {
            $statsQuery->filterByDbDisconnectTimestamp(array("min" => $start, "max" => $end));
        }

        $stats = $statsQuery->find();

        $result = array();
        foreach ($stats as $stat) {
            if (!is_null($stat["city"])) {
                if (!isset($result[$stat["city"]])) {
                    $result[$stat["city"]] = 1;
                } else {
                    $result[$stat["city"]] += 1;
                }

            } else {
                if (!isset($result["unknown"])) {
                    $result["unknown"] = 1;
                } else {
                    $result["unknown"] += 1;
                }
            }

        }
        return $result;
    }

    public static function getGlobalGeoLocationsStats($start=null, $end=null)
    {

        $statsQuery = ListenerStatsQuery::create()
            ->select(array('ip', 'city', 'country_name', 'country_iso_code'));

        if(!is_null($start) && !is_null($end)) {
            $statsQuery->filterByDbDisconnectTimestamp(array("min" => $start, "max" => $end));
        }

        $stats = $statsQuery->find();

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

    public static function getAggregateTuningHours($start=null, $end=null)
    {
        $stats = self::getAggregatePeriodDataPoints($start, $end);

        return $stats->toArray();
    }

    /**
     * Returns an array of data points to graph.
     * Data points will be once an hour.
     * @param $date
     */
    private static function getAggregateDailyDataPoints($date)
    {

    }

    /**
     * Returns an array of data points to graph.
     * Data points will be daily.
     * @param $start
     * @param $end
     */
    private static function getAggregatePeriodDataPoints($start, $end)
    {
        $statsQuery = ListenerStatsQuery::create()
            ->select(array('session_duration', 'date'))
            ->withColumn('sum(session_duration)', 'session_duration')
            ->withColumn('date(disconnect_timestamp)', 'date')
            ->addGroupByColumn('date(disconnect_timestamp)')
            ->addAscendingOrderByColumn('date');

        if(!is_null($start) && !is_null($end)) {
            $statsQuery->filterByDbDisconnectTimestamp(array("min" => $start, "max" => $end));
        }

        return $statsQuery->find();
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
