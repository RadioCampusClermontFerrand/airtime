<?php



/**
 * This class defines the structure of the 'rotation' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.airtime.map
 */
class RotationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'airtime.map.RotationTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('rotation');
        $this->setPhpName('Rotation');
        $this->setClassname('Rotation');
        $this->setPackage('airtime');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('rotation_id_seq');
        // columns
        $this->addPrimaryKey('id', 'DbId', 'INTEGER', true, null, null);
        $this->addColumn('name', 'DbName', 'VARCHAR', true, null, null);
        $this->addColumn('minimum_track_length', 'DbMinimumTrackLength', 'INTEGER', false, null, 60);
        $this->addColumn('maximum_track_length', 'DbMaximumTrackLength', 'INTEGER', false, null, 600);
        $this->addForeignKey('playlist', 'DbPlaylist', 'INTEGER', 'cc_playlist', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CcPlaylist', 'CcPlaylist', RelationMap::MANY_TO_ONE, array('playlist' => 'id', ), 'SET NULL', null);
        $this->addRelation('CcShowInstances', 'CcShowInstances', RelationMap::ONE_TO_MANY, array('id' => 'rotation', ), 'SET NULL', null, 'CcShowInstancess');
    } // buildRelations()

} // RotationTableMap
