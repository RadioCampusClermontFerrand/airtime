<?php


/**
 * Base class that represents a query for the 'rotation' table.
 *
 *
 *
 * @method RotationQuery orderByDbId($order = Criteria::ASC) Order by the id column
 * @method RotationQuery orderByDbName($order = Criteria::ASC) Order by the name column
 * @method RotationQuery orderByDbMinimumTrackLength($order = Criteria::ASC) Order by the minimum_track_length column
 * @method RotationQuery orderByDbMaximumTrackLength($order = Criteria::ASC) Order by the maximum_track_length column
 * @method RotationQuery orderByDbPlaylist($order = Criteria::ASC) Order by the playlist column
 *
 * @method RotationQuery groupByDbId() Group by the id column
 * @method RotationQuery groupByDbName() Group by the name column
 * @method RotationQuery groupByDbMinimumTrackLength() Group by the minimum_track_length column
 * @method RotationQuery groupByDbMaximumTrackLength() Group by the maximum_track_length column
 * @method RotationQuery groupByDbPlaylist() Group by the playlist column
 *
 * @method RotationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method RotationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method RotationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method RotationQuery leftJoinCcPlaylist($relationAlias = null) Adds a LEFT JOIN clause to the query using the CcPlaylist relation
 * @method RotationQuery rightJoinCcPlaylist($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CcPlaylist relation
 * @method RotationQuery innerJoinCcPlaylist($relationAlias = null) Adds a INNER JOIN clause to the query using the CcPlaylist relation
 *
 * @method RotationQuery leftJoinCcShowInstances($relationAlias = null) Adds a LEFT JOIN clause to the query using the CcShowInstances relation
 * @method RotationQuery rightJoinCcShowInstances($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CcShowInstances relation
 * @method RotationQuery innerJoinCcShowInstances($relationAlias = null) Adds a INNER JOIN clause to the query using the CcShowInstances relation
 *
 * @method Rotation findOne(PropelPDO $con = null) Return the first Rotation matching the query
 * @method Rotation findOneOrCreate(PropelPDO $con = null) Return the first Rotation matching the query, or a new Rotation object populated from the query conditions when no match is found
 *
 * @method Rotation findOneByDbName(string $name) Return the first Rotation filtered by the name column
 * @method Rotation findOneByDbMinimumTrackLength(int $minimum_track_length) Return the first Rotation filtered by the minimum_track_length column
 * @method Rotation findOneByDbMaximumTrackLength(int $maximum_track_length) Return the first Rotation filtered by the maximum_track_length column
 * @method Rotation findOneByDbPlaylist(int $playlist) Return the first Rotation filtered by the playlist column
 *
 * @method array findByDbId(int $id) Return Rotation objects filtered by the id column
 * @method array findByDbName(string $name) Return Rotation objects filtered by the name column
 * @method array findByDbMinimumTrackLength(int $minimum_track_length) Return Rotation objects filtered by the minimum_track_length column
 * @method array findByDbMaximumTrackLength(int $maximum_track_length) Return Rotation objects filtered by the maximum_track_length column
 * @method array findByDbPlaylist(int $playlist) Return Rotation objects filtered by the playlist column
 *
 * @package    propel.generator.airtime.om
 */
