<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Ordinances' );
Zend_Loader::loadClass( 'Users' );
Zend_Loader::loadClass( 'Emails' );

class OrdinancesController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$ADMIN , array( 'index' , 'add' , 'download' , 'delete' , 'view' , 'deletefile' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$COUNCILOR , array( 'index' , 'add' , 'download' , 'approve' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'index' , 'view' ) );

        $this->oOrdinances = new Ordinances();
        $this->oUsers = new Users();
        $this->oEmails = new Emails();

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$DOWNLOADS_MENUITEM );

        $ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
        $ajaxContext->addActionContext( 'delete' , 'json' );
        $ajaxContext->addActionContext( 'deletefile' , 'json' );
        $ajaxContext->addActionContext( 'approve' , 'json' );
        $ajaxContext->initContext();
    }

    private function formatOrdinanceIds( array $values )
    {
        $aResult = array();
        foreach ( $values as $value )
        {
            $aResult[ $value[ 'id' ] ] = 1;
        }
        return $aResult;
    }

    private function checkAlreadyApproved( $aApprovedOrdinances , $iOrId )
    {
        if ( array_key_exists( $iOrId , $aApprovedOrdinances ) )
        {
            return true;
        }
        return false;
    }

    public function indexAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];
        $sUserId = $aUserData[ 'sys_user_id' ];

        $bDeleteAllowed = $this->checkDeleteAllowed( $sRoleId );
        $bApproveAllowed = $this->checkApproveAllowed( $sRoleId );

        $aOrdinances = $this->oOrdinances->getOrdinances();
        $aOrdinancesFiles = $this->oOrdinances->getFiles();
        $aOrdinancesUsers = $this->oOrdinances->getOrdinanceApprovalsAll();
        $aApprovedOrdinances = $this->formatOrdinanceIds( $this->oOrdinances->getApprovedOrdinances( $sUserId ) );

        $aFinalOrdinances = array();
        for ( $i = 0 , $count = count( $aOrdinances ); $i < $count; $i++ )
        {
            $aItem = array();
            $aItem[ 'id' ] = $aOrdinances[ $i ][ 'id' ];
            $aItem[ 'name' ] = $aOrdinances[ $i ][ 'name' ];
            $aItem[ 'summary' ] = $aOrdinances[ $i ][ 'summary' ];
            $aItem[ 'delete' ] = $bDeleteAllowed;
            $aItem[ 'approve' ] = $bApproveAllowed;
            $aItem[ 'alreadyapproved' ] = $this->checkAlreadyApproved( $aApprovedOrdinances , $aItem[ 'id' ] );
            $aFinalOrdinances[ $aOrdinances[ $i ][ 'id' ] ] = $aItem;
        }

        $aFinalOrdinancesFiles = array();
        for ( $i = 0 , $count = count( $aOrdinancesFiles ); $i < $count; $i++ )
        {
            $aItem = array();
            $aItem[ 'or_id' ] = $aOrdinancesFiles[ $i ][ 'or_id' ];
            $aItem[ 'f_id' ] = $aOrdinancesFiles[ $i ][ 'f_id' ];
            $aItem[ 'f_name' ] = $aOrdinancesFiles[ $i ][ 'f_name' ];
            $aItem[ 'url' ] = $aOrdinancesFiles[ $i ][ 'url' ];

            $aFinalOrdinancesFiles[ $aOrdinancesFiles[ $i ][ 'or_id' ] ][] = $aItem;
        }

        foreach ( $aFinalOrdinancesFiles as $value )
        {
            $aFinalOrdinances[ $value[ 0 ][ 'or_id' ] ][ 'files' ] = $value;
        }

        $aFinalOrdinanceUsers = array();
        for ( $i = 0 , $count = count( $aOrdinancesUsers ); $i < $count; $i++ )
        {
            $aItem = array();
            $aItem[ 'id' ] = $aOrdinancesUsers[ $i ][ 'id' ];
            $aItem[ 'or_id' ] = $aOrdinancesUsers[ $i ][ 'or_id' ];
            $aItem[ 'user_id' ] = $aOrdinancesUsers[ $i ][ 'user_id' ];
            $aItem[ 'firstname' ] = $aOrdinancesUsers[ $i ][ 'firstname' ];
            $aItem[ 'lastname' ] = $aOrdinancesUsers[ $i ][ 'lastname' ];
            $aItem[ 'signature' ] = $aOrdinancesUsers[ $i ][ 'signature' ];

            $aFinalOrdinanceUsers[ $aOrdinancesUsers[ $i ][ 'or_id' ] ][] = $aItem;
        }

        foreach ( $aFinalOrdinanceUsers as $value )
        {
            $aFinalOrdinances[ $value[ 0 ][ 'or_id' ] ][ 'approvals' ] = $value;
        }

        $this->view->ordinances = $aFinalOrdinances;
    }

    public function addAction()
    {
        $this->view->title = "Add new ordinance";

        $aSubmissionMessage = '';
        $aErrorMessages = array();

        if ( $this->getRequest()->isPost() )
        {
            $sSummary = $this->getRequest()->getPost( 'or-summary' );
            $sName = $this->getRequest()->getPost( 'or-name' );

            $aErrorMessages = array_merge( $aErrorMessages , $this->_helper->_validator->errorMessages( 'notempty' , $sName , "Name cannot be empty." ) );
            $aErrorMessages = array_merge( $aErrorMessages , $this->_helper->_validator->errorMessages( 'notempty' , $sSummary , "Summary cannot be empty." ) );

            if ( empty( $aErrorMessages ) )
            {
                $id = $this->oOrdinances->add( array( 'name' => $sName , 'summary' => $sSummary ) );
                if ( !empty( $_FILES ) )
                {
                    if ( empty( $aUploadErrors ) )
                    {
                        $aSubmissionMessage = "Ordinance has been successfully added to the database.";
                    }
                    else
                    {
                        $aSubmissionMessage = "Uploading files failed. See errors below.";
                        $aErrorMessages = array_merge( $aErrorMessages , $aUploadErrors );
                        $this->view->post = $this->getRequest()->getPost();
                    }
                }
            }
            else
            {
                $aSubmissionMessage = "Submission Failed. See errors below.";
                $this->view->post = $this->getRequest()->getPost();
            }
        }
        $this->view->formmessage = $aSubmissionMessage;
        $this->view->formresponse = $aErrorMessages;

        $this->view->url = $this->view->url( array( 'controller' => 'ordinances' , 'action' => 'add' ) );
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $iOrId = ( int ) $this->getRequest()->getPost( 'id' );
        $this->view->list = "ordinances";
        if ( $this->oOrdinances->delete( $iOrId ) )
        {
            $this->view->result = true;
            $this->view->id = $iOrId;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $iOrId;
            $this->view->message = "Cannot delete data.";
        }
    }

    public function viewAction()
    {
        if ( $this->getRequest()->isGet() )
        {
            $aUserData = $this->_helper->_aclHelper->getCurrentUserData();

            $iOrId = ( int ) $this->getRequest()->getParam( 'id' );
            $oOrdinance = $this->oOrdinances->getOrdinance( $iOrId );
            $aOrdinanceFiles = $this->oOrdinances->getFilesFromOrdinanceId( $iOrId );
            $aOrdinanceApprovals = $this->oOrdinances->getOrdinanceApprovalsFromOrdinanceId( $iOrId );
            $aApprovedOrdinances = $this->oOrdinances->getApprovedOrdinance( $aUserData[ 'sys_user_id' ] , $iOrId );

            $this->view->ordinance = $oOrdinance;
            $this->view->files = $aOrdinanceFiles;
            $this->view->approvals = $aOrdinanceApprovals;
            $this->view->canApprove = $this->checkApproveAllowed( $aUserData[ 'sys_role_id' ] );

            if ( !is_null( $aApprovedOrdinances ) && !empty( $aApprovedOrdinances ) )
            {
                $this->view->doneApproving = true;
            }
            else
            {
                $this->view->doneApproving = false;
            }
        }
    }

    public function approveAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sUserId = $aUserData[ 'sys_user_id' ];
        $oUser = $this->oUsers->getPersonalData( $aUserData[ 'sys_user_id' ] );

        $iOrId = ( int ) $this->getRequest()->getPost( 'id' );
        $iApprovalId = $this->oOrdinances->addApproval( array( 'ordinance_id' => $iOrId , 'sys_user_id' => $sUserId ) );
        if ( $iApprovalId )
        {
            if ( $this->oEmails->ordinanceApproved(
                            $oUser , $this->oOrdinances->getOrdinance( $iOrId ) ) )
            {
                $this->view->result = true;
                $this->view->id = $iOrId;
                $this->view->approveid = $iApprovalId;
                $this->view->user = $oUser->toArray();
                $this->view->message = "<h4>You have already signed this ordinance.</h4>";
            }
            else
            {
                $this->view->result = false;
                $this->view->id = $iOrId;
                $this->view->message = "<h4>Cannot approve ordinance.</h4>";
            }
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $iOrId;
            $this->view->message = "<h4>Cannot approve ordinance.</h4>";
        }
        $this->view->messagecontainer = 'appr-mess-' . $iOrId;
    }

    private function checkDeleteAllowed( $sRoleId )
    {
        $bDeleteAllowed = false;

        if ( $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'ordinances' , 'delete' ) )
        {
            $bDeleteAllowed = true;
        }
        return $bDeleteAllowed;
    }

    private function checkApproveAllowed( $sRoleId )
    {
        $bApproveAllowed = false;

        if ( $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'ordinances' , 'approve' ) )
        {
            $bApproveAllowed = true;
        }
        return $bApproveAllowed;
    }

}
