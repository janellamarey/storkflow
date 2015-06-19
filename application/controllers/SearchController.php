<?php

Zend_Loader::loadClass( 'Ordinances' );
Zend_Loader::loadClass( 'Polls' );

class SearchController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'index' ) );

        $this->_helper->_aclHelper->addResource( 'polls' );
        $this->_helper->_aclHelper->allowAll( 'polls' , SiteConstants::$COUNCILOR , 'vote' );

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$HOME_MENUITEM );

        $this->oPolls = new Polls();
        $this->oOrdinances = new Ordinances();
    }

    public function indexAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];
        $sUserId = $aUserData[ 'sys_user_id' ];

        $this->view->title = 'Search Results';
        $filters = array( 'q' => array( 'StringTrim' , 'StripTags' ) );
        $validators = array( 'q' => array( 'presence' => 'required' ) );

        $input = new Zend_Filter_Input( $filters , $validators , $_GET );

        if ( $input->isValid() )
        {
            $this->view->messages = '';
            $q = $input->getEscaped( 'q' );
            $this->view->q = $q;

            $index = Custom_Search_Lucene::open( SearchIndexer::getIndexDirectory() );
            $results = $index->find( $q );
            $this->view->results = $results;
        }
        else
        {
            $this->view->messages = $input->getMessages();
        }

        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinances = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
        $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'polls' , 'vote' );
        $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $sUserId );
        $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
        $this->view->poll = $aFeaturedPoll;
    }
    
}
