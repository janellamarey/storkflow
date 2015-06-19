<?php

class LogoutController extends Zend_Controller_Action
{    
    function init()
    {
        Zend_Loader::loadClass('SiteConstants');        
        $this->oUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $this->_helper->_aclHelper->allow('role'.$this->oUserData['sys_role_id'], array('index'));
    }

    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_redirect('/');        
    }

}

