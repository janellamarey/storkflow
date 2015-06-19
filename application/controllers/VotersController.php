<?php

class VotersController extends Zend_Controller_Action
{    
    function init()
    {
        Zend_Loader::loadClass('SiteConstants');
        $this->_helper->_aclHelper->allow(SiteConstants::$ADMIN, array('index'));
        
        $this->_helper->_menuHelper->setMenuItemName(SiteConstants::$ACCOUNT_MENUITEM);
    }
    
    public function indexAction()
    {
    }    
}

