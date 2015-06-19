<?php

TestConfiguration::setUp();

class TestConfiguration
{

    static $bootstrap;

    static function setUp()
    {
        set_include_path( implode( PATH_SEPARATOR , array(
                realpath( dirname( __FILE__ ) . '/../application' ) ,
                realpath( dirname( __FILE__ ) . '/../library' ) ,
                get_include_path() ,
        ) ) );

        require_once realpath( dirname( __FILE__ ) . '/../application/Bootstrap.php' );
        require_once 'Zend/Loader/Autoloader.php';
        Zend_Loader_Autoloader::getInstance();

        self::$bootstrap = new Bootstrap( getenv( 'APPLICATION_ENV' ) );
        Zend_Session::$_unitTestEnabled = true;

        Zend_Loader::loadClass( 'Sql' );
        $sql = new Sql();
        $sql->reset();
    }

    static function setUpOrdinanceAndOrdinanceUsersTable()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( SiteConstants::$DROP_ORDINANCE_TABLE );
        $db->query( SiteConstants::$CREATE_ORDINANCE_TABLE );

        $db->query( Sql::$DROP_ORDINANCES_USERS_TABLE );
        $db->query( Sql::$CREATE_ORDINANCES_USERS_TABLE );
    }
    
    static function setUpOrdinanceAndOrdinanceFilesTable()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( SiteConstants::$DROP_ORDINANCE_TABLE );
        $db->query( SiteConstants::$CREATE_ORDINANCE_TABLE );

        $db->query( Sql::$DROP_ORDINANCES_FILES_TABLE );
        $db->query( Sql::$CREATE_ORDINANCES_FILES_TABLE );
    }

    static function createOneDownloadableOrdinance()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_VICEMAYOR_ATTORNEY_COUNCILORS_APPROVALS_FOR_ONE_ORDINANCE );
    }

    static function setupUsersTables()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$DROP_SYS_USERS_TABLE );
        $db->query( Sql::$CREATE_SYS_USERS_TABLE );
        $db->query( Sql::$INSERT_SYS_USERS_TABLE );

        $db->query( Sql::$DROP_SYS_USER_ROLES_TABLE );
        $db->query( Sql::$CREATE_SYS_USER_ROLES_TABLE );
        $db->query( Sql::$INSERT_SYS_USER_ROLES_TABLE );
    }

}
