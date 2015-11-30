<?php



/**
 * This class defines the structure of the 'listener_stats' table.
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
class ListenerStatsTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'airtime.map.ListenerStatsTableMap';

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
        $this->setName('listener_stats');
        $this->setPhpName('ListenerStats');
        $this->setClassname('ListenerStats');
        $this->setPackage('airtime');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('listener_stats_id_seq');
        // columns
        $this->addPrimaryKey('id', 'DbId', 'INTEGER', true, null, null);
        $this->addColumn('disconnect_timestamp', 'DbDisconnectTimestamp', 'TIMESTAMP', true, null, null);
        $this->addColumn('geo_ip', 'DbGeoIp', 'VARCHAR', true, 256, null);
        $this->addColumn('session_duration', 'DbSessionDuration', 'INTEGER', true, null, null);
        $this->addColumn('mount', 'DbMount', 'VARCHAR', true, 256, null);
        $this->addColumn('bytes', 'DbBytes', 'INTEGER', true, null, null);
        $this->addColumn('referrer', 'DbReferrer', 'VARCHAR', true, 4096, null);
        $this->addColumn('device', 'DbDevice', 'VARCHAR', true, 4096, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

} // ListenerStatsTableMap
