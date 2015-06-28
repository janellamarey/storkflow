<?php

class Custom_Controller_Plugin_ViewSetup extends Zend_Controller_Plugin_Abstract
{

    protected $_view;

    public function preDispatch( Zend_Controller_Request_Abstract $request )
    {
        if ( $this->isLogged() )
        {
            $this->handleLogout( $request );
        }
        else
        {
            $this->handleLogin( $request );
        }
    }

    private function handleLogin( $request )
    {
        $form = new Form_LoginInHeader( '/' );
        if ( $request->isPost() && $request->getPost( 'sendlogin' ) )
        {
            if ( $form->isValid( $request->getPost() ) )
            {
                $authAdapter = $form->username->getValidator( 'Authorise' )->getAuthAdapter();
                $data = $authAdapter->getResultRowObject( null , 'password' );
                $auth = Zend_Auth::getInstance();
                $auth->getStorage()->write( $data );

                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper( 'Redirector' );
                $redirector->gotoSimple( 'index' , 'accounts' );
            }
            else
            {
                $auth = Zend_Auth::getInstance();
                $auth->clearIdentity();
                $form->populate( $request->getPost() );
                $form->getElement( 'sendlogin' )->setValue( '1' );
            }
        }
        else if ( $request->isGet() )
        {
            $form->getElement( 'sendlogin' )->setValue( '1' );
        }
        Zend_Layout::getMvcInstance()->assign( 'loginForm' , $form );
    }

    private function handleLogout( $request )
    {
        $form = new Form_LogoutInHeader( '/' );
        $form->getElement( 'welcome' )->setValue( 'Welcome back ' . $this->getCurrentUsername() . '!' );

        if ( $request->isPost() && $request->getPost( 'sendlogout' ) && $form->isValid( $request->getPost() ) )
        {
            $auth = Zend_Auth::getInstance();
            $auth->clearIdentity();

            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper( 'Redirector' );
            $redirector->gotoSimple( 'index' , 'index' );
        }
        else
        {
            $form->getElement( 'sendlogout' )->setValue( '1' );
        }
        Zend_Layout::getMvcInstance()->assign( 'loginForm' , $form );
    }

    public function dispatchLoopStartup( Zend_Controller_Request_Abstract $request )
    {
        $this->initView( $request );
        $this->initHelperPath();
        $this->initMeta();
        $this->initTitle( 'Storkflow' );
        $this->initCSS();
        $this->initJQuery();
        $this->initBXSlider();
        $this->initFlexSlider();
        $this->initFancyBox();
        $this->initJSPDF();
        $this->initCustomJS();
        $this->initLinks();
        $this->initUser();
        $this->initHomePage( $request );
    }

    public function postDispatch( Zend_Controller_Request_Abstract $request )
    {
        $menuHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'menuHelper' );
        $user = $this->getCurrentUser();
        $menu = $menuHelper->getMenu( $user[ 'role_id' ] , $this->getView() );

