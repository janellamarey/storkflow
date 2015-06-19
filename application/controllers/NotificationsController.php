<?php

class NotificationsController extends Zend_Controller_Action
{    
    function init()
    {
        Zend_Loader::loadClass('SiteConstants');
        $this->_helper->_aclHelper->allow(SiteConstants::$SUPERUSER, array('index'));               
    }
    
    public function indexAction()
    {
    }
    
}

