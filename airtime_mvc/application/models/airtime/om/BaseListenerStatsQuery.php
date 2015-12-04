<?php


/**
 * Base class that represents a query for the 'listener_stats' table.
 *
 *
 *
 * @method ListenerStatsQuery orderByDbId($order = Criteria::ASC) Order by the id column
 * @method ListenerStatsQuery orderByDbDisconnectTimestamp($order = Criteria::ASC) Order by the disconnect_timestamp column
 * @method ListenerStatsQuery orderByDbIp($order = Criteria::ASC) Order by the ip column
 * @method ListenerStatsQuery orderByDbCity($order = Criteria::ASC) Order by the city column
 * @method ListenerStatsQuery orderByDbCountry($order = Criteria::ASC) Order by the country column
 * @method ListenerStatsQuery orderByDbSessionDuration($order = Criteria::ASC) Order by the session_duration column
 * @method ListenerStatsQuery orderByDbMount($order = Criteria::ASC) Order by the mount column
 * @method ListenerStatsQuery orderByDbBytes($order = Criteria::ASC) Order by the bytes column
 * @method ListenerStatsQuery orderByDbReferrer($order = Criteria::ASC) Order by the referrer column
 * @method ListenerStatsQuery orderByDbUserAgent($order = Criteria::ASC) Order by the user_agent column
 *
 * @method ListenerStatsQuery groupByDbId() Group by the id column
 * @method ListenerStatsQuery groupByDbDisconnectTimestamp() Group by the disconnect_timestamp column
 * @method ListenerStatsQuery groupByDbIp() Group by the ip column
 * @method ListenerStatsQuery groupByDbCity() Group by the city column
 * @method ListenerStatsQuery groupByDbCountry() Group by the country column
 * @method ListenerStatsQuery groupByDbSessionDuration() Group by the session_duration column
 * @method ListenerStatsQuery groupByDbMount() Group by the mount column
 * @method ListenerStatsQuery groupByDbBytes() Group by the bytes column
 * @method ListenerStatsQuery groupByDbReferrer() Group by the referrer column
 * @method ListenerStatsQuery groupByDbUserAgent() Group by the user_agent column
 *
 * @method ListenerStatsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ListenerStatsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ListenerStatsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ListenerStats findOne(PropelPDO $con = null) Return the first ListenerStats matching the query
 * @method ListenerStats findOneOrCreate(PropelPDO $con = null) Return the first ListenerStats matching the query, or a new ListenerStats object populated from the query conditions when no match is found
 *
 * @method ListenerStats findOneByDbDisconnectTimestamp(string $disconnect_timestamp) Return the first ListenerStats filtered by the disconnect_timestamp column
 * @method ListenerStats findOneByDbIp(string $ip) Return the first ListenerStats filtered by the ip column
 * @method ListenerStats findOneByDbCity(string $city) Return the first ListenerStats filtered by the city column
 * @method ListenerStats findOneByDbCountry(string $country) Return the first ListenerStats filtered by the country column
 * @method ListenerStats findOneByDbSessionDuration(int $session_duration) Return the first ListenerStats filtered by the session_duration column
 * @method ListenerStats findOneByDbMount(string $mount) Return the first ListenerStats filtered by the mount column
 * @method ListenerStats findOneByDbBytes(int $bytes) Return the first ListenerStats filtered by the bytes column
 * @method ListenerStats findOneByDbReferrer(string $referrer) Return the first ListenerStats filtered by the referrer column
 * @method ListenerStats findOneByDbUserAgent(string $user_agent) Return the first ListenerStats filtered by the user_agent column
 *
 * @method array findByDbId(int $id) Return ListenerStats objects filtered by the id column
 * @method array findByDbDisconnectTimestamp(string $disconnect_timestamp) Return ListenerStats objects filtered by the disconnect_timestamp column
 * @method array findByDbIp(string $ip) Return ListenerStats objects filtered by the ip column
 * @method array findByDbCity(string $city) Return ListenerStats objects filtered by the city column
 * @method array findByDbCountry(string $country) Return ListenerStats objects filtered by the country column
 * @method array findByDbSessionDuration(int $session_duration) Return ListenerStats objects filtered by the session_duration column
 * @method array findByDbMount(string $mount) Return ListenerStats objects filtered by the mount column
 * @method array findByDbBytes(int $bytes) Return ListenerStats objects filtered by the bytes column
 * @method array findByDbReferrer(string $referrer) Return ListenerStats objects filtered by the referrer column
 * @method array findByDbUserAgent(string $user_agent) Return ListenerStats objects filtered by the user_agent column
 *
 * @package    propel.generator.airtime.om
 */
