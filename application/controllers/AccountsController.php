<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Users' );

class AccountsController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$COUNCILOR , array( 'index' ) );

        $this->_helper->_aclHelper->addResource( 'users' );
        $this->_helper->_aclHelper->allowAll( 'users' , SiteConstants::$SUPERUSER , 'editmyprivilege' );

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$ACCOUNT_MENUITEM );

        $this->oUser = new Users();
    }

    public function indexAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];
        $aPersonalData = $this->oUser->getPersonalData( $aUserData[ 'sys_user_id' ] );
        $aAccountData = $this->oUser->getAccountData( $aUserData[ 'id' ] );
        $aRoleData = $this->oUser->getRoleData( $aUserData[ 'sys_role_id' ] );

        $this->view->title = "Dashboard";
        $this->view->links = $this->_helper->_linksHelper->getLinks( $sRoleId , $this->view );

        $this->view->user = $aPersonalData;
        $this->view->user_acc = $aAccountData;
        $this->view->user_role = $aRoleData;

        //flags 
        $this->view->hassignature = $this->checkSignature( $aUserData , $aPersonalData );
        $this->view->canEditOwnPrivilege = $this->checkEditPrivilegeAllowed( $sRoleId );
    }

    private function checkSignature( $aUserData , $aPersonalData )
    {
        return ('role' . $aUserData[ 'sys_role_id' ] === SiteConstants:: $COUNCILOR || 'role' . $aUserData[ 'sys_role_id' ] === SiteConstants:: $SUPERADMIN || 'role' . $aUserData[ 'sys_role_id' ] === SiteConstants:: $SUPERUSER) && !is_null( $aPersonalData );
    }

    private function checkEditPrivilegeAllowed( $sRoleId )
    {
        $bAddAllowed = false;

        if ( $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'users' , 'editmyprivilege' ) )
        {
            $bAddAllowed = true;
        }
        return $bAddAllowed;
    }

}
