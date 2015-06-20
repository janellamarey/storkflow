<?php
Zend_Loader::loadClass('SiteConstants');
class Custom_Controller_Action_Helper_MenuHelper extends Zend_Controller_Action_Helper_Abstract
{          
    public function getMenu($sRole, $oView)
    {
        $menu = array();
        
        $sExtraMenu = array();
        $sRole = 'role'.$sRole;
        if ($sRole == SiteConstants::$SUPERADMIN)
        {
            $sExtraMenu = array('title'=>'ACCOUNT',
                          'url'=>$oView->url(array('controller'=>'accounts','action'=>''), null, true)
                         );
        }
        else if($sRole == SiteConstants::$SUPERUSER)
        {
            $sExtraMenu = array('title'=>'ACCOUNT',
                          'url'=>$oView->url(array('controller'=>'accounts','action'=>''), null, true)
                         );
        }
        else if($sRole == SiteConstants::$ADMIN)
        {
            $sExtraMenu = array('title'=>'ACCOUNT',
                          'url'=>$oView->url(array('controller'=>'accounts','action'=>''), null, true)
                         );
        }
        else if($sRole == SiteConstants::$VOTER)
        {
            $sExtraMenu = array('title'=>'ACCOUNT',
                          'url'=>$oView->url(array('controller'=>'accounts','action'=>''), null, true)
                         );
        }
        else if($sRole == SiteConstants::$COUNCILOR)
        {
            $sExtraMenu = array('title'=>'ACCOUNT',
                          'url'=>$oView->url(array('controller'=>'accounts','action'=>''), null, true)
                         );
        }
        
        
        return $menu; 
    }
    
    /**
     * Set the active menu item based name of menu item
     * @param boolean $aMenu
     * @param type $sUrlToSearch
     * @return boolean
     */
    public function setActiveMenuItem($menu)
    {        
        if(!empty($menu) && !is_null($menu[$this->menuItemName]) && !empty($menu[$this->menuItemName]))
        {
            $menu[$this->menuItemName]['active'] = true;
        }
        return $menu;
    }
    
    public function setMenuItemName($name)
    {
        $this->menuItemName = $name;
    }
    
    public function setMenuItemAction($action)
    {
        $this->menuItemAction = $action;
    }
}