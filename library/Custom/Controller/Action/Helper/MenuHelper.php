<?php
Zend_Loader::loadClass('SiteConstants');
class Custom_Controller_Action_Helper_MenuHelper extends Zend_Controller_Action_Helper_Abstract
{          
    public function getMenu($sRole, $oView)
    {
        $aMenu = array(
            'HOME' => array('title'=>'HOME','url'=>$oView->url(array('controller'=>'index','action'=>'index'))),
            'ABOUT US' => array('title'=>'ABOUT US','url'=>$oView->url(array('controller'=>'about','action'=>''), null, true)),
            'NEWS & EVENTS' => array('title'=>'NEWS & EVENTS','url'=>$oView->url(array('controller'=>'news','action'=>''), null, true)),
            'DOWNLOADS' => array(),
            'GEOHAZARD MAP' => array('title'=>'GEOHAZARD MAP','url'=>$oView->url(array('controller'=>'map','action'=>''), null, true)),
            'PENDING LEGISLATION' => array('title'=>'PENDING LEGISLATION','url'=>$oView->url(array('controller'=>'legislation','action'=>''), null, true)),
            'PEOPLES PAGE' => array('title'=>"PEOPLE'S PAGE",'url'=>$oView->url(array('controller'=>'polls','action'=>'published'), null, true)),
            'CONTACT US' => array('title'=>'CONTACT US','url'=>$oView->url(array('controller'=>'contact','action'=>''), null, true))            
        );
        
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
        
        $aMenu['ACCOUNT'] = $sExtraMenu;
        
        $aMenu['DOWNLOADS'] = array('title'=>'DOWNLOADS',
                          'url'=>'',
                          'submenu'=> array(
                                        array('title'=>'ORDINANCES','url'=>$oView->url(array('controller'=>'downloads','action'=>'ordinances'), null, true)),
                                        array('title'=>'RESOLUTIONS','url'=>$oView->url(array('controller'=>'downloads','action'=>'resolutions'), null, true)),
                                        array('title'=>'BUDGETS','url'=>$oView->url(array('controller'=>'downloads','action'=>'budgets'), null, true)),
                                        array('title'=>'PROCUREMENTS','url'=>$oView->url(array('controller'=>'downloads','action'=>'procurements'), null, true))
                                   )
                         );
        return $aMenu; 
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
        else
        {
            $menu['HOME']['active'] = true;
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