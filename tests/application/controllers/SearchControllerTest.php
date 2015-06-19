<?php
class controllers_SearchControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    public function setUp()
    {
        $this->bootstrap = array( TestConfiguration::$bootstrap , 'configure' );
        parent::setUp();
    }

    public function testIndexAction()
    {
        $this->dispatch( '/search/index?q=fabian' );

        //assertions
        $this->assertModule( 'default' );
        $this->assertController( 'search' );
        $this->assertAction( 'index' );
        $this->assertQueryContentContains( 'a' , 'Fabian' );        
    }

}
