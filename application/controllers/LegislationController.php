<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Ordinances' );
Zend_Loader::loadClass( 'Users' );
Zend_Loader::loadClass( 'Emails' );
Zend_Loader::loadClass( 'Images' );
Zend_Loader::loadClass( 'Polls' );

class LegislationController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$ADMIN , array( 'index' , 'add' , 'addbuproc' , 'editbuproc' , 'edit' , 'download' , 'delete' , 'deletefile' , 'deletebuproc' , 'deletebuprocfile' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$SUPERADMIN , array( 'publish' , 'newpublish' , 'newpublishitem' , 'add' ) );

        //set add, edit acl
        $this->_helper->_aclHelper->deny( SiteConstants::$SUPERADMIN , array( 'edit' , 'delete' , 'deletebuproc' , 'deletebuprocfile' , 'approve' , 'approveitem' , 'addbuproc' , 'editbuproc' ) );
        $this->_helper->_aclHelper->deny( SiteConstants::$SUPERUSER , array( 'edit' , 'delete' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$SUPERUSER , array( 'approve' , 'approveitem' , 'add' ) );

        $this->_helper->_aclHelper->allow( SiteConstants::$COUNCILOR , array( 'index' , 'download' , 'approve' , 'approveitem' ) );
        $this->_helper->_aclHelper->deny( SiteConstants::$COUNCILOR , array( 'add' ) );

        //set approval acl
        $this->_helper->_aclHelper->deny( SiteConstants::$VOTER , array( 'add' ) );
        $this->_helper->_aclHelper->deny( SiteConstants::$ADMIN , array( 'approve' , 'approveitem' ) );
        $this->_helper->_aclHelper->deny( SiteConstants::$VOTER , array( 'approve' , 'approveitem' ) );
        $this->_helper->_aclHelper->deny( SiteConstants::$SUPERADMIN , array( 'approve' , 'approveitem' ) );

        $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'index' , 'view' ) );

        $this->_helper->_aclHelper->addResource( 'polls' );
        $this->_helper->_aclHelper->allowAll( 'polls' , SiteConstants::$COUNCILOR , 'vote' );

        $this->oOrdinances = new Ordinances();
        $this->oUsers = new Users();
        $this->oEmails = new Emails();
        $this->oImages = new Images();
        $this->oPolls = new Polls();

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$DOWNLOADS_MENUITEM );

        $ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
        $ajaxContext->addActionContext( 'delete' , 'json' );
        $ajaxContext->addActionContext( 'deletefile' , 'json' );
        $ajaxContext->addActionContext( 'deletebuproc' , 'json' );
        $ajaxContext->addActionContext( 'deletebuprocfile' , 'json' );
        $ajaxContext->addActionContext( 'approve' , 'json' );
        $ajaxContext->addActionContext( 'approveitem' , 'json' );
        $ajaxContext->addActionContext( 'publish' , 'json' );
        $ajaxContext->addActionContext( 'newpublish' , 'json' );
        $ajaxContext->addActionContext( 'newpublishitem' , 'json' );
        $ajaxContext->initContext();
    }

    public function indexAction()
    {
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$PENDING_MENUITEM );

        $user = $this->_helper->_aclHelper->getCurrentUserData();
        $roleId = $user[ 'role_id' ];
        $userId = $user[ 'sys_user_id' ];

        $isAddingAllowed = $this->checkAddAllowed( $roleId );
        $isDeletingAllowed = $this->checkDeleteAllowed( $roleId );
        $isApprovingAllowed = $this->checkApproveAllowed( $roleId );
        $isEditingAllowed = $this->checkEditAllowed( $roleId );
        $isPublishingAllowed = $this->checkPublishAllowed( $roleId );

        $ordinances = $this->oOrdinances->getOrdinances( 0 , 0 , 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' );
        $currentUserApprovedOrdinances = $this->oOrdinances->getApprovedOrdinances( $userId , 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' );
        $userApprovals = $this->oOrdinances->getIdArray( $currentUserApprovedOrdinances );

        $council = $this->oUsers->getCouncilors();
        if ( $roleId === SiteConstants::$SUPERADMIN_ID )
        {
            $council[] = $this->oUsers->getSingleSuperuser();
        }

        $ordinancesApprovedByAtLeastOneInGroup = $this->oOrdinances->getApprovedOrdinancesFromAtLeastOneUser( $council , 'NOT_PUBLISHED' );
        $halfsignedOrdinances = $this->oOrdinances->getIdArray( $ordinancesApprovedByAtLeastOneInGroup );

        $ordinancesApprovedBySuperuser = $this->oOrdinances->getApprovedOrdinancesFromUsers( array( $this->oUsers->getSingleSuperuser() ) , 'NOT_PUBLISHED' );
        $signedBySuperuser = $this->oOrdinances->getIdArray( $ordinancesApprovedBySuperuser );

        $ordinancesApprovedBySuperadmin = $this->oOrdinances->getApprovedOrdinancesFromUsers( array( $this->oUsers->getSingleSuperadmin() ) , 'NOT_PUBLISHED' );
        $signedBySuperadmin = $this->oOrdinances->getIdArray( $ordinancesApprovedBySuperadmin );

        $finalOrdinances = $this->oOrdinances->getOrdinancesArray( $ordinances , $userApprovals , $isDeletingAllowed , $isApprovingAllowed , $isEditingAllowed , $isPublishingAllowed , $halfsignedOrdinances , $signedBySuperuser , $signedBySuperadmin , $roleId );

        $allApprovals = $this->oOrdinances->getOrdinanceApprovalsAll( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' );
        $finalApprovals = $this->oOrdinances->getApprovalsArray( $allApprovals );
        foreach ( $finalApprovals as $value )
        {
            $finalOrdinances[ $value[ 0 ][ 'or_id' ] ][ 'approvalsText' ] = $this->oOrdinances->getStatus( $value );
        }
        $this->view->ordinances = $finalOrdinances;
        $this->view->canAdd = $isAddingAllowed;
        $this->view->is_logged = $roleId === SiteConstants::$GUESTROLE_ID ? false : true;
        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinancesside = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
        $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'polls' , 'vote' );
        $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $userId );
        $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
        $this->view->poll = $aFeaturedPoll;
    }

    public function addAction()
    {
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$PENDING_MENUITEM );

        $sMessage = "";
        $oForm = new Form_LegislationEdit( '/legislation/add/' );

        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
                $oUser = $this->oUsers->getPersonalData( $aUserData[ 'sys_user_id' ] );
                $oUserAccData = $this->oUsers->getAccountData( $aUserData[ 'sys_user_id' ] );


                $sName = $this->_request->getPost( 'name' );
                $sSummary = $this->_request->getPost( 'summary' );
                $sType = $this->_request->getPost( 'type' );

                $aData = array( 'name' => $sName , 'summary' => $sSummary , 'type' => $sType, 'status'=>'NOT_PUBLISHED' );

                $iOrdinanceId = $this->oOrdinances->add( $aData );
                if ( $iOrdinanceId )
                {
                    $sLink = SiteConstants::$SITE . $this->view->url( array( 'controller' => 'legislation' , 'action' => 'view' , 'id' => $iOrdinanceId ) , null , true );
                    if ( $this->oEmails->ordinanceCreated(
                                    $oUser , $oUserAccData , $this->oOrdinances->getOrdinance( $iOrdinanceId ) , $sLink ) )
                    {
                        $sMessage = "Content has been successfully updated!<br>An email is sent to Councilors, Secretary, Vice-Mayors and Mayor.";
                    }
                    else
                    {
                        $sMessage = "Submission success but emails were not sent. There seems to be a problem in network connection.";
                    }
                }
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function addbuprocAction()
    {
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$PENDING_MENUITEM );

        $message = "";
        $form = new Form_BudgetProcurement( '/legislation/addbuproc/' );

        if ( $this->_request->isPost() )
        {
            if ( $form->isValid( $this->_request->getPost() ) )
            {
                $userData = $this->_helper->_aclHelper->getCurrentUserData();
                $user = $this->oUsers->getPersonalData( $userData[ 'sys_user_id' ] );
                $userAccountData = $this->oUsers->getAccountData( $userData[ 'sys_user_id' ] );


                $name = $this->_request->getPost( 'name' );
                $type = $this->_request->getPost( 'type' );

                $data = array( 'name' => $name , 'type' => $type , 'summary' => '' , 'status' => 'PUBLISHED' );
                $ordinanceId = $this->oOrdinances->add( $data );

                if ( $ordinanceId )
                {
                    $sLink = SiteConstants::$SITE . $this->view->url( array( 'controller' => 'legislation' , 'action' => 'view' , 'id' => $ordinanceId ) , null , true );
                    if ( $this->oEmails->ordinanceCreated(
                                    $user , $userAccountData , $this->oOrdinances->getOrdinance( $ordinanceId ) , $sLink ) )
                    {
                        $message = "Content has been successfully updated!<br>An email is sent to Councilors, Secretary, Vice-Mayors and Mayor.";
                    }
                    else
                    {
                        $message = "Submission success but emails were not sent. There seems to be a problem in network connection.";
                    }
                }
            }
            else
            {
                $form->populate( $this->_request->getPost() );
                $message = 'Submission Failed. See errors below.';
            }
        }

        $this->view->form = $form;
        $this->view->message = $message;
    }

    public function editbuprocAction()
    {
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$PENDING_MENUITEM );

        $message = "";
        $form = new Form_BudgetProcurementEdit( '/legislation/editbuproc/' );

        if ( $this->_request->isPost() )
        {
            $ordinanceId = $this->_request->getPost( 'id' );
            if ( $ordinanceId )
            {
                if ( $form->isValid( $this->_request->getPost() ) )
                {
                    $userData = $this->_helper->_aclHelper->getCurrentUserData();
                    $user = $this->oUsers->getPersonalData( $userData[ 'sys_user_id' ] );
                    $userAccountData = $this->oUsers->getAccountData( $userData[ 'sys_user_id' ] );
                    $name = $this->_request->getPost( 'name' );
                    $type = $this->_request->getPost( 'type' );

                    $data = array( 'name' => $name , 'type' => $type , 'summary' => '' , 'status' => 'PUBLISHED' );
                    if ( $this->oOrdinances->update( $data , $ordinanceId ) )
                    {
                        $this->handlePostFiles( $ordinanceId , $form );

                        $sLink = SiteConstants::$SITE . $this->view->url( array( 'controller' => 'legislation' , 'action' => 'view' , 'id' => $ordinanceId ) , null , true );
                        if ( $this->oEmails->ordinanceCreated(
                                        $user , $userAccountData , $this->oOrdinances->getOrdinance( $ordinanceId ) , $sLink ) )
                        {
                            $message = "Content has been successfully updated!<br>An email is sent to Councilors, Secretary, Vice-Mayors and Mayor.";
                        }
                        else
                        {
                            $message = "Submission success but emails were not sent. There seems to be a problem in network connection.";
                        }
                    }
                }
                else
                {
                    $form->populate( $this->_request->getPost() );
                    $message = 'Submission Failed. See errors below.';
                }
                $this->view->files = $this->oOrdinances->addURLs( $this->oOrdinances->getFilesFromOrdinanceId( $ordinanceId ) );
            }
        }

        if ( $this->_request->isGet() )
        {
            $ordinanceId = $this->_request->getParam( 'id' );
            if ( $ordinanceId )
            {
                $ordinance = $this->oOrdinances->getOrdinance( $ordinanceId );
                $form->populate( array( 'id' => $ordinanceId , 'name' => $ordinance->name , 'type' => $ordinance->type ) );
                $this->view->files = $this->oOrdinances->addURLs( $this->oOrdinances->getFilesFromOrdinanceId( $ordinanceId ) );
            }
        }

        $this->view->form = $form;
        $this->view->message = $message;
    }

    private function handlePostFiles( $ordinanceId , $form )
    {
        $form->getValues();

        $config = Zend_Registry::get( 'config' );
        $destinationFolder = $config->upload->root->ordinances
                . DIRECTORY_SEPARATOR . $ordinanceId;
        if ( !file_exists( $destinationFolder ) )
        {
            mkdir( $destinationFolder );
        }

        $file = $form->pdfs->getFileName();
        if ( !is_null( $file ) && !empty( $file ) )
        {
            $basename = basename( $file );
            $isOverwritten = file_exists( $destinationFolder . DIRECTORY_SEPARATOR . $basename );
            if ( rename( $file , $destinationFolder . DIRECTORY_SEPARATOR . $basename ) && !$isOverwritten )
            {
                $data = array(
                        "ordinance_id" => $ordinanceId ,
                        "filename" => $basename
                );
                $this->oOrdinances->saveDocs( $data );
            }
        }
    }

    public function editAction()
    {
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$PENDING_MENUITEM );

        $sMessage = "";
        $oForm = new Form_LegislationEdit( '/legislation/edit/' );
        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
                $oUser = $this->oUsers->getPersonalData( $aUserData[ 'sys_user_id' ] );
                $oUserAccData = $this->oUsers->getAccountData( $aUserData[ 'sys_user_id' ] );

                $iOrdinanceId = $this->_request->getPost( 'id' );
                $sName = $this->_request->getPost( 'name' );
                $sSummary = $this->_request->getPost( 'summary' );
                $sType = $this->_request->getPost( 'type' );


                $aData = array( 'name' => $sName , 'summary' => $sSummary , 'type' => $sType );

                if ( $this->oOrdinances->update( $aData , $iOrdinanceId ) )
                {
                    $sLink = SiteConstants::$SITE . $this->view->url( array( 'controller' => 'legislation' , 'action' => 'view' , 'id' => $iOrdinanceId ) , null , true );

                    if ( $this->oEmails->ordinanceChanged(
                                    $oUser , $oUserAccData , $this->oOrdinances->getOrdinance( $iOrdinanceId ) , $sLink ) )
                    {
                        $sMessage = "Content has been successfully updated!<br>An email is sent to the councilors and website administrators.";
                    }
                    else
                    {
                        $sMessage = "Submission Failed. Failed to update database.";
                    }
                }
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->_request->isGet() )
        {
            $iOrdinanceId = $this->_request->getParam( 'id' );
            if ( $iOrdinanceId )
            {
                $oOrdinance = $this->oOrdinances->getOrdinance( $iOrdinanceId );
                $oForm->populate( array( 'id' => $iOrdinanceId , 'name' => $oOrdinance->name , 'summary' => $oOrdinance->summary ) );
            }
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    public function deletebuprocAction()
    {
        $this->disableLayout();

        $legislationId = ( int ) $this->getRequest()->getPost( 'id' );

        if ( $this->oOrdinances->delete( $legislationId ) )
        {
            $this->view->result = true;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->message = "Cannot delete data.";
        }
    }

    public function deletebuprocfileAction()
    {
        $this->disableLayout();

        $fileId = ( int ) $this->getRequest()->getPost( 'file_id' );
        $ordinanceId = ( int ) $this->getRequest()->getPost( 'ordinance_id' );
        $fileName = $this->getRequest()->getPost( 'filename' );

        if ( $this->oOrdinances->deleteFile( $fileId , $ordinanceId , $fileName ) )
        {
            $this->view->result = true;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->message = "Cannot delete data.";
        }
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

    public function deletefileAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $iFileId = ( int ) $this->getRequest()->getPost( 'id' );
        $this->view->list = "files-" . $iFileId;
        if ( $this->oOrdinances->deletefile( $iFileId ) )
        {
            $this->view->result = true;
            $this->view->id = $iFileId;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $iFileId;
            $this->view->message = "Cannot delete data.";
        }
    }

    public function viewAction()
    {
        if ( $this->getRequest()->isGet() )
        {
            $userData = $this->_helper->_aclHelper->getCurrentUserData();
            $roleId = $userData[ 'role_id' ];
            $userId = $userData[ 'sys_user_id' ];
            $this->view->is_logged = $roleId === SiteConstants::$GUESTROLE_ID ? false : true;

            $ordinanceId = ( int ) $this->getRequest()->getParam( 'id' );
            $ordinance = $this->oOrdinances->getOrdinance( $ordinanceId );
            $this->view->ordinance = $ordinance;

            $this->setMenuItemFromStatus( $ordinance->status );

            $ordinancesSignedByCurrentUser = $this->oOrdinances->getApprovedOrdinance( $userData[ 'sys_user_id' ] , $ordinanceId );
            $isAlreadyApproved = !is_null( $ordinancesSignedByCurrentUser ) && !empty( $ordinancesSignedByCurrentUser );
            $this->view->alreadyApproved = $isAlreadyApproved;

            $ordinanceApprovals = $this->oOrdinances->getOrdinanceApprovalsFromOrdinanceId( $ordinanceId );
            $this->view->approvals = $ordinanceApprovals;

            if ( $ordinance->status === SiteConstants::$PUBLISHED )
            {
                $this->view->showDownloadButton = true;
                $this->view->showEditButton = false;
                $this->view->showPublishButton = false;
                $this->view->showApproveButton = false;
            }
            else
            {
                $superuserId = ( int ) $this->oUsers->getSingleSuperuser();
                $superadminId = ( int ) $this->oUsers->getSingleSuperadmin();

                $this->view->showDownloadButton = false;
                $this->view->showEditButton = $this->checkEditAllowed( $roleId ) && !$this->oOrdinances->checkIfAlreadySigned( $ordinanceId );
                $this->view->showPublishButton = $this->checkPublishAllowed( $roleId ) && $this->oOrdinances->checkIfAlreadySigned( $ordinanceId , $superuserId );
                $this->view->showApproveButton = $this->oOrdinances->isApproveButtonEnabledForSingleOrdinance( $this->checkApproveAllowed( $roleId ) , $isAlreadyApproved , $ordinanceId , $roleId , $superuserId , $superadminId );
            }

            $councilorsInfoWithSignature = $this->oUsers->getUserInfoWithSignature( SiteConstants::$COUNCILOR_ID );
            $filteredCouncilors = $this->oOrdinances->filterSignatures( $councilorsInfoWithSignature , $ordinanceApprovals );
            $this->view->councilors = $filteredCouncilors;
            $this->view->councilors1 = $this->oOrdinances->getCouncilorsByDistrict( $filteredCouncilors , '1' );
            $this->view->councilors2 = $this->oOrdinances->getCouncilorsByDistrict( $filteredCouncilors , '2' );

            $superuserInfoWithSignature = $this->oUsers->getUserInfoWithSignature( SiteConstants::$SUPERUSER_ID );
            $filteredSuperusers = $this->oOrdinances->filterSignatures( $superuserInfoWithSignature , $ordinanceApprovals );
            $this->view->superuser = $filteredSuperusers;

            $superadminInfoWithSignature = $this->oUsers->getUserInfoWithSignature( SiteConstants::$SUPERADMIN_ID );
            $filteredSuperadmins = $this->oOrdinances->filterSignatures( $superadminInfoWithSignature , $ordinanceApprovals );
            $this->view->superadmin = $filteredSuperadmins;

            $this->view->logoURI = $this->oImages->getDataURI( 'img/logo-bacoor.png' );

            $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
            $this->view->ordinancesside = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
            $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'polls' , 'vote' );
            $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $userId );
            $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
            $this->view->poll = $aFeaturedPoll;
        }
    }

    private function filterSignatures( $users , $approvals )
    {
        $userIds = $this->oOrdinances->getColumnValues( $approvals , 'user_id' );
        for ( $i = 0; $i < count( $users ); $i++ )
        {
            if ( !in_array( $users[ $i ][ 'id' ] , $userIds ) )
            {
                $users[ $i ][ 'signature' ] = "";
                $users[ $i ][ 'datauri' ] = "";
                $users[ $i ][ 'datauriwidth' ] = "";
                $users[ $i ][ 'datauriheight' ] = "";
            }
        }
        return $users;
    }

    private function setMenuItemFromStatus( $status )
    {
        if ( $status === SiteConstants::$PUBLISHED )
        {
            $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$DOWNLOADS_MENUITEM );
            $this->view->category = 'DOWNLOADS';
        }
        else
        {
            $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$PENDING_MENUITEM );
            $this->view->category = 'PENDING LEGISLATION';
        }
    }

    public function approveAction()
    {
        $this->disableLayout();

        $user = $this->_helper->_aclHelper->getCurrentUserData();
        $personalData = $this->oUsers->getPersonalData( $user[ 'sys_user_id' ] );
        $accountData = $this->oUsers->getAccountData( $user[ 'id' ] );

        $ordinanceId = ( int ) $this->getRequest()->getPost( 'id' );
        $approvalId = $this->oOrdinances->addApproval( array( 'ordinance_id' => $ordinanceId , 'sys_user_id' => $user[ 'sys_user_id' ] ) );

        if ( $approvalId )
        {
            $link = $this->getLink( 'legislation' , 'view' , $ordinanceId );
            $ordinance = $this->oOrdinances->getOrdinance( $ordinanceId );

            $isApprovedEmailSent = $this->oEmails->ordinanceApproved( $personalData , $user[ 'role_id' ] , $ordinance , $link );
            $isOrdinanceSignedByAllCouncilors = $this->allCouncilorsSignedLegislation( $ordinanceId );

            if ( $isApprovedEmailSent && (!$isOrdinanceSignedByAllCouncilors || $isOrdinanceSignedByAllCouncilors &&
                    $this->oEmails->ordinanceStaged( $personalData , $user[ 'role_id' ] , $ordinance , $link ) ) )
            {
                $this->view->result = true;
                $this->view->approvalId = $approvalId;
                $this->view->personalData = $personalData->toArray();
                $this->view->accountData = $accountData->toArray();
                $this->view->councilors = $this->getCouncilorApprovals( $ordinanceId );
                $this->view->superusers = $this->getSuperuserApprovals( $ordinanceId );
                $this->view->superadmins = $this->getSuperadminApprovals( $ordinanceId );
                $this->view->message = "<h4>You have already signed this ordinance.</h4>";
            }
            else
            {
                $this->view->result = false;
                $this->view->message = "<h4>Cannot approve ordinance.</h4>";
            }
        }
        else
        {
            $this->view->result = false;
            $this->view->message = "<h4>Cannot approve ordinance.</h4>";
        }
    }

    public function newpublishAction()
    {
        $this->disableLayout();

        $user = $this->_helper->_aclHelper->getCurrentUserData();
        $personalData = $this->oUsers->getPersonalData( $user[ 'sys_user_id' ] );
        $accountData = $this->oUsers->getAccountData( $user[ 'id' ] );

        $ordinanceId = ( int ) $this->getRequest()->getPost( 'id' );
        $publishId = $this->oOrdinances->addPublish( array( 'ordinance_id' => $ordinanceId , 'sys_user_id' => $user[ 'sys_user_id' ] ) );
        if ( $publishId )
        {
            $link = $this->getLink( 'legislation' , 'view' , $ordinanceId );
            $ordinance = $this->oOrdinances->getOrdinance( $ordinanceId );

            $isApprovedEmailSent = $this->oEmails->ordinanceApproved( $personalData , $user[ 'role_id' ] , $ordinance , $link );
            $isOrdinanceSignedByAllCouncilors = $this->allCouncilorsSignedLegislation( $ordinanceId );

            if ( $isApprovedEmailSent && (!$isOrdinanceSignedByAllCouncilors || $isOrdinanceSignedByAllCouncilors &&
                    $this->oEmails->ordinanceStaged( $personalData , $user[ 'role_id' ] , $ordinance , $link ) ) )
            {
                $this->view->result = true;
                $this->view->approvalId = $publishId;
                $this->view->personalData = $personalData->toArray();
                $this->view->accountData = $accountData->toArray();
                $this->view->councilors = $this->getCouncilorApprovals( $ordinanceId );
                $this->view->superusers = $this->getSuperuserApprovals( $ordinanceId );
                $this->view->superadmins = $this->getSuperadminApprovals( $ordinanceId );
                $this->view->message = "<h4>You have already signed this ordinance.</h4>";
            }
            else
            {
                $this->view->result = false;
                $this->view->message = "<h4>Cannot approve ordinance.</h4>";
            }
        }
        else
        {
            $this->view->result = false;
            $this->view->message = "<h4>Cannot approve ordinance.</h4>";
        }
    }

    public function publishAction()
    {
        $this->disableLayout();

        $userData = $this->_helper->_aclHelper->getCurrentUserData();
        $ordinanceId = ( int ) $this->getRequest()->getPost( 'id' );
        $publishId = $this->oOrdinances->publish( array( 'ordinance_id' => $ordinanceId , 'sys_user_id' => $userData[ 'sys_user_id' ] ) );

        if ( $publishId )
        {
            $this->view->result = true;
            $this->view->id = $ordinanceId;
            $this->view->messagecontainer = 'appr-mess-' . $ordinanceId;
            $this->view->message = "<h4>You have already signed this ordinance.</h4>";
            $oUser = $this->oUsers->getPersonalData( $sUserId );
            $this->view->user = $oUser->toArray();
        }
        else
        {
            $this->view->result = false;
        }
    }

    public function approveitemAction()
    {
        $this->disableLayout();

        $user = $this->_helper->_aclHelper->getCurrentUserData();
        $personalData = $this->oUsers->getPersonalData( $user[ 'sys_user_id' ] );

        $ordinanceId = ( int ) $this->getRequest()->getPost( 'id' );
        $approvalId = $this->oOrdinances->addApproval( array( 'ordinance_id' => $ordinanceId , 'sys_user_id' => $user[ 'sys_user_id' ] ) );
        if ( $approvalId )
        {
            $approvals = $this->oOrdinances->getOrdinanceApprovalsByOrdinanceId( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' , $ordinanceId );
            $finalApprovals = $this->oOrdinances->getApprovalsArray( $approvals );
            $approvalText = $this->oOrdinances->getStatus( $finalApprovals[ $ordinanceId ] );

            $link = $this->getLink( 'legislation' , 'view' , $ordinanceId );
            $ordinance = $this->oOrdinances->getOrdinance( $ordinanceId );

            $isApprovedEmailSent = $this->oEmails->ordinanceApproved( $personalData , $user[ 'role_id' ] , $ordinance , $link );
            $isOrdinanceSignedByAllCouncilors = $this->allCouncilorsSignedLegislation( $ordinanceId );

            if ( $isApprovedEmailSent && (!$isOrdinanceSignedByAllCouncilors || $isOrdinanceSignedByAllCouncilors &&
                    $this->oEmails->ordinanceStaged( $personalData , $user[ 'role_id' ] , $ordinance , $link ) ) )
            {
                $this->view->result = true;
                $this->view->approvalText = $approvalText;
                $this->view->message = "<h4>You have already signed this ordinance.</h4>";
            }
            else
            {
                $this->view->result = false;
                $this->view->message = "<h4>Cannot approve ordinance.</h4>";
            }
        }
        else
        {
            $this->view->result = false;
            $this->view->message = "<h4>Cannot approve ordinance.</h4>";
        }
    }

    public function newpublishitemAction()
    {
        $this->disableLayout();

        $user = $this->_helper->_aclHelper->getCurrentUserData();
        $personalData = $this->oUsers->getPersonalData( $user[ 'sys_user_id' ] );

        $ordinanceId = ( int ) $this->getRequest()->getPost( 'id' );
        $approvalId = $this->oOrdinances->addPublish( array( 'ordinance_id' => $ordinanceId , 'sys_user_id' => $user[ 'sys_user_id' ] ) );

        if ( $approvalId )
        {
            $link = $this->getLink( 'legislation' , 'view' , $ordinanceId );
            $ordinance = $this->oOrdinances->getOrdinance( $ordinanceId );

            $isApprovedEmailSent = $this->oEmails->ordinanceApproved( $personalData , $user[ 'role_id' ] , $ordinance , $link );
            $isOrdinanceSignedByAllCouncilors = $this->allCouncilorsSignedLegislation( $ordinanceId );

            if ( $isApprovedEmailSent && (!$isOrdinanceSignedByAllCouncilors || $isOrdinanceSignedByAllCouncilors &&
                    $this->oEmails->ordinanceStaged( $personalData , $user[ 'role_id' ] , $ordinance , $link ) ) )
            {
                $this->view->result = true;
                $this->view->message = "<h4>You have already signed this ordinance.</h4>";
            }
            else
            {
                $this->view->result = false;
                $this->view->message = "<h4>Cannot approve ordinance.</h4>";
            }
        }
        else
        {
            $this->view->result = false;
            $this->view->message = "<h4>Cannot approve ordinance.</h4>";
        }
    }

    private function checkDeleteAllowed( $sRoleId )
    {
        $bDeleteAllowed = false;

        if ( $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'legislation' , 'delete' ) )
        {
            $bDeleteAllowed = true;
        }
        return $bDeleteAllowed;
    }

    private function checkApproveAllowed( $sRoleId )
    {
        $bApproveAllowed = false;

        if ( $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'legislation' , 'approve' ) )
        {
            $bApproveAllowed = true;
        }
        return $bApproveAllowed;
    }

    private function checkPublishAllowed( $sRoleId )
    {
        $bPublishAllowed = false;

        if ( $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'legislation' , 'newpublish' ) )
        {
            $bPublishAllowed = true;
        }
        return $bPublishAllowed;
    }

    private function checkAddAllowed( $sRoleId )
    {
        $bAddAllowed = false;

        if ( $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'legislation' , 'add' ) )
        {
            $bAddAllowed = true;
        }
        return $bAddAllowed;
    }

    private function checkEditAllowed( $sRoleId )
    {
        $bEditAllowed = false;

        if ( $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'legislation' , 'edit' ) )
        {
            $bEditAllowed = true;
        }
        return $bEditAllowed;
    }

    private function allCouncilorsSignedLegislation( $iOrId )
    {
        if ( $iOrId )
        {
            $aCouncilors = $this->oUsers->getCouncilorsDefaultPDO();
            $aCouncilorsIds = array();
            foreach ( $aCouncilors as $councilor )
            {
                $aCouncilorsIds[] = $councilor[ 'id' ];
            }
            $aApprovals = $this->oOrdinances->getOrdinanceApprovalsFromOrdinanceId( $iOrId );

            if ( count( $aCouncilors ) !== count( $aApprovals ) )
            {
                return false;
            }

            foreach ( $aApprovals as $approval )
            {
                if ( !in_array( $approval[ 'user_id' ] , $aCouncilorsIds ) )
                {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    private function getCouncilorApprovals( $ordinanceId )
    {
        $result = array();
        $councilors = $this->oUsers->getCouncilors();
        $approvals = $this->oOrdinances->getOrdinanceApprovalsFromOrdinanceId( $ordinanceId );

        foreach ( $approvals as $approval )
        {
            if ( in_array( $approval[ 'user_id' ] , $councilors ) )
            {
                $result[] = $approval;
            }
        }
        return $result;
    }

    private function getSuperuserApprovals( $ordinanceId )
    {
        $result = array();
        $superusers = $this->oUsers->getSuperusers();
        $approvals = $this->oOrdinances->getOrdinanceApprovalsFromOrdinanceId( $ordinanceId );
        foreach ( $approvals as $approval )
        {
            if ( in_array( $approval[ 'user_id' ] , $superusers ) )
            {
                $result[] = $approval;
            }
        }
        return $result;
    }

    private function getSuperadminApprovals( $ordinanceId )
    {
        $result = array();
        $superadmins = $this->oUsers->getSuperadmins();
        $approvals = $this->oOrdinances->getOrdinanceApprovalsFromOrdinanceId( $ordinanceId );
        foreach ( $approvals as $approval )
        {
            if ( in_array( $approval[ 'user_id' ] , $superadmins ) )
            {
                $result[] = $approval;
            }
        }
        return $result;
    }

    private function disableLayout()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );
    }

    private function getLink( $controller , $action , $id )
    {
        return SiteConstants::$SITE . $this->view->url( array( 'controller' => $controller , 'action' => $action , 'id' => $id ) , null , true );
    }

}
