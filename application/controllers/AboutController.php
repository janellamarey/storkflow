<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'AboutUs' );
Zend_Loader::loadClass( 'Polls' );
Zend_Loader::loadClass( 'Ordinances' );
Zend_Loader::loadClass( 'Uploader' );

class AboutController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$ABOUTUS_MENUITEM );

        $this->_helper->_aclHelper->allow( SiteConstants::$ADMIN , array( 'edit' , 'delete' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'index' ) );

        $this->_helper->_aclHelper->addResource( 'polls' );
        $this->_helper->_aclHelper->allowAll( 'polls' , SiteConstants::$COUNCILOR , 'vote' );

        $this->oAboutUs = new AboutUs();
        $this->oPolls = new Polls();
        $this->oOrdinances = new Ordinances();
        $this->oUploader = new Uploader();

        $ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
        $ajaxContext->addActionContext( 'delete' , 'json' );
        $ajaxContext->initContext();
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $iFileName = $this->getRequest()->getPost( 'filename' );

        if ( $this->oAboutUs->deletefile( $iFileName ) )
        {
            $this->view->result = true;
        }
        else
        {
            $this->view->result = false;
        }
    }

    public function indexAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];
        $sUserId = $aUserData[ 'sys_user_id' ];
        $this->view->is_logged = $sRoleId === SiteConstants::$GUESTROLE_ID ? false : true;

        $this->view->edit = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'about' , 'edit' );
        $this->view->title = "About Us";
        $this->view->content = $this->oAboutUs->getContent();

        $this->view->images = $this->oAboutUs->getImages( SiteConstants::$ABOUT_POST_ID , false , 3 );

        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinances = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
        $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'polls' , 'vote' );
        $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $sUserId );
        $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
        $this->view->poll = $aFeaturedPoll;
    }

    public function editAction()
    {
        $sMessage = "";
        $oForm = new Form_AboutUsEdit( '/about/edit/' );
        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $oForm->getValues();    
                $this->handlePostFiles( $oForm );

                //content
                $sContent = $this->_request->getPost( 'content' );
                $this->oAboutUs->updateData( array( 'body' => $sContent ) );
                $sMessage = "New 'About Us' information has been successfully updated!";
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->_request->isGet() )
        {
            $oForm->populate( array( 'content' => $this->oAboutUs->getContent() ) );
        }

        $this->view->images = $this->oAboutUs->getImages( SiteConstants::$ABOUT_POST_ID );
        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    private function handlePostFiles( $oForm , $bInsertToDB = true )
    {
        $oAppConfig = Zend_Registry::get( 'config' );
        $sDestinationFolder = $oAppConfig->upload->root->posts
                . DIRECTORY_SEPARATOR . SiteConstants::$ABOUT_POST_ID;
        if ( !file_exists( $sDestinationFolder ) )
        {
            mkdir( $sDestinationFolder );
        }

        $file = $oForm->images->getFileName();        
        if ( !is_null( $file ) && !empty( $file ) )
        {
            if ( $bInsertToDB )
            {
                $sCaption = $this->_request->getPost( 'caption' );
                $aData = array(
                        "post_id" => SiteConstants::$ABOUT_POST_ID ,
                        "name" => basename( $file ) ,
                        "caption" => $sCaption
                );
                $this->oAboutUs->saveImages( $aData );
            }
        }
    }

}
