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
        $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'index' ) );

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
        $sUserId = $aUserData[ 'sys_user_id' ];
        $this->view->is_logged = $sRoleId === SiteConstants::$GUESTROLE_ID ? false : true;

        $sLatestNews = $this->oNews->getLatestBacoorNews();

        if ( !empty( $sLatestNews ) )
        {
            $this->view->images = $this->oNews->getImages( $sLatestNews[ 0 ][ 'post_id' ] , true , 3 );
        }
        else
        {
            $this->view->images = array();
        }
        
        $this->view->bacoornews = $sLatestNews;
        $this->view->localnews = array_merge( $this->oNews->getGMANews( 2 ) );
        $this->view->foreignnews = array_merge( $this->oNews->getCNNNews( 2 ) );

        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinances = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED', 'ORDINANCE|RESOLUTION' );
        $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'polls' , 'vote' );
        $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $sUserId );
        $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
        $this->view->poll = $aFeaturedPoll;
    }

}
