<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Sql' );
Zend_Loader::loadClass( 'Custom_Search_Lucene' );
Zend_Loader::loadClass( 'SearchIndexer' );

class DevController extends Zend_Controller_Action
{
    function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$SUPERADMIN , array( 'index' , 'resetdb' , 'reindex' ) );

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$ACCOUNT_MENUITEM );

        $ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
        $ajaxContext->addActionContext( 'resetdb' , 'json' );
        $ajaxContext->addActionContext( 'reindex' , 'json' );
        $ajaxContext->initContext();
    }

    public function indexAction()
    {
        $this->title = "Developer tools";
        $this->view->buttons = $this->getButtons();
    }

    public function getButtons()
    {
        return $aLinks = array(
                array( 'id' => 'resetdb' , 'title' => 'Reset database tables' , 'url' => $this->view->url( array( 'controller' => 'dev' , 'action' => 'resetdb' ) , null , true ) ) ,
                array( 'id' => 'reindex' , 'title' => 'Reindex search entries' , 'url' => $this->view->url( array( 'controller' => 'dev' , 'action' => 'reindex' ) , null , true ) )
        );
    }

    public function resetdbAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$DROP_EMAIL_QUEUE_TABLE );
        $db->query( Sql::$CREATE_EMAIL_QUEUE_TABLE );
        $db->query( Sql::$DROP_MAPS_TABLE );
        $db->query( Sql::$CREATE_MAPS_TABLE );
        $db->query( Sql::$DROP_ORDINANCES_TABLE );
        $db->query( Sql::$CREATE_ORDINANCES_TABLE );
        $db->query( Sql::$DROP_ORDINANCES_USERS_TABLE );
        $db->query( Sql::$CREATE_ORDINANCES_USERS_TABLE );
        $db->query( Sql::$DROP_POSTS_TABLE );
        $db->query( Sql::$CREATE_POSTS_TABLE );
        $db->query( Sql::$DROP_POSTS_POST_TYPES_TABLE );
        $db->query( Sql::$CREATE_POSTS_POST_TYPES_TABLE );
        $db->query( Sql::$DROP_POST_IMAGES_TABLE );
        $db->query( Sql::$CREATE_POST_IMAGES_TABLE );
        $db->query( Sql::$DROP_POST_TYPES_TABLE );
        $db->query( Sql::$CREATE_POST_TYPES_TABLE );
        $db->query( Sql::$DROP_SURVEYS_TABLE );
        $db->query( Sql::$CREATE_SURVEYS_TABLE );
        $db->query( Sql::$DROP_SURVEY_OPTIONS_TABLE );
        $db->query( Sql::$CREATE_SURVEY_OPTIONS_TABLE );
        $db->query( Sql::$DROP_SURVEY_USERS_TABLE );
        $db->query( Sql::$CREATE_SURVEY_USERS_TABLE );
        $db->query( Sql::$DROP_SYS_ROLES_TABLE );
        $db->query( Sql::$CREATE_SYS_ROLES_TABLE );
        $db->query( Sql::$DROP_SYS_ROLE_MAPPINGS_TABLE );
        $db->query( Sql::$CREATE_SYS_ROLE_MAPPINGS_TABLE );
        $db->query( Sql::$DROP_SYS_USERS_TABLE );
        $db->query( Sql::$CREATE_SYS_USERS_TABLE );
        $db->query( Sql::$DROP_SYS_USER_ROLES_TABLE );
        $db->query( Sql::$CREATE_SYS_USER_ROLES_TABLE );
        //inserts
        $db->query( Sql::$INSERT_POST_TYPES_TABLE );
        $db->query( Sql::$INSERT_POSTS_TABLE );
        $db->query( Sql::$INSERT_POSTS_POST_TYPES );
        $db->query( Sql::$INSERT_SYS_ROLE_MAPPINGS_TABLE );
        $db->query( Sql::$INSERT_SYS_USERS_TABLE );
        $db->query( Sql::$INSERT_SYS_USER_ROLES_TABLE );

        $this->view->result = true;
        $this->view->message = "All database entries have been resetted.";
    }

    public function reindexAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $config = Zend_Registry::get( 'config' );
        SearchIndexer::setIndexDirectory( $config->search->path );
        $index = Custom_Search_Lucene::create( SearchIndexer::getIndexDirectory() );

        $users = new DB_Sysusers();
        $usersFetch = $users->fetchAll();
        foreach ( $usersFetch as $user )
        {
            if ( ( int ) $user->searchable === 1 )
            {
                $document = SearchIndexer::getDocument( $user );
                $index->addDocument( $document );
            }
        }

        $legislations = new DB_Legislations();
        $legislationFetch = $legislations->fetchAll();
        foreach ( $legislationFetch as $legislation )
        {
            $document = SearchIndexer::getDocument( $legislation );
            $index->addDocument( $document );
        }

        $this->view->result = true;
        $this->view->message = "All search entries have been reindexed.";
    }

}
