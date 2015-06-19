<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Users' );
Zend_Loader::loadClass( 'Errors' );
Zend_Loader::loadClass( 'Emails' );

class UsersController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$SUPERUSER , array( 'delete' , 'editpersonal' , 'editaccount' , 'editmyprivilege' , 'editprivilege' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$ADMIN , array( 'new' , 'add' , 'registered' , 'approve', 'deny' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$COUNCILOR , array( 'view' , 'addsignature' , 'changepass' , 'edit' , 'editmyaccount' , 'editmypersonal' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'register' , 'registeruser' , 'registercouncilor' ) );

        $this->_helper->_aclHelper->allow( SiteConstants::$COUNCILOR , array( 'pwcemails' ) );
        $this->_helper->_aclHelper->deny( SiteConstants::$VOTER , array( 'pwcemails' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$SUPERUSER , array( 'pwcemails' ) );

        $this->oUsers = new Users();
        $this->oEmails = new Emails();

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$ACCOUNT_MENUITEM );

        $ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
        $ajaxContext->addActionContext( 'delete' , 'json' );
        $ajaxContext->addActionContext( 'approve' , 'json' );
        $ajaxContext->addActionContext( 'deny' , 'json' );
        $ajaxContext->initContext();
    }

    public function newAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];

        $bAddAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'users' , 'add' );
        $bDeleteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'users' , 'delete' );
        $bApproveAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'users' , 'approve' );
        $bDenyAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'users' , 'deny' );

        $aUsers = $this->oUsers->getNewUsers();

        $this->view->users = $this->oUsers->toArray( $aUsers , false , $bDeleteAllowed , $bApproveAllowed, $bDenyAllowed );

        $this->view->add = $bAddAllowed;
    }

    public function registeredAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];

        $bAddAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'users' , 'add' );
        $bDeleteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'users' , 'delete' );
        $bApproveAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'users' , 'approve' );

        $excludedRoles = array();
        if ( SiteConstants::$SUPERUSER_ID == ( int ) $sRoleId )
        {
            $excludedRoles = array( SiteConstants::$SUPERADMIN_ID );
        }
        else if ( SiteConstants::$ADMIN_ID == ( int ) $sRoleId )
        {
            $excludedRoles = array( SiteConstants::$SUPERADMIN_ID ,
                    SiteConstants::$SUPERUSER_ID ,
                    SiteConstants::$COUNCILOR_ID );
        }

        $aUsers = $this->oUsers->getRegisteredUsersExcept( $excludedRoles );

        $this->view->users = $this->oUsers->toArray( $aUsers , false , $bDeleteAllowed , $bApproveAllowed );

        $this->view->add = $bAddAllowed;
    }

    public function approveAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $oFromUser = $this->oUsers->getPersonalData( $aUserData[ 'sys_user_id' ] );

        $iUserId = ( int ) $this->getRequest()->getPost( 'id' );
        $iApprovalId = $this->oUsers->updateStatusData( $iUserId , SiteConstants::$REG );
        if ( $iApprovalId )
        {
            $oToUser = $this->oUsers->getPersonalData( $iUserId );
            if ( $this->oEmails->userApproved( $oFromUser , $oToUser ) )
            {
                $this->view->result = true;
                $this->view->id = $iUserId;
            }
            else
            {
                $this->view->result = false;
                $this->view->id = $iUserId;
                $this->view->message = "<h4>Approval success but failed to send notification email.</h4>";
            }
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $iUserId;
            $this->view->message = "<h4>Cannot approve user. There are some problems in the database.</h4>";
        }
    }

    public function denyAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $userData = $this->_helper->_aclHelper->getCurrentUserData();
        $sender = $this->oUsers->getPersonalData( $userData[ 'sys_user_id' ] );

        $userId = ( int ) $this->getRequest()->getPost( 'id' );
        $reciepient = $this->oUsers->getPersonalData( $userId );
        $deleted = $this->oUsers->delete( $userId );
        if ( $deleted )
        {
            if ( $this->oEmails->userDenied( $sender , $reciepient ) )
            {
                $this->view->result = true;
                $this->view->id = $userId;
            }
            else
            {
                $this->view->result = false;
                $this->view->id = $userId;
                $this->view->message = "<h4>Approval success but failed to send notification email.</h4>";
            }
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $userId;
            $this->view->message = "<h4>Cannot approve user. There are some problems in the database.</h4>";
        }
    }

    public function pushError( $container , $validator , $data , $message )
    {
        $aError = $this->_helper->_validator->errorMessage( $validator , $data , $message );
        if ( $aError != '' )
        {
            $container->addError( $aError );
        }
    }

    public function getErrorMessagesBeforeAdding( $oPost )
    {
        $sLastname = $oPost[ 'user-lastname' ];
        $sFirstname = $oPost[ 'user-firstname' ];
        $sAddress = $oPost[ 'user-address' ];
        $sContact = $oPost[ 'user-contact' ];
        $sEmail = $oPost[ 'user-email' ];
        $sUsername = $oPost[ 'user-username' ];
        $sPassword = $oPost[ 'user-password' ];

        $aErrorMessages = new Errors();
        $this->pushError( $aErrorMessages , 'notempty' , $sLastname , "'Lastname' cannot be empty." );
        $this->pushError( $aErrorMessages , 'notempty' , $sFirstname , "'Firstname' cannot be empty." );
        $this->pushError( $aErrorMessages , 'notempty' , $sAddress , "'Address' cannot be empty." );
        $this->pushError( $aErrorMessages , 'notempty' , $sContact , "'Contact Numbers' cannot be empty." );
        $this->pushError( $aErrorMessages , 'notempty' , $sEmail , "'Email Address' cannot be empty." );
        $this->pushError( $aErrorMessages , 'notempty' , $sUsername , "'Username' cannot be empty." );
        $this->pushError( $aErrorMessages , 'notempty' , $sPassword , "'Password' cannot be empty." );
        return $aErrorMessages->getErrors();
    }

    public function addAction()
    {
        $sMessage = "";
        $oForm = new Form_UserAdd( '/users/add/' );

        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $iUserId = $this->oUsers->addNewUser( $this->getRequest()->getPost() );
                if ( $iUserId )
                {
                    $sMessage = "Registration has been sent. We will send you an email once we have approved your registration.";
                }
                else
                {
                    $oForm->populate( $this->_request->getPost() );
                    $sMessage = 'Submission Failed. See errors below.';
                }
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function editpersonalAction()
    {
        $sMessage = "";
        $oForm = new Form_UserEditPersonal( '/users/editpersonal/' );

        if ( $this->_request->isPost() )
        {
            $iUserId = ( int ) $this->_request->getPost( 'id' );
            $oForm->setData( array( 'user_id' => $iUserId ) );
            $oForm->init();

            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $this->oUsers->updatePersonalData( $iUserId , $this->_request->getPost() );
                $sMessage = "New information has been successfully updated.";
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->_request->isGet() )
        {
            $iUserId = ( int ) $this->_request->getParam( 'id' );
            $oForm->setData( array( 'user_id' => $iUserId ) );
            $oForm->init();

            $aPersonalData = $this->oUsers->getPersonalData( $iUserId )->toArray();
            $this->view->title = SiteConstants::createName( $aPersonalData[ 'firstname' ] , $aPersonalData[ 'lastname' ] , $aPersonalData[ 'mi' ] , $aPersonalData[ 'designation' ] );
            $oForm->populate( $aPersonalData );
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function editmypersonalAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $iUserId = $aUserData[ 'sys_user_id' ];

        $sMessage = "";
        $oForm = new Form_UserEditPersonal( '/users/editmypersonal/' );
        $oForm->setData( array( 'user_id' => ( int ) $iUserId ) );
        $oForm->init();
        if ( $this->_request->isPost() )
        {
            $this->_request->setPost( 'id' , ( int ) $iUserId );
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $this->oUsers->updatePersonalData( $iUserId , $this->_request->getPost() );
                $sMessage = "New information has been successfully updated.";
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->_request->isGet() )
        {
            $aPersonalData = $this->oUsers->getPersonalData( $iUserId )->toArray();
            $this->view->title = SiteConstants::createName( $aPersonalData[ 'firstname' ] , $aPersonalData[ 'lastname' ] , $aPersonalData[ 'mi' ] , $aPersonalData[ 'designation' ] );
            $oForm->populate( $aPersonalData );
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function editaccountAction()
    {
        $sMessage = "";
        $oForm = new Form_UserEditAccount( '/users/editaccount/' );

        if ( $this->_request->isPost() )
        {
            $iUserRoleId = ( int ) $this->_request->getPost( 'id' );
            $oForm->setData( array( 'user_role_id' => $iUserRoleId ) );
            $oForm->init();

            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $this->oUsers->updateAccountData( $iUserRoleId , $this->_request->getPost() );
                $sMessage = "New information has been successfully updated.";
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->_request->isGet() )
        {
            $iUserId = ( int ) $this->_request->getParam( 'sys_user_id' );
            $iUserRoleId = ( int ) $this->_request->getParam( 'id' );
            $oForm->setData( array( 'user_role_id' => $iUserRoleId ) );
            $oForm->init();

            $aPersonalData = $this->oUsers->getPersonalData( $iUserId )->toArray();
            $aAccountData = $this->oUsers->getAccountData( $iUserRoleId )->toArray();

            $this->view->title = SiteConstants::createName( $aPersonalData[ 'firstname' ] , $aPersonalData[ 'lastname' ] , $aPersonalData[ 'mi' ] , $aPersonalData[ 'designation' ] );
            $oForm->populate( $aAccountData );
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function editmyaccountAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $iUserRoleId = $aUserData[ 'id' ];
        $iUserId = $aUserData[ 'sys_user_id' ];

        $sMessage = "";
        $oForm = new Form_UserEditAccount( '/users/editmyaccount/' );
        $oForm->setData( array( 'user_role_id' => $iUserRoleId ) );
        $oForm->init();

        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $this->oUsers->updateAccountData( $iUserId , $this->_request->getPost() );
                $sMessage = "New information has been successfully updated.";
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->_request->isGet() )
        {
            $aPersonalData = $this->oUsers->getPersonalData( $iUserId )->toArray();
            $aAccountData = $this->oUsers->getAccountData( $iUserRoleId )->toArray();

            $this->view->title = SiteConstants::createName( $aPersonalData[ 'firstname' ] , $aPersonalData[ 'lastname' ] , $aPersonalData[ 'mi' ] , $aPersonalData[ 'designation' ] );
            $oForm->populate( $aAccountData );
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function editprivilegeAction()
    {
        $sMessage = "";
        $oForm = new Form_UserEditPrivilege( '/users/editprivilege/' );

        if ( $this->_request->isPost() )
        {
            $iUserRoleId = ( int ) $this->_request->getPost( 'id' );
            $oForm->setData( array( 'user_role_id' => $iUserRoleId ) );
            $oForm->init();

            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $this->oUsers->updatePrivilege( $iUserRoleId , $this->_request->getPost() );
                $sMessage = "New information has been successfully updated. Privilege changes will reflect next time you logged in.";
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->_request->isGet() )
        {
            $iUserId = ( int ) $this->_request->getParam( 'sys_user_id' );
            $iUserRoleId = ( int ) $this->_request->getParam( 'id' );
            $oForm->setData( array( 'user_role_id' => $iUserRoleId ) );
            $oForm->init();

            $aPersonalData = $this->oUsers->getPersonalData( $iUserId )->toArray();
            $aAccountData = $this->oUsers->getAccountData( $iUserRoleId )->toArray();

            $this->view->title = SiteConstants::createName( $aPersonalData[ 'firstname' ] , $aPersonalData[ 'lastname' ] , $aPersonalData[ 'mi' ] , $aPersonalData[ 'designation' ] );
            $oForm->populate( $aAccountData );
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function editmyprivilegeAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $iUserRoleId = $aUserData[ 'id' ];
        $iUserId = $aUserData[ 'sys_user_id' ];

        $sMessage = "";
        $oForm = new Form_UserEditPrivilege( '/users/editmyprivilege/' );
        $oForm->setData( array( 'user_role_id' => $iUserRoleId ) );
        $oForm->init();

        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $this->oUsers->updatePrivilege( $iUserId , $this->_request->getPost() );
                $sMessage = "New information has been successfully updated. Privilege changes will reflect next time you logged in.";
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->_request->isGet() )
        {
            $aPersonalData = $this->oUsers->getPersonalData( $iUserId )->toArray();
            $aAccountData = $this->oUsers->getAccountData( $iUserRoleId )->toArray();

            $this->view->title = SiteConstants::createName( $aPersonalData[ 'firstname' ] , $aPersonalData[ 'lastname' ] , $aPersonalData[ 'mi' ] , $aPersonalData[ 'designation' ] );
            $oForm->populate( $aAccountData );
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function registerAction()
    {
        if ( $this->getRequest()->isGet() )
        {
            $sRole = $this->getRequest()->getParam( 'reg' );

            $oRequest = $this->getRequest();
            if ( $sRole == SiteConstants::$VOTER )
            {
                $oRequest->setModuleName( 'default' );
                $oRequest->setControllerName( 'users' );
                $oRequest->setActionName( 'registeruser' );
                $oRequest->setDispatched( false );
            }
            else if ( $sRole == SiteConstants::$COUNCILOR )
            {
                $oRequest->setModuleName( 'default' );
                $oRequest->setControllerName( 'users' );
                $oRequest->setActionName( 'registercouncilor' );
                $oRequest->setDispatched( false );
            }
        }
    }

    public function editAction()
    {
        if ( $this->getRequest()->isGet() )
        {
            $this->_helper->_aclHelper->allowAll( 'users' , SiteConstants::$COUNCILOR , 'editpersonal' );
            $this->_helper->_aclHelper->allowAll( 'users' , SiteConstants::$COUNCILOR , 'editaccount' );

            $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
            $sUserRoleId = $aUserData[ 'id' ];
            $sUserId = $aUserData[ 'sys_user_id' ];

            $sType = $this->getRequest()->getParam( 'type' );

            $oRequest = $this->getRequest();
            if ( $sType === 'personal' )
            {
                $oRequest->setModuleName( 'default' );
                $oRequest->setControllerName( 'users' );
                $oRequest->setActionName( 'editpersonal' );
                $oRequest->setParams( array( 'id' => $sUserId ) );
                $oRequest->setDispatched( false );
            }
            else
            {
                $oRequest->setModuleName( 'default' );
                $oRequest->setControllerName( 'users' );
                $oRequest->setActionName( 'editaccount' );
                $oRequest->setParams( array( 'id' => $sUserId , 'sys_user_id' => $sUserRoleId ) );
                $oRequest->setDispatched( false );
            }
        }
    }

    public function registeruserAction()
    {
        $sMessage = "";
        $oForm = new Form_UserRegisterUser( '/users/registeruser/' );

        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $iUserId = $this->oUsers->addNewUser( $this->getRequest()->getPost() );
                if ( $iUserId )
                {
                    $sMessage = "Registration has been sent. We will send you an email once we have approved your registration.";
                }
                else
                {
                    $oForm->populate( $this->_request->getPost() );
                    $sMessage = 'Submission Failed. See errors below.';
                }
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->getRequest()->isGet() )
        {
            $oForm->populate( $this->_request->getParams() );
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function registercouncilorAction()
    {
        $sMessage = "";
        $oForm = new Form_UserRegisterCouncilor( '/users/registercouncilor/' );

        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $iUserId = $this->oUsers->addNewUser( $this->getRequest()->getPost() );
                if ( $iUserId )
                {
                    $sMessage = "Registration has been sent. We will send you an email once we have approved your registration.";
                }
                else
                {
                    $oForm->populate( $this->_request->getPost() );
                    $sMessage = 'Submission Failed. See errors below.';
                }
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->getRequest()->isGet() )
        {
            $oForm->populate( $this->_request->getParams() );
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function viewAction()
    {
        if ( $this->getRequest()->isGet() )
        {
            $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
            $sRoleId = $aUserData[ 'sys_role_id' ];
            $this->view->canEditPersonal = $this->checkAllowed( $sRoleId , 'users' , 'editpersonal' );
            $this->view->canEditAccount = $this->checkAllowed( $sRoleId , 'users' , 'editaccount' );

            $iUserId = ( int ) $this->getRequest()->getParam( 'id' );
            $aPersonalData = $this->oUsers->getPersonalData( $iUserId );
            $aAccountData = $this->oUsers->getAccountDataFromUserId( $iUserId );
            $aRoleData = $this->oUsers->getRoleData( $aAccountData->sys_role_id );

            $this->view->title = SiteConstants::createName( $aPersonalData[ 'firstname' ] , $aPersonalData[ 'lastname' ] , $aPersonalData[ 'mi' ] , $aPersonalData[ 'designation' ] );
            $this->view->user = $aPersonalData;
            $this->view->user_acc = $aAccountData;
            $this->view->user_role = $aRoleData;
            $this->view->hassignature = false;
        }
    }

    public function addsignatureAction()
    {
        $this->view->title = "Add Signature";
        $aSubmissionMessage = '';
        $aErrorMessages = array();
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $oCurrentUser = $this->oUsers->getPersonalData( $aUserData[ 'sys_user_id' ] );
        if ( !empty( $_FILES ) )
        {
            $aUploadErrors = $this->oUsers->addSignature( $aUserData[ 'sys_user_id' ] , $_FILES );
            if ( empty( $aUploadErrors ) )
            {
                $aSubmissionMessage = "Signature has been successfully added to the database.";
                $oCurrentUser = $this->oUsers->getPersonalData( $aUserData[ 'sys_user_id' ] );
            }
            else
            {
                $aSubmissionMessage = "Uploading files failed. See errors below.";
                $aErrorMessages = array_merge( $aErrorMessages , $aUploadErrors );
                $this->view->post = $this->getRequest()->getPost();
            }
        }
        else
        {
            if ( is_null( $oCurrentUser->signature ) || empty( $oCurrentUser->signature ) )
            {
                $aSubmissionMessage = "No files uploaded.";
            }
        }

        $this->view->user = $oCurrentUser;
        $this->view->formmessage = $aSubmissionMessage;
        $this->view->formresponse = $aErrorMessages;
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $iUserId = ( int ) $this->getRequest()->getPost( 'id' );
        $this->view->list = "users";
        if ( $this->oUsers->delete( $iUserId ) )
        {
            $this->view->result = true;
            $this->view->id = $iUserId;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $iUserId;
            $this->view->message = "Cannot delete data.";
        }
    }

    public function pwcemailsAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sUserId = $aUserData[ 'sys_user_id' ];

        $sMessage = "";
        $oForm = new Form_PasswordChangeEmail( '/users/pwcemails/' );

        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $sPwc = $this->getRequest()->getPost( 'pwc' );

                $iUserId = $this->oUsers->updatePasswordChangeEmailRecurrence( $sUserId , $sPwc );
                if ( $iUserId )
                {
                    $sMessage = "Details has been successfully updated!";
                }
                else
                {
                    $oForm->populate( $this->_request->getPost() );
                    $sMessage = 'No details has been updated. Current combobox value is already the latest.';
                }
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function changepassAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sUserRoleId = $aUserData[ 'id' ];

        $sMessage = "";
        $oForm = new Form_PasswordChange( '/users/changepass/' );
        $oForm->setData( array( 'user_role_id' => $sUserRoleId ) );
        $oForm->init();

        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $sNewPassword = $this->_request->getPost( 'password' );
                $iUserId = $this->oUsers->updatePasswordData( $sUserRoleId , $sNewPassword );
                if ( $iUserId )
                {
                    $sMessage = "Details has been successfully updated!";
                }
                else
                {
                    $oForm->populate( $this->_request->getPost() );
                    $sMessage = 'No details has been updated. Password has not changed.';
                }
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    private function checkAllowed( $sRoleId , $controller , $action )
    {
        $bEditAllowed = false;

        if ( $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , $controller , $action ) )
        {
            $bEditAllowed = true;
        }
        return $bEditAllowed;
    }

}