abstract class BaseListenerStatsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseListenerStatsQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'airtime';
        }
        if (null === $modelName) {
            $modelName = 'ListenerStats';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ListenerStatsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ListenerStatsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ListenerStatsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ListenerStatsQuery) {
            return $criteria;
        }
        $query = new ListenerStatsQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   ListenerStats|ListenerStats[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ListenerStatsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ListenerStatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 ListenerStats A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneByDbId($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 ListenerStats A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "disconnect_timestamp", "ip", "city", "country", "session_duration", "mount", "bytes", "referrer", "user_agent" FROM "listener_stats" WHERE "id" = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new ListenerStats();
            $obj->hydrate($row);
            ListenerStatsPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return ListenerStats|ListenerStats[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|ListenerStats[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ListenerStatsPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ListenerStatsPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterByDbId(1234); // WHERE id = 1234
     * $query->filterByDbId(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterByDbId(array('min' => 12)); // WHERE id >= 12
     * $query->filterByDbId(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $dbId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByDbId($dbId = null, $comparison = null)
    {
        if (is_array($dbId)) {
            $useMinMax = false;
            if (isset($dbId['min'])) {
                $this->addUsingAlias(ListenerStatsPeer::ID, $dbId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dbId['max'])) {
                $this->addUsingAlias(ListenerStatsPeer::ID, $dbId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListenerStatsPeer::ID, $dbId, $comparison);
    }

    /**
     * Filter the query on the disconnect_timestamp column
     *
     * Example usage:
     * <code>
     * $query->filterByDbDisconnectTimestamp('2011-03-14'); // WHERE disconnect_timestamp = '2011-03-14'
     * $query->filterByDbDisconnectTimestamp('now'); // WHERE disconnect_timestamp = '2011-03-14'
     * $query->filterByDbDisconnectTimestamp(array('max' => 'yesterday')); // WHERE disconnect_timestamp < '2011-03-13'
     * </code>
     *
     * @param     mixed $dbDisconnectTimestamp The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByDbDisconnectTimestamp($dbDisconnectTimestamp = null, $comparison = null)
    {
        if (is_array($dbDisconnectTimestamp)) {
            $useMinMax = false;
            if (isset($dbDisconnectTimestamp['min'])) {
                $this->addUsingAlias(ListenerStatsPeer::DISCONNECT_TIMESTAMP, $dbDisconnectTimestamp['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dbDisconnectTimestamp['max'])) {
                $this->addUsingAlias(ListenerStatsPeer::DISCONNECT_TIMESTAMP, $dbDisconnectTimestamp['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListenerStatsPeer::DISCONNECT_TIMESTAMP, $dbDisconnectTimestamp, $comparison);
    }

    /**
     * Filter the query on the ip column
     *
     * Example usage:
     * <code>
     * $query->filterByDbIp('fooValue');   // WHERE ip = 'fooValue'
     * $query->filterByDbIp('%fooValue%'); // WHERE ip LIKE '%fooValue%'
     * </code>
     *
     * @param     string $dbIp The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByDbIp($dbIp = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($dbIp)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $dbIp)) {
                $dbIp = str_replace('*', '%', $dbIp);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListenerStatsPeer::IP, $dbIp, $comparison);
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByDbCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByDbCity('%fooValue%'); // WHERE city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $dbCity The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByDbCity($dbCity = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($dbCity)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $dbCity)) {
                $dbCity = str_replace('*', '%', $dbCity);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListenerStatsPeer::CITY, $dbCity, $comparison);
    }

    /**
     * Filter the query on the country column
     *
     * Example usage:
     * <code>
     * $query->filterByDbCountry('fooValue');   // WHERE country = 'fooValue'
     * $query->filterByDbCountry('%fooValue%'); // WHERE country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $dbCountry The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByDbCountry($dbCountry = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($dbCountry)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $dbCountry)) {
                $dbCountry = str_replace('*', '%', $dbCountry);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListenerStatsPeer::COUNTRY, $dbCountry, $comparison);
    }

    /**
     * Filter the query on the session_duration column
     *
     * Example usage:
     * <code>
     * $query->filterByDbSessionDuration(1234); // WHERE session_duration = 1234
     * $query->filterByDbSessionDuration(array(12, 34)); // WHERE session_duration IN (12, 34)
     * $query->filterByDbSessionDuration(array('min' => 12)); // WHERE session_duration >= 12
     * $query->filterByDbSessionDuration(array('max' => 12)); // WHERE session_duration <= 12
     * </code>
     *
     * @param     mixed $dbSessionDuration The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByDbSessionDuration($dbSessionDuration = null, $comparison = null)
    {
        if (is_array($dbSessionDuration)) {
            $useMinMax = false;
            if (isset($dbSessionDuration['min'])) {
                $this->addUsingAlias(ListenerStatsPeer::SESSION_DURATION, $dbSessionDuration['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dbSessionDuration['max'])) {
                $this->addUsingAlias(ListenerStatsPeer::SESSION_DURATION, $dbSessionDuration['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListenerStatsPeer::SESSION_DURATION, $dbSessionDuration, $comparison);
    }

    /**
     * Filter the query on the mount column
     *
     * Example usage:
     * <code>
     * $query->filterByDbMount('fooValue');   // WHERE mount = 'fooValue'
     * $query->filterByDbMount('%fooValue%'); // WHERE mount LIKE '%fooValue%'
     * </code>
     *
     * @param     string $dbMount The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByDbMount($dbMount = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($dbMount)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $dbMount)) {
                $dbMount = str_replace('*', '%', $dbMount);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListenerStatsPeer::MOUNT, $dbMount, $comparison);
    }

    /**
     * Filter the query on the bytes column
     *
     * Example usage:
     * <code>
     * $query->filterByDbBytes(1234); // WHERE bytes = 1234
     * $query->filterByDbBytes(array(12, 34)); // WHERE bytes IN (12, 34)
     * $query->filterByDbBytes(array('min' => 12)); // WHERE bytes >= 12
     * $query->filterByDbBytes(array('max' => 12)); // WHERE bytes <= 12
     * </code>
     *
     * @param     mixed $dbBytes The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByDbBytes($dbBytes = null, $comparison = null)
    {
        if (is_array($dbBytes)) {
            $useMinMax = false;
            if (isset($dbBytes['min'])) {
                $this->addUsingAlias(ListenerStatsPeer::BYTES, $dbBytes['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dbBytes['max'])) {
                $this->addUsingAlias(ListenerStatsPeer::BYTES, $dbBytes['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListenerStatsPeer::BYTES, $dbBytes, $comparison);
    }

    /**
     * Filter the query on the referrer column
     *
     * Example usage:
     * <code>
     * $query->filterByDbReferrer('fooValue');   // WHERE referrer = 'fooValue'
     * $query->filterByDbReferrer('%fooValue%'); // WHERE referrer LIKE '%fooValue%'
     * </code>
     *
     * @param     string $dbReferrer The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByDbReferrer($dbReferrer = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($dbReferrer)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $dbReferrer)) {
                $dbReferrer = str_replace('*', '%', $dbReferrer);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListenerStatsPeer::REFERRER, $dbReferrer, $comparison);
    }

    /**
     * Filter the query on the user_agent column
     *
     * Example usage:
     * <code>
     * $query->filterByDbUserAgent('fooValue');   // WHERE user_agent = 'fooValue'
     * $query->filterByDbUserAgent('%fooValue%'); // WHERE user_agent LIKE '%fooValue%'
     * </code>
     *
     * @param     string $dbUserAgent The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function filterByDbUserAgent($dbUserAgent = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($dbUserAgent)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $dbUserAgent)) {
                $dbUserAgent = str_replace('*', '%', $dbUserAgent);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListenerStatsPeer::USER_AGENT, $dbUserAgent, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ListenerStats $listenerStats Object to remove from the list of results
     *
     * @return ListenerStatsQuery The current query, for fluid interface
     */
    public function prune($listenerStats = null)
    {
        if ($listenerStats) {
            $this->addUsingAlias(ListenerStatsPeer::ID, $listenerStats->getDbId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
