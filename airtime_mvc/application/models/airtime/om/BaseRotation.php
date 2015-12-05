<?php


/**
 * Base class that represents a row from the 'rotation' table.
 *
 *
 *
 * @package    propel.generator.airtime.om
 */
abstract class BaseRotation extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'RotationPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        RotationPeer
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
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the criteria field.
     * @var        string
     */
    protected $criteria;

    /**
     * The value for the seed field.
     * @var        double
     */
    protected $seed;

    /**
     * The value for the playlist field.
     * @var        int
     */
    protected $playlist;

    /**
     * @var        CcPlaylist
     */
    protected $aCcPlaylist;

    /**
     * @var        PropelObjectCollection|CcShowInstances[] Collection to store aggregation of CcShowInstances objects.
     */
    protected $collCcShowInstancess;
    protected $collCcShowInstancessPartial;

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
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $ccShowInstancessScheduledForDeletion = null;

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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getDbName()
    {

        return $this->name;
    }

    /**
     * Get the [criteria] column value.
     *
     * @return string
     */
    public function getDbCriteria()
    {

        return $this->criteria;
    }

    /**
     * Get the [seed] column value.
     *
     * @return double
     */
    public function getDbSeed()
    {

        return $this->seed;
    }

    /**
     * Get the [playlist] column value.
     *
     * @return int
     */
    public function getDbPlaylist()
    {

        return $this->playlist;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Rotation The current object (for fluent API support)
     */
    public function setDbId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = RotationPeer::ID;
        }


        return $this;
    } // setDbId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Rotation The current object (for fluent API support)
     */
    public function setDbName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = RotationPeer::NAME;
        }


        return $this;
    } // setDbName()

    /**
     * Set the value of [criteria] column.
     *
     * @param  string $v new value
     * @return Rotation The current object (for fluent API support)
     */
    public function setDbCriteria($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->criteria !== $v) {
            $this->criteria = $v;
            $this->modifiedColumns[] = RotationPeer::CRITERIA;
        }


        return $this;
    } // setDbCriteria()

    /**
     * Set the value of [seed] column.
     *
     * @param  double $v new value
     * @return Rotation The current object (for fluent API support)
     */
    public function setDbSeed($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->seed !== $v) {
            $this->seed = $v;
            $this->modifiedColumns[] = RotationPeer::SEED;
        }


        return $this;
    } // setDbSeed()

    /**
     * Set the value of [playlist] column.
     *
     * @param  int $v new value
     * @return Rotation The current object (for fluent API support)
     */
    public function setDbPlaylist($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->playlist !== $v) {
            $this->playlist = $v;
            $this->modifiedColumns[] = RotationPeer::PLAYLIST;
        }

        if ($this->aCcPlaylist !== null && $this->aCcPlaylist->getDbId() !== $v) {
            $this->aCcPlaylist = null;
        }


        return $this;
    } // setDbPlaylist()

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
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->criteria = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->seed = ($row[$startcol + 3] !== null) ? (double) $row[$startcol + 3] : null;
            $this->playlist = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 5; // 5 = RotationPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Rotation object", $e);
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

        if ($this->aCcPlaylist !== null && $this->playlist !== $this->aCcPlaylist->getDbId()) {
            $this->aCcPlaylist = null;
        }
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
            $con = Propel::getConnection(RotationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = RotationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCcPlaylist = null;
            $this->collCcShowInstancess = null;

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
            $con = Propel::getConnection(RotationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = RotationQuery::create()
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
            $con = Propel::getConnection(RotationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                RotationPeer::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCcPlaylist !== null) {
                if ($this->aCcPlaylist->isModified() || $this->aCcPlaylist->isNew()) {
                    $affectedRows += $this->aCcPlaylist->save($con);
                }
                $this->setCcPlaylist($this->aCcPlaylist);
            }

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

            if ($this->ccShowInstancessScheduledForDeletion !== null) {
                if (!$this->ccShowInstancessScheduledForDeletion->isEmpty()) {
                    foreach ($this->ccShowInstancessScheduledForDeletion as $ccShowInstances) {
                        // need to save related object because we set the relation to null
                        $ccShowInstances->save($con);
                    }
                    $this->ccShowInstancessScheduledForDeletion = null;
                }
            }

            if ($this->collCcShowInstancess !== null) {
                foreach ($this->collCcShowInstancess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[] = RotationPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . RotationPeer::ID . ')');
        }
        if (null === $this->id) {
            try {
                $stmt = $con->query("SELECT nextval('rotation_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(RotationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }
        if ($this->isColumnModified(RotationPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '"name"';
        }
        if ($this->isColumnModified(RotationPeer::CRITERIA)) {
            $modifiedColumns[':p' . $index++]  = '"criteria"';
        }
        if ($this->isColumnModified(RotationPeer::SEED)) {
            $modifiedColumns[':p' . $index++]  = '"seed"';
        }
        if ($this->isColumnModified(RotationPeer::PLAYLIST)) {
            $modifiedColumns[':p' . $index++]  = '"playlist"';
        }

        $sql = sprintf(
            'INSERT INTO "rotation" (%s) VALUES (%s)',
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
                    case '"name"':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '"criteria"':
                        $stmt->bindValue($identifier, $this->criteria, PDO::PARAM_STR);
                        break;
                    case '"seed"':
                        $stmt->bindValue($identifier, $this->seed, PDO::PARAM_STR);
                        break;
                    case '"playlist"':
                        $stmt->bindValue($identifier, $this->playlist, PDO::PARAM_INT);
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


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCcPlaylist !== null) {
                if (!$this->aCcPlaylist->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCcPlaylist->getValidationFailures());
                }
            }


            if (($retval = RotationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCcShowInstancess !== null) {
                    foreach ($this->collCcShowInstancess as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
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
        $pos = RotationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getDbName();
                break;
            case 2:
                return $this->getDbCriteria();
                break;
            case 3:
                return $this->getDbSeed();
                break;
            case 4:
                return $this->getDbPlaylist();
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
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Rotation'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Rotation'][$this->getPrimaryKey()] = true;
        $keys = RotationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getDbId(),
            $keys[1] => $this->getDbName(),
            $keys[2] => $this->getDbCriteria(),
            $keys[3] => $this->getDbSeed(),
            $keys[4] => $this->getDbPlaylist(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCcPlaylist) {
                $result['CcPlaylist'] = $this->aCcPlaylist->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCcShowInstancess) {
                $result['CcShowInstancess'] = $this->collCcShowInstancess->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
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
        $pos = RotationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setDbName($value);
                break;
            case 2:
                $this->setDbCriteria($value);
                break;
            case 3:
                $this->setDbSeed($value);
                break;
            case 4:
                $this->setDbPlaylist($value);
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
        $keys = RotationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setDbId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setDbName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDbCriteria($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDbSeed($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDbPlaylist($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(RotationPeer::DATABASE_NAME);

        if ($this->isColumnModified(RotationPeer::ID)) $criteria->add(RotationPeer::ID, $this->id);
        if ($this->isColumnModified(RotationPeer::NAME)) $criteria->add(RotationPeer::NAME, $this->name);
        if ($this->isColumnModified(RotationPeer::CRITERIA)) $criteria->add(RotationPeer::CRITERIA, $this->criteria);
        if ($this->isColumnModified(RotationPeer::SEED)) $criteria->add(RotationPeer::SEED, $this->seed);
        if ($this->isColumnModified(RotationPeer::PLAYLIST)) $criteria->add(RotationPeer::PLAYLIST, $this->playlist);

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
        $criteria = new Criteria(RotationPeer::DATABASE_NAME);
        $criteria->add(RotationPeer::ID, $this->id);

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
     * @param object $copyObj An object of Rotation (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setDbName($this->getDbName());
        $copyObj->setDbCriteria($this->getDbCriteria());
        $copyObj->setDbSeed($this->getDbSeed());
        $copyObj->setDbPlaylist($this->getDbPlaylist());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCcShowInstancess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCcShowInstances($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

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
     * @return Rotation Clone of current object.
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
     * @return RotationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new RotationPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a CcPlaylist object.
     *
     * @param                  CcPlaylist $v
     * @return Rotation The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCcPlaylist(CcPlaylist $v = null)
    {
        if ($v === null) {
            $this->setDbPlaylist(NULL);
        } else {
            $this->setDbPlaylist($v->getDbId());
        }

        $this->aCcPlaylist = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the CcPlaylist object, it will not be re-added.
        if ($v !== null) {
            $v->addRotation($this);
        }


        return $this;
    }


    /**
     * Get the associated CcPlaylist object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return CcPlaylist The associated CcPlaylist object.
     * @throws PropelException
     */
    public function getCcPlaylist(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCcPlaylist === null && ($this->playlist !== null) && $doQuery) {
            $this->aCcPlaylist = CcPlaylistQuery::create()->findPk($this->playlist, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCcPlaylist->addRotations($this);
             */
        }

        return $this->aCcPlaylist;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('CcShowInstances' == $relationName) {
            $this->initCcShowInstancess();
        }
    }

    /**
     * Clears out the collCcShowInstancess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Rotation The current object (for fluent API support)
     * @see        addCcShowInstancess()
     */
    public function clearCcShowInstancess()
    {
        $this->collCcShowInstancess = null; // important to set this to null since that means it is uninitialized
        $this->collCcShowInstancessPartial = null;

        return $this;
    }

    /**
     * reset is the collCcShowInstancess collection loaded partially
     *
     * @return void
     */
    public function resetPartialCcShowInstancess($v = true)
    {
        $this->collCcShowInstancessPartial = $v;
    }

    /**
     * Initializes the collCcShowInstancess collection.
     *
     * By default this just sets the collCcShowInstancess collection to an empty array (like clearcollCcShowInstancess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCcShowInstancess($overrideExisting = true)
    {
        if (null !== $this->collCcShowInstancess && !$overrideExisting) {
            return;
        }
        $this->collCcShowInstancess = new PropelObjectCollection();
        $this->collCcShowInstancess->setModel('CcShowInstances');
    }

    /**
     * Gets an array of CcShowInstances objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Rotation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CcShowInstances[] List of CcShowInstances objects
     * @throws PropelException
     */
    public function getCcShowInstancess($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCcShowInstancessPartial && !$this->isNew();
        if (null === $this->collCcShowInstancess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCcShowInstancess) {
                // return empty collection
                $this->initCcShowInstancess();
            } else {
                $collCcShowInstancess = CcShowInstancesQuery::create(null, $criteria)
                    ->filterByRotation($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCcShowInstancessPartial && count($collCcShowInstancess)) {
                      $this->initCcShowInstancess(false);

                      foreach ($collCcShowInstancess as $obj) {
                        if (false == $this->collCcShowInstancess->contains($obj)) {
                          $this->collCcShowInstancess->append($obj);
                        }
                      }

                      $this->collCcShowInstancessPartial = true;
                    }

                    $collCcShowInstancess->getInternalIterator()->rewind();

                    return $collCcShowInstancess;
                }

                if ($partial && $this->collCcShowInstancess) {
                    foreach ($this->collCcShowInstancess as $obj) {
                        if ($obj->isNew()) {
                            $collCcShowInstancess[] = $obj;
                        }
                    }
                }

                $this->collCcShowInstancess = $collCcShowInstancess;
                $this->collCcShowInstancessPartial = false;
            }
        }

        return $this->collCcShowInstancess;
    }

    /**
     * Sets a collection of CcShowInstances objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $ccShowInstancess A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Rotation The current object (for fluent API support)
     */
    public function setCcShowInstancess(PropelCollection $ccShowInstancess, PropelPDO $con = null)
    {
        $ccShowInstancessToDelete = $this->getCcShowInstancess(new Criteria(), $con)->diff($ccShowInstancess);


        $this->ccShowInstancessScheduledForDeletion = $ccShowInstancessToDelete;

        foreach ($ccShowInstancessToDelete as $ccShowInstancesRemoved) {
            $ccShowInstancesRemoved->setRotation(null);
        }

        $this->collCcShowInstancess = null;
        foreach ($ccShowInstancess as $ccShowInstances) {
            $this->addCcShowInstances($ccShowInstances);
        }

        $this->collCcShowInstancess = $ccShowInstancess;
        $this->collCcShowInstancessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CcShowInstances objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CcShowInstances objects.
     * @throws PropelException
     */
    public function countCcShowInstancess(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCcShowInstancessPartial && !$this->isNew();
        if (null === $this->collCcShowInstancess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCcShowInstancess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCcShowInstancess());
            }
            $query = CcShowInstancesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRotation($this)
                ->count($con);
        }

        return count($this->collCcShowInstancess);
    }

    /**
     * Method called to associate a CcShowInstances object to this object
     * through the CcShowInstances foreign key attribute.
     *
     * @param    CcShowInstances $l CcShowInstances
     * @return Rotation The current object (for fluent API support)
     */
    public function addCcShowInstances(CcShowInstances $l)
    {
        if ($this->collCcShowInstancess === null) {
            $this->initCcShowInstancess();
            $this->collCcShowInstancessPartial = true;
        }

        if (!in_array($l, $this->collCcShowInstancess->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCcShowInstances($l);

            if ($this->ccShowInstancessScheduledForDeletion and $this->ccShowInstancessScheduledForDeletion->contains($l)) {
                $this->ccShowInstancessScheduledForDeletion->remove($this->ccShowInstancessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CcShowInstances $ccShowInstances The ccShowInstances object to add.
     */
    protected function doAddCcShowInstances($ccShowInstances)
    {
        $this->collCcShowInstancess[]= $ccShowInstances;
        $ccShowInstances->setRotation($this);
    }

    /**
     * @param	CcShowInstances $ccShowInstances The ccShowInstances object to remove.
     * @return Rotation The current object (for fluent API support)
     */
    public function removeCcShowInstances($ccShowInstances)
    {
        if ($this->getCcShowInstancess()->contains($ccShowInstances)) {
            $this->collCcShowInstancess->remove($this->collCcShowInstancess->search($ccShowInstances));
            if (null === $this->ccShowInstancessScheduledForDeletion) {
                $this->ccShowInstancessScheduledForDeletion = clone $this->collCcShowInstancess;
                $this->ccShowInstancessScheduledForDeletion->clear();
            }
            $this->ccShowInstancessScheduledForDeletion[]= $ccShowInstances;
            $ccShowInstances->setRotation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Rotation is new, it will return
     * an empty collection; or if this Rotation has previously
     * been saved, it will retrieve related CcShowInstancess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Rotation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CcShowInstances[] List of CcShowInstances objects
     */
    public function getCcShowInstancessJoinCcShow($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CcShowInstancesQuery::create(null, $criteria);
        $query->joinWith('CcShow', $join_behavior);

        return $this->getCcShowInstancess($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Rotation is new, it will return
     * an empty collection; or if this Rotation has previously
     * been saved, it will retrieve related CcShowInstancess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Rotation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CcShowInstances[] List of CcShowInstances objects
     */
    public function getCcShowInstancessJoinCcShowInstancesRelatedByDbOriginalShow($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CcShowInstancesQuery::create(null, $criteria);
        $query->joinWith('CcShowInstancesRelatedByDbOriginalShow', $join_behavior);

        return $this->getCcShowInstancess($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Rotation is new, it will return
     * an empty collection; or if this Rotation has previously
     * been saved, it will retrieve related CcShowInstancess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Rotation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CcShowInstances[] List of CcShowInstances objects
     */
    public function getCcShowInstancessJoinCcFiles($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CcShowInstancesQuery::create(null, $criteria);
        $query->joinWith('CcFiles', $join_behavior);

        return $this->getCcShowInstancess($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->criteria = null;
        $this->seed = null;
        $this->playlist = null;
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
            if ($this->collCcShowInstancess) {
                foreach ($this->collCcShowInstancess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aCcPlaylist instanceof Persistent) {
              $this->aCcPlaylist->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCcShowInstancess instanceof PropelCollection) {
            $this->collCcShowInstancess->clearIterator();
        }
        $this->collCcShowInstancess = null;
        $this->aCcPlaylist = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(RotationPeer::DEFAULT_STRING_FORMAT);
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
