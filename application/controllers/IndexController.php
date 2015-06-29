<?php

Zend_Loader::loadClass( 'SiteConstants' );

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->user = $this->_helper->_aclHelper->getCurrentUser();
        $roleId = $this->user->sysRoleId;

        if ( $roleId === SiteConstants::$GUEST_ID )
        {
            $this->_helper->_aclHelper->deny( SiteConstants::$GUEST_USER , array( 'index' ) );
        }
        else
        {
            $this->_helper->_aclHelper->allow( SiteConstants::$GUEST_USER , array( 'index' ) );
            $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$ACCOUNT_MENUITEM );
        }
    }

    public function indexAction()
    {
        $this->view->is_logged = $this->user->sysRoleId === SiteConstants::$GUEST_ID ? false : true;
    }

}
