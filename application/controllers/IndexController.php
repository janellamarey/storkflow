<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'News' );
Zend_Loader::loadClass( 'Ordinances' );
Zend_Loader::loadClass( 'Polls' );
Zend_Loader::loadClass( 'Images' );

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];

        if ( $sRoleId === SiteConstants::$GUESTROLE_ID )
        {
            $this->_helper->_aclHelper->deny( SiteConstants::$GUESTROLE , array( 'index' ) );
        }
        else
        {
            $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'index' ) );
        }

        $this->_helper->_aclHelper->addResource( 'polls' );
        $this->_helper->_aclHelper->allowAll( 'polls' , SiteConstants::$COUNCILOR , 'vote' );

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$HOME_MENUITEM );

        $this->oNews = new News();
        $this->oPolls = new Polls();
        $this->oOrdinances = new Ordinances();
        $this->oImages = new Images();
    }

    public function indexAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];
        $this->view->is_logged = $sRoleId === SiteConstants::$GUESTROLE_ID ? false : true;
    }

}
