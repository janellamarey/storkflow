<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Users' );
Zend_Loader::loadClass( 'Emails' );

class UsersController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$ACCOUNT_MENUITEM );

        $this->_helper->_aclHelper->allow( SiteConstants::$ADMIN_USER , array( 'add' , 'registered' , 'delete' , 'view' , 'editmyprivilege' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$MEMBER_USER , array( 'changepass' , 'editmypersonal' , 'editmyaccount' ) );


        $ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
        $ajaxContext->addActionContext( 'delete' , 'json' );

        $ajaxContext->initContext();

        $this->users = new Users();
        $this->emails = new Emails();
    }

    public function addAction()
    {
        $roleId = ( int ) $this->_helper->_aclHelper->getCurrentUserRole();
        $message = "";
        $form = new Form_UserAdd( '/users/add/' );

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $userId = $this->users->addNewUser( $this->getRequest()->getPost() );
                if ( $userId )
                {
                    $message = "User has been added successfully.";
                }
                else
                {
                    $form->populate( $this->getRequest()->getPost() );
                    $message = 'Submission Failed. See errors below.';
                }
            }
            else
            {
                $form->populate( $this->getRequest()->getPost() );
                $message = 'Submission Failed. See errors below.';
            }
        }


        $this->view->links = $this->_helper->_linksHelper->getLinks( $roleId , $this->view );

        $this->view->form = $form;
        $this->view->message = $message;
    }

    public function registeredAction()
    {
        $roleId = ( int ) $this->_helper->_aclHelper->getCurrentUserRole();
        $isAddAllowed = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'users' , 'add' );
        $isDeleteAllowed = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'users' , 'delete' );
        $isApprovedAllowed = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'users' , 'approve' );

        $registeredUsers = $this->users->getRegisteredUsersExcept( array() );

        $this->view->links = $this->_helper->_linksHelper->getLinks( $roleId , $this->view );

        $this->view->users = $this->users->toArray( $registeredUsers , false , $isDeleteAllowed , $isApprovedAllowed );
        $this->view->add = $isAddAllowed;
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $sysId = ( int ) $this->getRequest()->getPost( 'id' );
        $this->view->list = "users";
        if ( $this->users->delete( $sysId ) )
        {
            $this->view->result = true;
            $this->view->id = $sysId;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $sysId;
            $this->view->message = "Cannot delete data.";
        }
    }

    public function viewAction()
    {
        if ( $this->getRequest()->isGet() )
        {
            $roleId = $this->_helper->_aclHelper->getCurrentUserRole();
            $this->view->canEditPersonal = $this->checkAllowed( $roleId , 'users' , 'editpersonal' );
            $this->view->canEditAccount = $this->checkAllowed( $roleId , 'users' , 'editaccount' );

            $userRoleId = ( int ) $this->getRequest()->getParam( 'id' );
            $accountData = $this->users->getAccountData( $userRoleId );
            $personalData = $this->users->getPersonalData( $this->users->getUserId( $userRoleId ) );
            $roleData = $this->users->getRoleData( $accountData->sys_role_id );

            $this->view->links = $this->_helper->_linksHelper->getLinks( $roleId , $this->view );

            $this->view->title = SiteConstants::createName( $personalData[ 'firstname' ] , $personalData[ 'lastname' ] , $personalData[ 'mi' ] , $personalData[ 'designation' ] );
            $this->view->user = $personalData;
            $this->view->user_acc = $accountData;
            $this->view->user_role = $roleData;
        }
    }

    public function changepassAction()
    {
        $roleId = $this->_helper->_aclHelper->getCurrentUserRole();

        $message = "";
        $form = new Form_PasswordChange( '/users/changepass/' );
        $form->setData( array( 'user_role_id' => $roleId ) );
        $form->init();

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $newPassword = $this->getRequest()->getPost( 'password' );
                $userId = $this->users->updatePasswordData( $roleId , $newPassword );
                if ( $userId )
                {
                    $message = "Details has been successfully updated!";
                }
                else
                {
                    $form->populate( $this->getRequest()->getPost() );
                    $message = 'No details has been updated. Password has not changed.';
                }
            }
            else
            {
                $form->populate( $this->getRequest()->getPost() );
                $message = 'Submission Failed. See errors below.';
            }
        }

        $this->view->links = $this->_helper->_linksHelper->getLinks( $roleId , $this->view );

        $this->view->form = $form;
        $this->view->message = $message;
    }

    public function editmypersonalAction()
    {
        $user = $this->_helper->_aclHelper->getCurrentUser();
        $userId = $user->sysUserId;

        $message = "";
        $form = new Form_UserEditPersonal( '/users/editmypersonal/' );
        $form->setData( array( 'user_id' => ( int ) $userId ) );
        $form->init();
        if ( $this->getRequest()->isPost() )
        {
            $this->getRequest()->setPost( 'id' , ( int ) $userId );
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $this->users->updatePersonalData( $userId , $this->getRequest()->getPost() );
                $message = "New information has been successfully updated.";
            }
            else
            {
                $form->populate( $this->getRequest()->getPost() );
                $message = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->getRequest()->isGet() )
        {
            $personalData = $this->users->getPersonalData( $userId )->toArray();
            $this->view->title = SiteConstants::createName( $personalData[ 'firstname' ] , $personalData[ 'lastname' ] , $personalData[ 'mi' ] , $personalData[ 'designation' ] );
            $form->populate( $personalData );
        }

        $this->view->links = $this->_helper->_linksHelper->getLinks( $user->sysRoleId , $this->view );

        $this->view->form = $form;
        $this->view->message = $message;
    }

    public function editmyaccountAction()
    {
        $user = $this->_helper->_aclHelper->getCurrentUser();
        $userRoleId = $user->id;
        $userId = $user->sysUserId;

        $message = "";
        $form = new Form_UserEditAccount( '/users/editmyaccount/' );
        $form->setData( array( 'user_role_id' => $userRoleId ) );
        $form->init();

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $this->users->updateAccountData( $userId , $this->getRequest()->getPost() );
                $message = "New information has been successfully updated.";
            }
            else
            {
                $form->populate( $this->getRequest()->getPost() );
                $message = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->getRequest()->isGet() )
        {
            $personalData = $this->users->getPersonalData( $userId )->toArray();
            $accountData = $this->users->getAccountData( $userRoleId )->toArray();

            $this->view->title = SiteConstants::createName( $personalData[ 'firstname' ] , $personalData[ 'lastname' ] , $personalData[ 'mi' ] , $personalData[ 'designation' ] );
            $form->populate( $accountData );
        }

        $this->view->links = $this->_helper->_linksHelper->getLinks( $user->sysRoleId , $this->view );

        $this->view->form = $form;
        $this->view->message = $message;
    }

    public function editmyprivilegeAction()
    {
        $user = $this->_helper->_aclHelper->getCurrentUser();
        $userRoleId = $user->id;
        $userId = $user->sysUserId;

        $message = "";
        $form = new Form_UserEditPrivilege( '/users/editmyprivilege/' );
        $form->setData( array( 'user_role_id' => $userRoleId ) );
        $form->init();

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $this->users->updatePrivilege( $userId , $this->getRequest()->getPost() );
                $message = "New information has been successfully updated. Privilege changes will reflect next time you logged in.";
            }
            else
            {
                $form->populate( $this->getRequest()->getPost() );
                $message = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->getRequest()->isGet() )
        {
            $personalData = $this->users->getPersonalData( $userId )->toArray();
            $accountData = $this->users->getAccountData( $userRoleId )->toArray();

            $this->view->title = SiteConstants::createName( $personalData[ 'firstname' ] , $personalData[ 'lastname' ] , $personalData[ 'mi' ] , $personalData[ 'designation' ] );
            $form->populate( $accountData );
        }

        $this->view->links = $this->_helper->_linksHelper->getLinks( $user->sysRoleId , $this->view );

        $this->view->form = $form;
        $this->view->message = $message;
    }

    private function checkAllowed( $roleId , $controller , $action )
    {
        $bEditAllowed = false;

        if ( $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , $controller , $action ) )
        {
            $bEditAllowed = true;
        }
        return $bEditAllowed;
    }

}
