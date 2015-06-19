<?php

class SurveysController extends Zend_Controller_Action
{    
    function init()
    {
        Zend_Loader::loadClass('SiteConstants');
        $this->_helper->_aclHelper->allow(SiteConstants::$GUESTROLE, array('index'));               
    }
    
    public function indexAction()
    {
        
    }
    
}

