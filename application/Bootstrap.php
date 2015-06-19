<?php

class Bootstrap
{

    public function __construct( $configSection )
    {
        $rootDir = dirname( dirname( __FILE__ ) );
        define( 'ROOT_DIR' , $rootDir );

        set_include_path( get_include_path()
                . PATH_SEPARATOR . ROOT_DIR . '/application/models/'
                . PATH_SEPARATOR . ROOT_DIR . '/application/forms/'
                . PATH_SEPARATOR . ROOT_DIR . '/application/sql/'
        );

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace( 'Custom_' );
        $autoloader->registerNamespace( 'DB_' );
        $autoloader->registerNamespace( 'Utils_' );
        $autoloader->registerNamespace( 'Form_' );

        // Load configuration
        Zend_Registry::set( 'configSection' , $configSection );
        $config = new Zend_Config_Ini( ROOT_DIR . '/application/configs/config.ini' , $configSection );

        Zend_Registry::set( 'config' , $config );
        date_default_timezone_set( $config->date_default_timezone );

        // configure database and store to the registry
        $db = Zend_Db::factory( $config->db );
        Zend_Db_Table_Abstract::setDefaultAdapter( $db );
        Zend_Registry::set( 'db' , $db );
    }

    public function configureHelpers()
    {
        $oAcl = new Custom_Acl();
        $oAclHelper = new Custom_Controller_Action_Helper_AclHelper( null , array( 'acl' => $oAcl ) );
        $oCustomFormatDateHelper = new Custom_Controller_Action_Helper_DateFormatter();
        $oValidateHelper = new Custom_Controller_Action_Helper_Validator();
        $oMenuHelper = new Custom_Controller_Action_Helper_MenuHelper();
        $oLinkHelper = new Custom_Controller_Action_Helper_LinksHelper();

        Zend_Controller_Action_HelperBroker::addHelper( $oAclHelper );
        Zend_Controller_Action_HelperBroker::addHelper( $oCustomFormatDateHelper );
        Zend_Controller_Action_HelperBroker::addHelper( $oValidateHelper );
        Zend_Controller_Action_HelperBroker::addHelper( $oMenuHelper );
        Zend_Controller_Action_HelperBroker::addHelper( $oLinkHelper );
    }

    public function configureFrontController()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->setControllerDirectory( ROOT_DIR . '/application/controllers' );

        //create layout 
        $frontController->registerPlugin( new Custom_Controller_Plugin_ViewSetup() );
        Zend_Layout::startMvc( array( 'layoutPath' => ROOT_DIR . '/application/views/layouts' ) );
    }

    public function configureSearchIndexer()
    {
        $config = Zend_Registry::get( 'config' );
        require_once 'SearchIndexer.php';
        SearchIndexer::setIndexDirectory( $config->search->path );
        Custom_Db_Table_Row_Observable::attachObserver( 'SearchIndexer' );
    }

    public function configure()
    {
        $this->configureHelpers();
        $this->configureFrontController();
        $this->configureSearchIndexer();
    }

    public function runApp()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->dispatch();
    }

}