abstract class BaseRotationQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseRotationQuery object.
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
            $modelName = 'Rotation';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new RotationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   RotationQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return RotationQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof RotationQuery) {
            return $criteria;
        }
        $query = new RotationQuery(null, null, $modelAlias);

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
     * @return   Rotation|Rotation[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RotationPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(RotationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Rotation A model object, or null if the key is not found
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
     * @return                 Rotation A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "name", "minimum_track_length", "maximum_track_length", "playlist" FROM "rotation" WHERE "id" = :p0';
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
            $obj = new Rotation();
            $obj->hydrate($row);
            RotationPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Rotation|Rotation[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Rotation[]|mixed the list of results, formatted by the current formatter
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
     * @return RotationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(RotationPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return RotationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(RotationPeer::ID, $keys, Criteria::IN);
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
     * @return RotationQuery The current query, for fluid interface
     */
    public function filterByDbId($dbId = null, $comparison = null)
    {
        if (is_array($dbId)) {
            $useMinMax = false;
            if (isset($dbId['min'])) {
                $this->addUsingAlias(RotationPeer::ID, $dbId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dbId['max'])) {
                $this->addUsingAlias(RotationPeer::ID, $dbId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RotationPeer::ID, $dbId, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByDbName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByDbName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $dbName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RotationQuery The current query, for fluid interface
     */
    public function filterByDbName($dbName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($dbName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $dbName)) {
                $dbName = str_replace('*', '%', $dbName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(RotationPeer::NAME, $dbName, $comparison);
    }

    /**
     * Filter the query on the minimum_track_length column
     *
     * Example usage:
     * <code>
     * $query->filterByDbMinimumTrackLength(1234); // WHERE minimum_track_length = 1234
     * $query->filterByDbMinimumTrackLength(array(12, 34)); // WHERE minimum_track_length IN (12, 34)
     * $query->filterByDbMinimumTrackLength(array('min' => 12)); // WHERE minimum_track_length >= 12
     * $query->filterByDbMinimumTrackLength(array('max' => 12)); // WHERE minimum_track_length <= 12
     * </code>
     *
     * @param     mixed $dbMinimumTrackLength The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RotationQuery The current query, for fluid interface
     */
    public function filterByDbMinimumTrackLength($dbMinimumTrackLength = null, $comparison = null)
    {
        if (is_array($dbMinimumTrackLength)) {
            $useMinMax = false;
            if (isset($dbMinimumTrackLength['min'])) {
                $this->addUsingAlias(RotationPeer::MINIMUM_TRACK_LENGTH, $dbMinimumTrackLength['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dbMinimumTrackLength['max'])) {
                $this->addUsingAlias(RotationPeer::MINIMUM_TRACK_LENGTH, $dbMinimumTrackLength['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RotationPeer::MINIMUM_TRACK_LENGTH, $dbMinimumTrackLength, $comparison);
    }

    /**
     * Filter the query on the maximum_track_length column
     *
     * Example usage:
     * <code>
     * $query->filterByDbMaximumTrackLength(1234); // WHERE maximum_track_length = 1234
     * $query->filterByDbMaximumTrackLength(array(12, 34)); // WHERE maximum_track_length IN (12, 34)
     * $query->filterByDbMaximumTrackLength(array('min' => 12)); // WHERE maximum_track_length >= 12
     * $query->filterByDbMaximumTrackLength(array('max' => 12)); // WHERE maximum_track_length <= 12
     * </code>
     *
     * @param     mixed $dbMaximumTrackLength The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RotationQuery The current query, for fluid interface
     */
    public function filterByDbMaximumTrackLength($dbMaximumTrackLength = null, $comparison = null)
    {
        if (is_array($dbMaximumTrackLength)) {
            $useMinMax = false;
            if (isset($dbMaximumTrackLength['min'])) {
                $this->addUsingAlias(RotationPeer::MAXIMUM_TRACK_LENGTH, $dbMaximumTrackLength['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dbMaximumTrackLength['max'])) {
                $this->addUsingAlias(RotationPeer::MAXIMUM_TRACK_LENGTH, $dbMaximumTrackLength['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RotationPeer::MAXIMUM_TRACK_LENGTH, $dbMaximumTrackLength, $comparison);
    }

    /**
     * Filter the query on the playlist column
     *
     * Example usage:
     * <code>
     * $query->filterByDbPlaylist(1234); // WHERE playlist = 1234
     * $query->filterByDbPlaylist(array(12, 34)); // WHERE playlist IN (12, 34)
     * $query->filterByDbPlaylist(array('min' => 12)); // WHERE playlist >= 12
     * $query->filterByDbPlaylist(array('max' => 12)); // WHERE playlist <= 12
     * </code>
     *
     * @see       filterByCcPlaylist()
     *
     * @param     mixed $dbPlaylist The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RotationQuery The current query, for fluid interface
     */
    public function filterByDbPlaylist($dbPlaylist = null, $comparison = null)
    {
        if (is_array($dbPlaylist)) {
            $useMinMax = false;
            if (isset($dbPlaylist['min'])) {
                $this->addUsingAlias(RotationPeer::PLAYLIST, $dbPlaylist['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dbPlaylist['max'])) {
                $this->addUsingAlias(RotationPeer::PLAYLIST, $dbPlaylist['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RotationPeer::PLAYLIST, $dbPlaylist, $comparison);
    }

    /**
     * Filter the query by a related CcPlaylist object
     *
     * @param   CcPlaylist|PropelObjectCollection $ccPlaylist The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RotationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCcPlaylist($ccPlaylist, $comparison = null)
    {
        if ($ccPlaylist instanceof CcPlaylist) {
            return $this
                ->addUsingAlias(RotationPeer::PLAYLIST, $ccPlaylist->getDbId(), $comparison);
        } elseif ($ccPlaylist instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RotationPeer::PLAYLIST, $ccPlaylist->toKeyValue('PrimaryKey', 'DbId'), $comparison);
        } else {
            throw new PropelException('filterByCcPlaylist() only accepts arguments of type CcPlaylist or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CcPlaylist relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return RotationQuery The current query, for fluid interface
     */
    public function joinCcPlaylist($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CcPlaylist');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CcPlaylist');
        }

        return $this;
    }

    /**
     * Use the CcPlaylist relation CcPlaylist object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   CcPlaylistQuery A secondary query class using the current class as primary query
     */
    public function useCcPlaylistQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCcPlaylist($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CcPlaylist', 'CcPlaylistQuery');
    }

    /**
     * Filter the query by a related CcShowInstances object
     *
     * @param   CcShowInstances|PropelObjectCollection $ccShowInstances  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RotationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCcShowInstances($ccShowInstances, $comparison = null)
    {
        if ($ccShowInstances instanceof CcShowInstances) {
            return $this
                ->addUsingAlias(RotationPeer::ID, $ccShowInstances->getDbRotation(), $comparison);
        } elseif ($ccShowInstances instanceof PropelObjectCollection) {
            return $this
                ->useCcShowInstancesQuery()
                ->filterByPrimaryKeys($ccShowInstances->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCcShowInstances() only accepts arguments of type CcShowInstances or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CcShowInstances relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return RotationQuery The current query, for fluid interface
     */
    public function joinCcShowInstances($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CcShowInstances');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CcShowInstances');
        }

        return $this;
    }

    /**
     * Use the CcShowInstances relation CcShowInstances object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   CcShowInstancesQuery A secondary query class using the current class as primary query
     */
    public function useCcShowInstancesQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCcShowInstances($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CcShowInstances', 'CcShowInstancesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Rotation $rotation Object to remove from the list of results
     *
     * @return RotationQuery The current query, for fluid interface
     */
    public function prune($rotation = null)
    {
        if ($rotation) {
            $this->addUsingAlias(RotationPeer::ID, $rotation->getDbId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
