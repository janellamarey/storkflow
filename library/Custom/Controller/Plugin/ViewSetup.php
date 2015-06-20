<?php

class Custom_Controller_Plugin_ViewSetup extends Zend_Controller_Plugin_Abstract
{

    protected $_view;

    public function dispatchLoopStartup( Zend_Controller_Request_Abstract $request )
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper( 'viewRenderer' );
        $viewRenderer->init();
        $view = $viewRenderer->view;
        $this->_view = $view;

        $view->originalModule = $request->getModuleName();
        $view->originalController = $request->getControllerName();
        $view->originalAction = $request->getActionName();
        $view->doctype( 'XHTML1_STRICT' );

        $prefix = 'Custom_View_Helper';
        $dir = dirname( __FILE__ ) . '/../../View/Helper';
        $view->addHelperPath( $dir , $prefix );

        $view->headMeta()->appendHttpEquiv( 'Content-Type' , 'application/xhtml+xml; charset=utf-8' );
        $view->headMeta()->setName( 'description' , 'Bacoor Website' );
        $view->headMeta()->setName( 'keywords' , 'bacoor government' );
        $view->headMeta()->setName( 'robots' , 'index, follow' );

        $view->headTitle( 'Storkflow' );

        //set current user
        $aclHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'aclHelper' );
        $oUserData = $aclHelper->getCurrentUserData();
        Zend_Layout::getMvcInstance()->assign( 'user' , $oUserData );

        //append main css
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/style.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/menu.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/content.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/jquery/jquery-ui.min.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/jquery/jquery-ui.structure.min.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/jquery/jquery-ui.theme.min.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/jspdf/editor.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/fancybox/jquery.fancybox.css' );

        //append JQuery Library
        $config = Zend_Registry::get( 'config' );
        $jqueryPath = $config->paths->jquerypath;
        $view->headScript()->appendFile( $jqueryPath . '/jquery-1.11.0.min.js' );
        $view->headScript()->appendFile( $jqueryPath . '/jquery-ui.min.js' );

        //append BX slider
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/js/bxslider/jquery.bxslider.css' );
        $view->headScript()->appendFile( '/js/bxslider/jquery.bxslider.js' );

        //append fancybox
        $view->headScript()->appendFile( '/js/fancybox/jquery.fancybox.pack.js' );

        //append jspdf libraries
        $view->headScript()->appendFile( '/js/jspdf/html2canvas.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.min.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.PLUGINTEMPLATE.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.addhtml.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.addimage.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.autoprint.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.cell.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.from_html.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.javascript.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.png_support.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.sillysvgrenderer.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.split_text_to_size.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.standard_fonts_metrics.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.total_pages.js' );
        $view->headScript()->appendFile( '/js/jspdf/jspdf.plugin.customtext.js' );
        $view->headScript()->appendFile( '/js/png_support/png.js' );
        $view->headScript()->appendFile( '/js/png_support/zlib.js' );

        //append custom js
        $view->headScript()->appendFile( '/js/bcc.js' );

        //misc links
        $aLinks = array(
                'ordinances' => '/downloads/ordinances' ,
                'resolutions' => '/downloads/resolutions' ,
                'budgets' => '/downloads/budgets' ,
                'procurements' => '/downloads/procurements'
        );
        Zend_Layout::getMvcInstance()->assign( 'links' , $aLinks );

        $this->handleBanner( $request );
        $this->handleUserStatus( $view );
    }

    private function handleUserStatus( $view )
    {
        $aclHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'aclHelper' );
        $user = $aclHelper->getCurrentUserData();
        Zend_Layout::getMvcInstance()->assign( 'isLoggedIn' , $user[ 'sys_role_id' ] === SiteConstants::$GUESTROLE_ID ? false : true  );
        Zend_Layout::getMvcInstance()->assign( 'registrationURL' , $view->url( array( 'controller' => 'users' , 'action' => 'register' ) ) );
    }

    private function handleBanner( $request )
    {
        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();

        $pagesWithBanner = $controllerName === 'about' && $actionName === 'index' ||
                $controllerName === 'contact' && $actionName === 'index' ||
                $controllerName === 'downloads' && $actionName === 'budgets' ||
                $controllerName === 'downloads' && $actionName === 'ordinances' ||
                $controllerName === 'downloads' && $actionName === 'procurements' ||
                $controllerName === 'downloads' && $actionName === 'resolutions' ||
                $controllerName === 'index' && $actionName === 'index' ||
                $controllerName === 'index' && $actionName === '' ||
                $controllerName === 'legislation' && $actionName === 'index' ||
                $controllerName === 'legislation' && $actionName === 'view' ||
                $controllerName === 'map' && $actionName === 'index' ||
                $controllerName === 'news' && $actionName === 'index' ||
                $controllerName === 'polls' && $actionName === 'published' ||
                $controllerName === 'polls' && $actionName === 'view' ||
                $controllerName === 'search' && $actionName === 'index';

        if ( $pagesWithBanner )
        {
            Zend_Layout::getMvcInstance()->assign( 'isBannerShown' , true );
        }
        else
        {
            Zend_Layout::getMvcInstance()->assign( 'isBannerShown' , false );
        }
    }

    public function postDispatch( Zend_Controller_Request_Abstract $request )
    {
        //set proper menu items
        $menuHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'menuHelper' );
        $aclHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'aclHelper' );
        $oUserData = $aclHelper->getCurrentUserData();
        $oMenu = $menuHelper->getMenu( $oUserData[ 'sys_role_id' ] , $this->_view );
        $oMenuWithActive = $menuHelper->setActiveMenuItem( $oMenu );
        Zend_Layout::getMvcInstance()->assign( 'menu' , $oMenuWithActive );
    }

}
