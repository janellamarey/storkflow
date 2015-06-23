<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Users' );
Zend_Loader::loadClass( 'Emails' );

class LoginController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$GUEST_USER , array( 'index' , 'forgotpass' ) );

        $this->_auth = Zend_Auth::getInstance();

        if ( $this->_auth->hasIdentity() )
        {
            $this->oUserData = $this->_helper->_aclHelper->getCurrentUserData();
            $this->_helper->_aclHelper->deny( 'role' . $this->oUserData[ 'sys_role_id' ] , array( 'index' ) );
        }

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$HOME_MENUITEM );
    }

    public function indexAction()
    {
        $form = new Form_Login( '/login/index/' );

        if ( $this->_request->isPost() )
        {
            if ( $form->isValid( $this->_request->getPost() ) )
            {
                $authAdapter = $form->username->getValidator( 'Authorise' )->getAuthAdapter();
                $data = $authAdapter->getResultRowObject( null , 'password' );
                $auth = Zend_Auth::getInstance();
                $auth->getStorage()->write( $data );
                $this->_redirect( 'index/' );
            }
            else
            {
                $auth = Zend_Auth::getInstance();
                $auth->clearIdentity();
                $form->populate( $this->_request->getPost() );
            }
        }
        $this->view->form = $form;
    }

    public function forgotpassAction()
    {
        $oForm = new Form_ForgotPass( '/login/forgotpass/' );
        $bSubmitted = false;
        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $oUser = new Users();
                $oPersonalData = $oUser->getPersonalDataFromEmail( $this->_request->getPost( 'email_add' ) );
                $oAccountData = $oUser->getAccountDataFromUserId( $oPersonalData->id );

                //generate random password
                $sNewPassword = substr( md5( rand() ) , 0 , 7 );
                $oUser->updatePasswordData( $oAccountData->id , $sNewPassword );

                //send email for reset
                $oEmail = new Emails();
                $oEmail->mailReset( $oPersonalData['email_add'] , $oAccountData['username'] , $sNewPassword );
                
                $bSubmitted = true;
            }
            else
            {
                $auth = Zend_Auth::getInstance();
                $auth->clearIdentity();
                $oForm->populate( $this->_request->getPost() );
            }
        }

        if ( !$bSubmitted )
        {
            $this->view->form = $oForm;
        }
        else
        {
            $this->view->form = "<h1>You have successfully resetted your password. Please check your email.</h1>";
        }
    }

}