        if ( !empty( $menu ) )
        {
            $menuWithActiveItem = $menuHelper->setActiveMenuItem( $menu );
            Zend_Layout::getMvcInstance()->assign( 'menu' , $menuWithActiveItem );
        }
        else
        {
            Zend_Layout::getMvcInstance()->assign( 'menu' , array() );
        }
    }

    public function initView( $request )
    {
        $view = $this->getView();
        $view->originalModule = $request->getModuleName();
        $view->originalController = $request->getControllerName();
        $view->originalAction = $request->getActionName();
        $view->doctype( 'XHTML1_STRICT' );
    }

    public function initHelperPath()
    {
        $view = $this->getView();
        $prefix = 'Custom_View_Helper';
        $dir = dirname( __FILE__ ) . '/../../View/Helper';
        $view->addHelperPath( $dir , $prefix );
    }

    public function initMeta()
    {
        $view = $this->getView();
        $view->headMeta()->appendHttpEquiv( 'Content-Type' , 'application/xhtml+xml; charset=utf-8' );
        $view->headMeta()->setName( 'description' , 'Storkflow' );
        $view->headMeta()->setName( 'keywords' , 'storkflow' );
        $view->headMeta()->setName( 'robots' , 'index, follow' );
    }

    public function initTitle( $title = "" )
    {
        $view = $this->getView();
        $view->headTitle( $title );
    }

    public function initCSS()
    {
        $view = $this->getView();
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/storkflow.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/content.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/jquery/jquery-ui.min.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/jquery/jquery-ui.structure.min.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/jquery/jquery-ui.theme.min.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/jspdf/editor.css' );
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/css/fancybox/jquery.fancybox.css' );
        $view->headLink()->appendStylesheet( 'http://fonts.googleapis.com/css?family=Righteous' );
    }

    public function initJQuery()
    {
        $view = $this->getView();
        $config = Zend_Registry::get( 'config' );
        $jqueryPath = $config->paths->jquerypath;
        $view->headScript()->appendFile( $jqueryPath . '/jquery-1.11.0.min.js' );
        $view->headScript()->appendFile( $jqueryPath . '/jquery-ui.min.js' );
        $view->headScript()->appendFile( $jqueryPath . '/jquery.easing.js' );
        $view->headScript()->appendFile( $jqueryPath . '/jquery.mousewheel.js' );
    }

    public function initBXSlider()
    {
        $view = $this->getView();
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/js/bxslider/jquery.bxslider.css' );
        $view->headScript()->appendFile( '/js/bxslider/jquery.bxslider.js' );
    }

    public function initFlexSlider()
    {
        $view = $this->getView();
        $view->headLink()->appendStylesheet( $view->baseUrl() . '/js/flexslider/flexslider.css' );
        $view->headScript()->appendFile( '/js/flexslider/jquery.flexslider-min.js' );
    }

    public function initFancyBox()
    {
        $view = $this->getView();
        $view->headScript()->appendFile( '/js/fancybox/jquery.fancybox.pack.js' );
    }

    public function initJSPDF()
    {
        $view = $this->getView();
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
    }

    public function initCustomJS()
    {
        $view = $this->getView();
        $view->headScript()->appendFile( '/js/storkflow.js' );
    }

    public function initUser()
    {
        $user = $this->getCurrentUser();
        Zend_Layout::getMvcInstance()->assign( 'user' , $user );
    }

    public function initLinks()
    {
        $links = array(
                'account' => '/accounts/index'
        );
        Zend_Layout::getMvcInstance()->assign( 'links' , $links );
    }

    private function getView()
    {
        if ( is_null( $this->_view ) )
        {
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper( 'viewRenderer' );
            $viewRenderer->init();
            $this->_view = $viewRenderer->view;
        }
        return $this->_view;
    }

    private function getCurrentUser()
    {
        $aclHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'aclHelper' );
        return $aclHelper->getCurrentUserArray();
    }

    private function getCurrentUsername()
    {
        $user = $this->getCurrentUser();
        $username = 'Anonymous';
        if ( !is_null( $user[ 'username' ] ) && !empty( $user[ 'username' ] ) )
        {
            $username = $user[ 'username' ];
        }
        return $username;
    }

    public function initHomePage( $request )
    {
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $isHomePage = false;
        if ( $this->isHomePage( $controller , $action ) )
        {
            $isHomePage = true;
        }
        Zend_Layout::getMvcInstance()->assign( 'isHomePage' , $isHomePage );
    }

    private function isHomePage( $controller , $action )
    {
        return $controller === 'accounts' && $action === 'index' || $controller === 'accounts'
                && $action === '';
    }

    private function isLogged()
    {
        return $this->isOnline( $this->getCurrentUser() );
    }

    private function isOnline( $user )
    {
        return $user[ 'role_id' ] === SiteConstants::$GUEST_ID ? false : true;
    }

}
