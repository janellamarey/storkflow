<?php

class controllers_UsersControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = array( TestConfiguration::$bootstrap , 'configure' );
        parent::setUp();
    }

    public function testPwcEmailsForSuperUser()
    {
        $this->resetRequest();
        $this->resetResponse();
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'username' => 'khalid' , 'password' => 'a' ) );
        $this->dispatch( '/login/index' );
        $this->assertRedirectTo( '/index/' );

        $this->resetRequest();
        $this->resetResponse();
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'pwc' => '2' ) );
        $this->dispatch( '/users/pwcemails' );
        $this->assertController( 'users' );
        $this->assertAction( 'pwcemails' );
    }
    
    public function testPwcEmailsForSuperadmin()
    {
        $this->resetRequest();
        $this->resetResponse();
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'username' => 'cevaristo' , 'password' => 'a' ) );
        $this->dispatch( '/login/index' );
        $this->assertRedirectTo( '/index/' );

        $this->resetRequest();
        $this->resetResponse();
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'pwc' => '2' ) );
        $this->dispatch( '/users/pwcemails' );
        $this->assertController( 'users' );
        $this->assertAction( 'pwcemails' );
    }
    
    public function testPwcEmailsForCouncilors()
    {
        $this->resetRequest();
        $this->resetResponse();
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'username' => 'rfabian' , 'password' => 'a' ) );
        $this->dispatch( '/login/index' );
        $this->assertRedirectTo( '/index/' );

        $this->resetRequest();
        $this->resetResponse();
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'pwc' => '2' ) );
        $this->dispatch( '/users/pwcemails' );
        $this->assertController( 'users' );
        $this->assertAction( 'pwcemails' );
    }
    
    public function testPwcEmailsForAdmin()
    {
        $this->resetRequest();
        $this->resetResponse();
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'username' => 'admin1' , 'password' => 'a' ) );
        $this->dispatch( '/login/index' );
        $this->assertRedirectTo( '/index/' );

        $this->resetRequest();
        $this->resetResponse();
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'pwc' => '2' ) );
        $this->dispatch( '/users/pwcemails' );
        //should not be allowed
        $this->assertController( 'index' );
    }
    
    public function testPwcEmailsForVoters()
    {
        $this->resetRequest();
        $this->resetResponse();
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'username' => 'voter1' , 'password' => 'a' ) );
        $this->dispatch( '/login/index' );
        $this->assertRedirectTo( '/index/' );

        $this->resetRequest();
        $this->resetResponse();
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'pwc' => '2' ) );
        $this->dispatch( '/users/pwcemails' );
        //should not be allowed
        $this->assertController( 'index' );
    }
    
    public function testPwcEmailLinkIsVisible()
    {
        //should be available for superadmin, superuser, councilor
        $this->resetRequest();
        $this->resetResponse();        
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'username' => 'khalid' , 'password' => 'a' ) );
        $this->dispatch( '/login/index' );
        $this->assertRedirectTo( '/index/' );
        
        $this->resetRequest();
        $this->resetResponse();  
        $this->dispatch( '/accounts/index' );
        $this->assertQueryContentContains( 'a', 'Change password change email recurrence' );
        
        $this->resetRequest();
        $this->resetResponse();
        $this->dispatch( '/logout' );
        $this->assertRedirectTo( '/' );
        
        $this->resetRequest();
        $this->resetResponse();        
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'username' => 'cevaristo' , 'password' => 'a' ) );
        $this->dispatch( '/login/index' );
        $this->assertRedirectTo( '/index/' );
        
        $this->resetRequest();
        $this->resetResponse();  
        $this->dispatch( '/accounts/index' );
        $this->assertQueryContentContains( 'a', 'Change password change email recurrence' );
        
        $this->resetRequest();
        $this->resetResponse();
        $this->dispatch( '/logout' );
        $this->assertRedirectTo( '/' );
        
        $this->resetRequest();
        $this->resetResponse();        
        $this->getRequest()->setMethod( 'POST' );
        $this->getRequest()->setPost( array( 'username' => 'rfabian' , 'password' => 'a' ) );
        $this->dispatch( '/login/index' );
        $this->assertRedirectTo( '/index/' );
        
        $this->resetRequest();
        $this->resetResponse();  
        $this->dispatch( '/accounts/index' );
        $this->assertQueryContentContains( 'a', 'Change password change email recurrence' );
        
        $this->resetRequest();
        $this->resetResponse();
        $this->dispatch( '/logout' );
        $this->assertRedirectTo( '/' );
        
    }

}
