<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Maps' );
Zend_Loader::loadClass( 'Polls' );
Zend_Loader::loadClass( 'Ordinances' );
Zend_Loader::loadClass( 'Images' );

class MapController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$ADMIN , array( 'edit' , 'add' , 'deletefile' ) );
        $this->_helper->_aclHelper->deny( SiteConstants::$SUPERUSER , array( 'edit' , 'add' ) );
        $this->_helper->_aclHelper->deny( SiteConstants::$SUPERADMIN , array( 'edit' , 'add' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'index' ) );

        $this->_helper->_aclHelper->addResource( 'polls' );
        $this->_helper->_aclHelper->allowAll( 'polls' , SiteConstants::$COUNCILOR , 'vote' );

        $this->oMaps = new Maps();
        $this->oPolls = new Polls();
        $this->oImages = new Images();
        $this->oOrdinances = new Ordinances();

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$GEOHAZARD_MENUITEM );

        $ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
        $ajaxContext->addActionContext( 'deletefile' , 'json' );
        $ajaxContext->initContext();
    }

    public function indexAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];
        $sUserId = $aUserData[ 'sys_user_id' ];
        $this->view->is_logged = $sRoleId === SiteConstants::$GUESTROLE_ID ? false : true;

        $this->view->title = "Geohazard Map";
        $aSubmissionMessage = '';
        $aErrorMessages = array();

        $oAppConfig = Zend_Registry::get( 'config' );
        $sPath = $oAppConfig->upload->root->maps
                . DIRECTORY_SEPARATOR . SiteConstants::$MAP_POST_ID;
        if ( !file_exists( $sPath ) )
        {
            mkdir( $sPath );
        }
        $oMaps = $this->oImages->getImages( $sPath , SiteConstants::$MAP_POST_ID , 3 );
        
        if ( is_null( $oMaps ) || empty( $oMaps ) )
        {
            $this->view->hasMap = false;
            $this->view->images = array();
        }
        else
        {
            $this->view->hasMap = true;
            $this->view->images = $oMaps;
        }

        $this->view->add = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'map' , 'add' );
        $this->view->edit = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'map' , 'edit' );

        $this->view->formmessage = $aSubmissionMessage;
        $this->view->formresponse = $aErrorMessages;

        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinances = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
        $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'polls' , 'vote' );
        $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $sUserId );
        $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
        $this->view->poll = $aFeaturedPoll;
    }

    public function addAction()
    {
        $this->view->title = "Add Geohazard map";
        $aSubmissionMessage = '';
        $aErrorMessages = array();

        if ( !empty( $_FILES ) )
        {
            $aUploadErrors = $this->oMaps->addMap( $_FILES );

            if ( empty( $aUploadErrors ) )
            {
                $aSubmissionMessage = "Map has been successfully added to the database.";
                $oMap = $this->oMaps->getHazardMap();
                if ( is_null( $oMap ) || empty( $oMap ) )
                {
                    $this->view->hasMap = false;
                }
                else
                {
                    $this->view->hazardMap = $oMap;
                    $this->view->hasMap = true;
                }
            }
            else
            {
                $aSubmissionMessage = "Uploading files failed. See errors below.";
                $aErrorMessages = array_merge( $aErrorMessages , $aUploadErrors );
                $this->view->post = $this->getRequest()->getPost();
            }
        }
        else
        {
            $oMap = $this->oMaps->getHazardMap();
            if ( is_null( $oMap ) || empty( $oMap ) )
            {
                $this->view->hasMap = false;
            }
            else
            {
                $this->view->hazardMap = $oMap;
                $this->view->hasMap = true;
            }
        }

        $this->view->formmessage = $aSubmissionMessage;
        $this->view->formresponse = $aErrorMessages;
    }

    public function editAction()
    {
        $sMessage = "";
        $oForm = new Form_MapEdit( SiteConstants::$MAP_POST_ID , '/map/edit/' );
        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                //images
                $oForm->getValues();
                $this->handlePostFiles( SiteConstants::$MAP_POST_ID , $oForm );

                $sMessage = "New 'Map' information has been successfully updated!";
            }
            else
            {
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        $oAppConfig = Zend_Registry::get( 'config' );
        $sPath = $oAppConfig->upload->root->maps
                . DIRECTORY_SEPARATOR . SiteConstants::$MAP_POST_ID;
        if ( !file_exists( $sPath ) )
        {
            mkdir( $sPath );
        }
        $this->view->images = $this->oImages->getImages( $sPath , SiteConstants::$MAP_POST_ID );
        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    private function handlePostFiles( $iPostId , $oForm )
    {
        $oForm->getValues();

        $oAppConfig = Zend_Registry::get( 'config' );
        $sDestinationFolder = $oAppConfig->upload->root->maps . DIRECTORY_SEPARATOR . $iPostId;
        if ( !file_exists( $sDestinationFolder ) )
        {
            mkdir( $sDestinationFolder );
        }

        $aFiles = $oForm->images->getFileName();
        if ( is_array( $aFiles ) )
        {
            foreach ( $aFiles as $file )
            {
                $basename = basename( $file );
                rename( $file , $sDestinationFolder . DIRECTORY_SEPARATOR . $basename );
            }
        }
        else
        {
            $basename = basename( $aFiles );
            rename( $aFiles , $sDestinationFolder . DIRECTORY_SEPARATOR . $basename );
        }
    }

    public function deletefileAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $iFileName = $this->getRequest()->getPost( 'filename' );
        $iPostId = $this->getRequest()->getPost( 'postid' );
        $iId = $this->getRequest()->getPost( 'id' );

        if ( $this->oMaps->deletefile( $iId , $iPostId , $iFileName ) )
        {
            $this->view->result = true;
            $this->view->filename = $iFileName;
            $this->view->id = $iId;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->filename = $iFileName;
            $this->view->id = $iId;
            $this->view->message = "Cannot delete data";
        }
    }

}
