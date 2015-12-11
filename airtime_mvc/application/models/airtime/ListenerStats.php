<?php

use GeoIp2\Database\Reader;

class ListenerStatNotFoundException extends Exception {}

/**
 * TODO:
 * - Fix windowing - We're currently aggregating only listeners that disconnect within the time range.
 * - Handle timezones correctly. We should convert the timezone that comes from the front-end into UTC.
 */

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
        $sessionDurationSeconds = $data->session_duration;
        $sessionStart = new DateTime($data->timestamp, 'utc');
        $sessionStart->modify(sprintf("-%s seconds", $sessionDurationSeconds));

        $listenerStat = new ListenerStats();
        $listenerStat->setDbBytes($data->bytes)
            ->setDbDisconnectTimestamp($data->timestamp)
            ->setDbConnectTimestamp($sessionStart)
            ->setDbSessionDuration($data->session_duration)
            ->setDbIp($data->client_ip)
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

        Logging::info($stats);
        return $stats; //->toArray();
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
        $sql = 'SELECT
                    EXTRACT(epoch from date)*1000, -- The Float library needs a JavaScript timestamp. This gives us that.
                    EXTRACT(epoch from SUM(session_duration))/3600 as "total_listener_hours"
                FROM (
                    SELECT date(disconnect_timestamp) as date, session_duration
                    FROM listener_stats
                      WHERE (connect_timestamp, disconnect_timestamp) OVERLAPS (:start, :end)
                      GROUP BY date(disconnect_timestamp), disconnect_timestamp, session_duration, date
                      ORDER BY disconnect_timestamp ASC
                    ) t GROUP BY date ORDER BY date';

        $sqlParams = array(":start" => $start, ":end" => $end);
        $conn = Propel::getConnection();
        $st = $conn->prepare($sql);
        foreach ($sqlParams as $key => $value) {
            $st->bindValue($key, $value);
        }
        $st->execute();

        return $st->fetchAll(PDO::FETCH_KEY_PAIR);


        /*
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
        */
    }

    public static function getMostPopularShows($start, $end) {

        //TODO: As an alternative report, show the average number of listeners on each day across each hour? Could be a week calendar with heatmap.
        //TODO: Bin the data on the show name, and average out the number of listeners across all instances?

        //Get the most popular hours within the time range. (ie. the hours with the most listeners)

        //Get most popular hours of the day
        $statsQuery = ListenerStatsQuery::create()
            ->select(array('listeners', 'hour'))
            ->withColumn('date_trunc(\'hour\', disconnect_timestamp)', 'hour') //Truncate precision to the hour
            ->withColumn('count(*)', 'listeners')
            ->addGroupByColumn('hour')
            ->addDescendingOrderByColumn('listeners');
            //->setLimit(5);

        if (!is_null($start) && !is_null($end)) {
            $statsQuery->filterByDbDisconnectTimestamp(array("min" => $start, "max" => $end));
        }

        $mostPopularHoursResults = $statsQuery->find();

        $hourIdx = 0;
        $popularHourCount = $mostPopularHoursResults->count();

        if ($popularHourCount == 0) {
            return array([], 0);
        }

        $sql = "SELECT * from cc_show_instances";
        $sql .= " LEFT JOIN cc_show ON cc_show_instances.show_id=cc_show.id";
        $sql .= " WHERE";

        $sqlParams = array();
        foreach ($mostPopularHoursResults as $hour) {
            $showStartHour = new DateTime($hour['hour'], new DateTimeZone('utc'));
            $nextHour = (clone $showStartHour);
            $nextHour->add(new DateInterval("PT1H"));

            $showStartHourTS = $showStartHour->format(DEFAULT_TIMESTAMP_FORMAT);
            $nextHourTS = $nextHour->format(DEFAULT_TIMESTAMP_FORMAT);

            //For each hour, figure out what show was scheduled at that time.
            $sql .= " (cc_show_instances.starts,cc_show_instances.ends) OVERLAPS (:showStartHourTS${hourIdx}, :nextHourTS${hourIdx})";
            $sqlParams[":showStartHourTS${hourIdx}"] = $showStartHourTS;
            $sqlParams[":nextHourTS${hourIdx}"] = $nextHourTS;

            $hourIdx++;

            if ($hourIdx < $popularHourCount) {
                $sql .= " OR";
            }
        }

        $conn = Propel::getConnection();
        $st = $conn->prepare($sql);
        foreach ($sqlParams as $key => $value) {
            $st->bindValue($key, $value);
        }
        $st->execute();

        $showInstances = $st->fetchAll(PDO::FETCH_ASSOC);
        for ($showIdx = 0; $showIdx < sizeof($showInstances); $showIdx++) {
            $showInstances[$showIdx]['listeners'] = $mostPopularHoursResults[$showIdx]['listeners'];
            $showInstances[$showIdx] = CcShowInstances::sanitizeResponse($showInstances[$showIdx]);
        }
        return array($showInstances, count($showInstances));

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
