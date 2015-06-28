<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Users' );

class AccountsController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$MEMBER_USER , array( 'index' ) );
        
        $this->_helper->_aclHelper->addResource( 'users' );
        $this->_helper->_aclHelper->allowAll( 'users' , SiteConstants::$ADMIN_USER , 'editmyprivilege' );
        
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$ACCOUNT_MENUITEM );
        
        $this->user = new Users();
    }

    public function indexAction()
    {
        $user = $this->_helper->_aclHelper->getCurrentUser();
        $roleId = $user->sysRoleId;
        $personalData = $this->user->getPersonalData( $user->sysUserId );
        $accountData = $this->user->getAccountData( $user->id );
        $roleData = $this->user->getRoleData( $user->sysRoleId );

        $this->view->links = $this->_helper->_linksHelper->getLinks( $roleId , $this->view );

        $this->view->user = $personalData;
        $this->view->user_acc = $accountData;
        $this->view->user_role = $roleData;

        $this->view->canEditOwnPrivilege = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'users' , 'editmyprivilege' );
    }

}
