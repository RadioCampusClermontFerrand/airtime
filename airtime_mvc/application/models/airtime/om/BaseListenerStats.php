<?php


/**
 * Base class that represents a row from the 'listener_stats' table.
 *
 *
 *
 * @package    propel.generator.airtime.om
 */
abstract class BaseListenerStats extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'ListenerStatsPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ListenerStatsPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the disconnect_timestamp field.
     * @var        string
     */
    protected $disconnect_timestamp;

    /**
     * The value for the ip field.
     * @var        string
     */
    protected $ip;

    /**
     * The value for the city field.
     * @var        string
     */
    protected $city;

    /**
     * The value for the country_name field.
     * @var        string
     */
    protected $country_name;

    /**
     * The value for the country_iso_code field.
     * @var        string
     */
    protected $country_iso_code;

    /**
     * The value for the session_duration field.
     * @var        int
     */
    protected $session_duration;

    /**
     * The value for the mount field.
     * @var        string
     */
    protected $mount;

    /**
     * The value for the bytes field.
     * @var        int
     */
    protected $bytes;

    /**
     * The value for the referrer field.
     * @var        string
     */
    protected $referrer;

    /**
     * The value for the user_agent field.
     * @var        string
     */
    protected $user_agent;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getDbId()
    {

        return $this->id;
    }

    /**
     * Get the [optionally formatted] temporal [disconnect_timestamp] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDbDisconnectTimestamp($format = 'Y-m-d H:i:s')
    {
        if ($this->disconnect_timestamp === null) {
            return null;
        }


        try {
            $dt = new DateTime($this->disconnect_timestamp);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->disconnect_timestamp, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [ip] column value.
     *
     * @return string
     */
    public function getDbIp()
    {

        return $this->ip;
    }

    /**
     * Get the [city] column value.
     *
     * @return string
     */
    public function getDbCity()
    {

        return $this->city;
    }

    /**
     * Get the [country_name] column value.
     *
     * @return string
     */
    public function getDbCountryName()
    {

        return $this->country_name;
    }

    /**
     * Get the [country_iso_code] column value.
     *
     * @return string
     */
    public function getDbCountryIsoCode()
    {

        return $this->country_iso_code;
    }

    /**
     * Get the [session_duration] column value.
     *
     * @return int
     */
    public function getDbSessionDuration()
    {

        return $this->session_duration;
    }

    /**
     * Get the [mount] column value.
     *
     * @return string
     */
    public function getDbMount()
    {

        return $this->mount;
    }

    /**
     * Get the [bytes] column value.
     *
     * @return int
     */
    public function getDbBytes()
    {

        return $this->bytes;
    }

    /**
     * Get the [referrer] column value.
     *
     * @return string
     */
    public function getDbReferrer()
    {

        return $this->referrer;
    }

    /**
     * Get the [user_agent] column value.
     *
     * @return string
     */
    public function getDbUserAgent()
    {

        return $this->user_agent;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return ListenerStats The current object (for fluent API support)
     */
    public function setDbId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ListenerStatsPeer::ID;
        }


        return $this;
    } // setDbId()

    /**
     * Sets the value of [disconnect_timestamp] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return ListenerStats The current object (for fluent API support)
     */
    public function setDbDisconnectTimestamp($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->disconnect_timestamp !== null || $dt !== null) {
            $currentDateAsString = ($this->disconnect_timestamp !== null && $tmpDt = new DateTime($this->disconnect_timestamp)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->disconnect_timestamp = $newDateAsString;
                $this->modifiedColumns[] = ListenerStatsPeer::DISCONNECT_TIMESTAMP;
            }
        } // if either are not null


        return $this;
    } // setDbDisconnectTimestamp()

    /**
     * Set the value of [ip] column.
     *
     * @param  string $v new value
     * @return ListenerStats The current object (for fluent API support)
     */
    public function setDbIp($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->ip !== $v) {
            $this->ip = $v;
            $this->modifiedColumns[] = ListenerStatsPeer::IP;
        }


        return $this;
    } // setDbIp()

    /**
     * Set the value of [city] column.
     *
     * @param  string $v new value
     * @return ListenerStats The current object (for fluent API support)
     */
    public function setDbCity($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[] = ListenerStatsPeer::CITY;
        }


        return $this;
    } // setDbCity()

    /**
     * Set the value of [country_name] column.
     *
     * @param  string $v new value
     * @return ListenerStats The current object (for fluent API support)
     */
    public function setDbCountryName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->country_name !== $v) {
            $this->country_name = $v;
            $this->modifiedColumns[] = ListenerStatsPeer::COUNTRY_NAME;
        }


        return $this;
    } // setDbCountryName()

    /**
     * Set the value of [country_iso_code] column.
     *
     * @param  string $v new value
     * @return ListenerStats The current object (for fluent API support)
     */
    public function setDbCountryIsoCode($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->country_iso_code !== $v) {
            $this->country_iso_code = $v;
            $this->modifiedColumns[] = ListenerStatsPeer::COUNTRY_ISO_CODE;
        }


        return $this;
    } // setDbCountryIsoCode()

    /**
     * Set the value of [session_duration] column.
     *
     * @param  int $v new value
     * @return ListenerStats The current object (for fluent API support)
     */
    public function setDbSessionDuration($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->session_duration !== $v) {
            $this->session_duration = $v;
            $this->modifiedColumns[] = ListenerStatsPeer::SESSION_DURATION;
        }


        return $this;
    } // setDbSessionDuration()

    /**
     * Set the value of [mount] column.
     *
     * @param  string $v new value
     * @return ListenerStats The current object (for fluent API support)
     */
    public function setDbMount($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->mount !== $v) {
            $this->mount = $v;
            $this->modifiedColumns[] = ListenerStatsPeer::MOUNT;
        }


        return $this;
    } // setDbMount()

    /**
     * Set the value of [bytes] column.
     *
     * @param  int $v new value
     * @return ListenerStats The current object (for fluent API support)
     */
    public function setDbBytes($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->bytes !== $v) {
            $this->bytes = $v;
            $this->modifiedColumns[] = ListenerStatsPeer::BYTES;
        }


        return $this;
    } // setDbBytes()

    /**
     * Set the value of [referrer] column.
     *
     * @param  string $v new value
     * @return ListenerStats The current object (for fluent API support)
     */
    public function setDbReferrer($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->referrer !== $v) {
            $this->referrer = $v;
            $this->modifiedColumns[] = ListenerStatsPeer::REFERRER;
        }


        return $this;
    } // setDbReferrer()

    /**
     * Set the value of [user_agent] column.
     *
     * @param  string $v new value
     * @return ListenerStats The current object (for fluent API support)
     */
    public function setDbUserAgent($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->user_agent !== $v) {
            $this->user_agent = $v;
            $this->modifiedColumns[] = ListenerStatsPeer::USER_AGENT;
        }


        return $this;
    } // setDbUserAgent()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->disconnect_timestamp = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->ip = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->city = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->country_name = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->country_iso_code = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->session_duration = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->mount = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->bytes = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->referrer = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->user_agent = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 11; // 11 = ListenerStatsPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating ListenerStats object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(ListenerStatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ListenerStatsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(ListenerStatsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ListenerStatsQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(ListenerStatsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                ListenerStatsPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = ListenerStatsPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ListenerStatsPeer::ID . ')');
        }
        if (null === $this->id) {
            try {
                $stmt = $con->query("SELECT nextval('listener_stats_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ListenerStatsPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }
        if ($this->isColumnModified(ListenerStatsPeer::DISCONNECT_TIMESTAMP)) {
            $modifiedColumns[':p' . $index++]  = '"disconnect_timestamp"';
        }
        if ($this->isColumnModified(ListenerStatsPeer::IP)) {
            $modifiedColumns[':p' . $index++]  = '"ip"';
        }
        if ($this->isColumnModified(ListenerStatsPeer::CITY)) {
            $modifiedColumns[':p' . $index++]  = '"city"';
        }
        if ($this->isColumnModified(ListenerStatsPeer::COUNTRY_NAME)) {
            $modifiedColumns[':p' . $index++]  = '"country_name"';
        }
        if ($this->isColumnModified(ListenerStatsPeer::COUNTRY_ISO_CODE)) {
            $modifiedColumns[':p' . $index++]  = '"country_iso_code"';
        }
        if ($this->isColumnModified(ListenerStatsPeer::SESSION_DURATION)) {
            $modifiedColumns[':p' . $index++]  = '"session_duration"';
        }
        if ($this->isColumnModified(ListenerStatsPeer::MOUNT)) {
            $modifiedColumns[':p' . $index++]  = '"mount"';
        }
        if ($this->isColumnModified(ListenerStatsPeer::BYTES)) {
            $modifiedColumns[':p' . $index++]  = '"bytes"';
        }
        if ($this->isColumnModified(ListenerStatsPeer::REFERRER)) {
            $modifiedColumns[':p' . $index++]  = '"referrer"';
        }
        if ($this->isColumnModified(ListenerStatsPeer::USER_AGENT)) {
            $modifiedColumns[':p' . $index++]  = '"user_agent"';
        }

        $sql = sprintf(
            'INSERT INTO "listener_stats" (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '"id"':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '"disconnect_timestamp"':
                        $stmt->bindValue($identifier, $this->disconnect_timestamp, PDO::PARAM_STR);
                        break;
                    case '"ip"':
                        $stmt->bindValue($identifier, $this->ip, PDO::PARAM_STR);
                        break;
                    case '"city"':
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);
                        break;
                    case '"country_name"':
                        $stmt->bindValue($identifier, $this->country_name, PDO::PARAM_STR);
                        break;
                    case '"country_iso_code"':
                        $stmt->bindValue($identifier, $this->country_iso_code, PDO::PARAM_STR);
                        break;
                    case '"session_duration"':
                        $stmt->bindValue($identifier, $this->session_duration, PDO::PARAM_INT);
                        break;
                    case '"mount"':
                        $stmt->bindValue($identifier, $this->mount, PDO::PARAM_STR);
                        break;
                    case '"bytes"':
                        $stmt->bindValue($identifier, $this->bytes, PDO::PARAM_INT);
                        break;
                    case '"referrer"':
                        $stmt->bindValue($identifier, $this->referrer, PDO::PARAM_STR);
                        break;
                    case '"user_agent"':
                        $stmt->bindValue($identifier, $this->user_agent, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = ListenerStatsPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }



            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = ListenerStatsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getDbId();
                break;
            case 1:
                return $this->getDbDisconnectTimestamp();
                break;
            case 2:
                return $this->getDbIp();
                break;
            case 3:
                return $this->getDbCity();
                break;
            case 4:
                return $this->getDbCountryName();
                break;
            case 5:
                return $this->getDbCountryIsoCode();
                break;
            case 6:
                return $this->getDbSessionDuration();
                break;
            case 7:
                return $this->getDbMount();
                break;
            case 8:
                return $this->getDbBytes();
                break;
            case 9:
                return $this->getDbReferrer();
                break;
            case 10:
                return $this->getDbUserAgent();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {
        if (isset($alreadyDumpedObjects['ListenerStats'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ListenerStats'][$this->getPrimaryKey()] = true;
        $keys = ListenerStatsPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getDbId(),
            $keys[1] => $this->getDbDisconnectTimestamp(),
            $keys[2] => $this->getDbIp(),
            $keys[3] => $this->getDbCity(),
            $keys[4] => $this->getDbCountryName(),
            $keys[5] => $this->getDbCountryIsoCode(),
            $keys[6] => $this->getDbSessionDuration(),
            $keys[7] => $this->getDbMount(),
            $keys[8] => $this->getDbBytes(),
            $keys[9] => $this->getDbReferrer(),
            $keys[10] => $this->getDbUserAgent(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }


        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = ListenerStatsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setDbId($value);
                break;
            case 1:
                $this->setDbDisconnectTimestamp($value);
                break;
            case 2:
                $this->setDbIp($value);
                break;
            case 3:
                $this->setDbCity($value);
                break;
            case 4:
                $this->setDbCountryName($value);
                break;
            case 5:
                $this->setDbCountryIsoCode($value);
                break;
            case 6:
                $this->setDbSessionDuration($value);
                break;
            case 7:
                $this->setDbMount($value);
                break;
            case 8:
                $this->setDbBytes($value);
                break;
            case 9:
                $this->setDbReferrer($value);
                break;
            case 10:
                $this->setDbUserAgent($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = ListenerStatsPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setDbId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setDbDisconnectTimestamp($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDbIp($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDbCity($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDbCountryName($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setDbCountryIsoCode($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setDbSessionDuration($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setDbMount($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setDbBytes($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setDbReferrer($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setDbUserAgent($arr[$keys[10]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ListenerStatsPeer::DATABASE_NAME);

        if ($this->isColumnModified(ListenerStatsPeer::ID)) $criteria->add(ListenerStatsPeer::ID, $this->id);
        if ($this->isColumnModified(ListenerStatsPeer::DISCONNECT_TIMESTAMP)) $criteria->add(ListenerStatsPeer::DISCONNECT_TIMESTAMP, $this->disconnect_timestamp);
        if ($this->isColumnModified(ListenerStatsPeer::IP)) $criteria->add(ListenerStatsPeer::IP, $this->ip);
        if ($this->isColumnModified(ListenerStatsPeer::CITY)) $criteria->add(ListenerStatsPeer::CITY, $this->city);
        if ($this->isColumnModified(ListenerStatsPeer::COUNTRY_NAME)) $criteria->add(ListenerStatsPeer::COUNTRY_NAME, $this->country_name);
        if ($this->isColumnModified(ListenerStatsPeer::COUNTRY_ISO_CODE)) $criteria->add(ListenerStatsPeer::COUNTRY_ISO_CODE, $this->country_iso_code);
        if ($this->isColumnModified(ListenerStatsPeer::SESSION_DURATION)) $criteria->add(ListenerStatsPeer::SESSION_DURATION, $this->session_duration);
        if ($this->isColumnModified(ListenerStatsPeer::MOUNT)) $criteria->add(ListenerStatsPeer::MOUNT, $this->mount);
        if ($this->isColumnModified(ListenerStatsPeer::BYTES)) $criteria->add(ListenerStatsPeer::BYTES, $this->bytes);
        if ($this->isColumnModified(ListenerStatsPeer::REFERRER)) $criteria->add(ListenerStatsPeer::REFERRER, $this->referrer);
        if ($this->isColumnModified(ListenerStatsPeer::USER_AGENT)) $criteria->add(ListenerStatsPeer::USER_AGENT, $this->user_agent);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(ListenerStatsPeer::DATABASE_NAME);
        $criteria->add(ListenerStatsPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getDbId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setDbId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getDbId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of ListenerStats (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setDbDisconnectTimestamp($this->getDbDisconnectTimestamp());
        $copyObj->setDbIp($this->getDbIp());
        $copyObj->setDbCity($this->getDbCity());
        $copyObj->setDbCountryName($this->getDbCountryName());
        $copyObj->setDbCountryIsoCode($this->getDbCountryIsoCode());
        $copyObj->setDbSessionDuration($this->getDbSessionDuration());
        $copyObj->setDbMount($this->getDbMount());
        $copyObj->setDbBytes($this->getDbBytes());
        $copyObj->setDbReferrer($this->getDbReferrer());
        $copyObj->setDbUserAgent($this->getDbUserAgent());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setDbId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return ListenerStats Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return ListenerStatsPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ListenerStatsPeer();
        }

        return self::$peer;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->disconnect_timestamp = null;
        $this->ip = null;
        $this->city = null;
        $this->country_name = null;
        $this->country_iso_code = null;
        $this->session_duration = null;
        $this->mount = null;
        $this->bytes = null;
        $this->referrer = null;
        $this->user_agent = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ListenerStatsPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
