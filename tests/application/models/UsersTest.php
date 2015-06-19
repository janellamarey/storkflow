<?php

Zend_Loader::loadClass( 'DB_Ordinances' );
Zend_Loader::loadClass( 'Ordinances' );

class models_UsersTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        call_user_func( array( TestConfiguration::$bootstrap , "configure" ) );
        TestConfiguration::setupUsersTables();
    }

    public function testGetCouncilors()
    {
        $ordinances = new Users();
        $councilors = $ordinances->getCouncilors();
        $this->assertSame( 13 , count( $councilors ) );
        $this->assertTrue( in_array( '3' , $councilors , true ) );
        $this->assertTrue( in_array( '15' , $councilors , true ) );
    }

    public function testGetSuperusers()
    {
        $ordinances = new Users();
        $superusers = $ordinances->getSuperusers();
        $this->assertSame( 1 , count( $superusers ) );
        $this->assertTrue( in_array( '2' , $superusers , true ) );
    }

    public function testGetSuperadmins()
    {
        $ordinances = new Users();
        $superadmins = $ordinances->getSuperadmins();
        $this->assertSame( 1 , count( $superadmins ) );
        $this->assertTrue( in_array( '1' , $superadmins , true ) );
    }

    public function testUserApprove()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_SYS_USER );
        $db->query( Sql::$INSERT_ONE_SYS_USER_ROLE );
    }

}
