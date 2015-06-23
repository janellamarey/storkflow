<?php

Zend_Loader::loadClass( 'SiteConstants' );

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];

        if ( $sRoleId === SiteConstants::$GUEST_ID )
        {
            $this->_helper->_aclHelper->deny( SiteConstants::$GUEST_USER , array( 'index' ) );
        }
        else
        {
            $this->_helper->_aclHelper->allow( SiteConstants::$GUEST_USER , array( 'index' ) );
        }

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$HOME_MENUITEM );
    }

    public function indexAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];
        $this->view->is_logged = $sRoleId === SiteConstants::$GUEST_ID ? false : true;
    }

}
