<?php
Zend_Loader::loadClass('SiteConstants');
Zend_Loader::loadClass('AboutUs');
Zend_Loader::loadClass('ContactUs');
Zend_Loader::loadClass('News');
class ArticlesController extends Zend_Controller_Action
{    
    function init()
    {
        $this->_helper->_aclHelper->allow(SiteConstants::$ADMIN, array('index')); 
        $this->oAboutUs = new AboutUs();
        $this->oContactUs = new ContactUs();
        $this->oNews = new News();
        
        $this->_helper->_menuHelper->setMenuItemName(SiteConstants::$ACCOUNT_MENUITEM); 
    }
    
    public function indexAction()
    {
        $this->view->about_us_url_edit = '/about/edit';       
        $this->view->about_us_content = $this->oAboutUs->getContent();
        
        $this->view->contact_us_url_edit = '/contact/edit';
        $this->view->contact_us_content = $this->oContactUs->getContent();
        
        $this->view->news_url_add = '/news/add';
        $this->view->news_content = $this->oNews->getBacoorNews();
        
        $this->view->map_url = '/map/upload';
        $this->view->map_url_view = '/map/view';
    }
    
}

