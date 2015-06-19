<?php

Zend_Loader::loadClass( 'DB_Ordinances' );
Zend_Loader::loadClass( 'Ordinances' );

class models_DownloadsTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        call_user_func( array( TestConfiguration::$bootstrap , "configure" ) );
        TestConfiguration::setUpOrdinanceAndOrdinanceUsersTable();
        TestConfiguration::setUpOrdinanceAndOrdinanceFilesTable();
    }

    public function testPublishedOrdinance()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_VICEMAYOR_ATTORNEY_COUNCILORS_APPROVALS_FOR_ONE_ORDINANCE );
        $db->query( Sql::$ALTER_ORDINANCE_STATUS );

        $data = array(
                'name' => 'Ordinance one' ,
                'status' => 'PUBLISHED' ,
                'type' => 'ORDINANCE' ,
                'searchable' => 1
        );

        $select = <<<EOT
                SELECT name, summary, status, type, searchable
                FROM b_ordinances                
EOT;
        $where = $db->quoteInto( 'WHERE name=?' , $data[ 'name' ] ) . ' && ';
        $where.=$db->quoteInto( 'status=?' , $data[ 'status' ] ) . ' && ';
        $where.=$db->quoteInto( 'type=?' , $data[ 'type' ] ) . ' && ';
        $where.='searchable=1';
        $dbOrdinances = $db->fetchAll( $select . ' ' . $where , "" );

        $this->assertSame( 1 , count( $dbOrdinances ) );
        $this->assertSame( $data[ 'name' ] , $dbOrdinances[ 0 ][ 'name' ] );
        $this->assertSame( $data[ 'status' ] , $dbOrdinances[ 0 ][ 'status' ] );
        $this->assertSame( $data[ 'type' ] , $dbOrdinances[ 0 ][ 'type' ] );
    }

    public function testProcurementFiles()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_PROCUREMENT_PUBLISHED );
        $db->query( Sql::$INSERT_FILES_TO_ONE_PROCUREMENT );
    }

    public function testBudgetFiles()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_BUDGET_PUBLISHED );
        $db->query( Sql::$INSERT_FILES_TO_ONE_BUDGET );
    }

}
