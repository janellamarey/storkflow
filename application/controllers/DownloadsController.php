<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Ordinances' );
Zend_Loader::loadClass( 'Users' );
Zend_Loader::loadClass( 'Images' );
Zend_Loader::loadClass( 'Polls' );

class DownloadsController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'ordinances' , 'resolutions' , 'budgets' , 'procurements' , 'pdf' ) );

        $this->_helper->_aclHelper->addResource( 'polls' );
        $this->_helper->_aclHelper->allowAll( 'polls' , SiteConstants::$COUNCILOR , 'vote' );

        $this->_helper->_aclHelper->addResource( 'legislation' );
        $this->_helper->_aclHelper->allowAll( 'legislation' , SiteConstants::$ADMIN , array( 'addbuproc' , 'editbuproc' , 'deletebuproc' ) );
        $this->_helper->_aclHelper->denyAll( 'legislation' , SiteConstants::$SUPERADMIN , array( 'addbuproc' , 'editbuproc' , 'deletebuproc' ) );

        $this->oOrdinances = new Ordinances();
        $this->oUsers = new Users();
        $this->oImages = new Images();
        $this->oPolls = new Polls();

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$DOWNLOADS_MENUITEM );
    }

    public function ordinancesAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sUserId = $aUserData[ 'sys_user_id' ];
        $sRoleId = $aUserData[ 'sys_role_id' ];
        $this->view->is_logged = $sRoleId === SiteConstants::$GUESTROLE_ID ? false : true;

        $aOrdinances = $this->oOrdinances->getOrdinances( 0 , 0 , 'PUBLISHED' , 'ORDINANCE' );
        $aApprovedOrdinances = $this->oOrdinances->getIdArray( $this->oOrdinances->getApprovedOrdinances( $sUserId , 'PUBLISHED' ) );
        $aFinalOrdinances = $this->oOrdinances->getOrdinancesArray( $aOrdinances , $aApprovedOrdinances );

        $aOrdinancesUsers = $this->oOrdinances->getOrdinanceApprovalsAll( 'PUBLISHED' , 'ORDINANCE' );
        $aFinalOrdinanceUsers = $this->oOrdinances->getApprovalsArray( $aOrdinancesUsers );
        foreach ( $aFinalOrdinanceUsers as $value )
        {
            $aFinalOrdinances[ $value[ 0 ][ 'or_id' ] ][ 'approvals' ] = $value;
        }
        $this->view->ordinances = $aFinalOrdinances;
        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinancesside = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
        $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'polls' , 'vote' );
        $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $sUserId );
        $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
        $this->view->poll = $aFeaturedPoll;
    }

    public function resolutionsAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sUserId = $aUserData[ 'sys_user_id' ];
        $sRoleId = $aUserData[ 'sys_role_id' ];
        $this->view->is_logged = $sRoleId === SiteConstants::$GUESTROLE_ID ? false : true;

        $aOrdinances = $this->oOrdinances->getOrdinances( 0 , 0 , 'PUBLISHED' , 'RESOLUTION' );
        $aOrdinancesUsers = $this->oOrdinances->getOrdinanceApprovalsAll( 'PUBLISHED' , 'RESOLUTION' );
        $aApprovedOrdinances = $this->oOrdinances->getIdArray( $this->oOrdinances->getApprovedOrdinances( $sUserId , 'PUBLISHED' , 'RESOLUTION' ) );

        $aFinalOrdinances = $this->oOrdinances->getOrdinancesArray( $aOrdinances , $aApprovedOrdinances );
        $aFinalOrdinanceUsers = $this->oOrdinances->getApprovalsArray( $aOrdinancesUsers );
        foreach ( $aFinalOrdinanceUsers as $value )
        {
            $aFinalOrdinances[ $value[ 0 ][ 'or_id' ] ][ 'approvals' ] = $value;
        }

        $this->view->ordinances = $aFinalOrdinances;
        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinancesside = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
        $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'polls' , 'vote' );
        $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $sUserId );
        $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
        $this->view->poll = $aFeaturedPoll;
    }

    public function budgetsAction()
    {
        $userData = $this->_helper->_aclHelper->getCurrentUserData();
        $userId = $userData[ 'sys_user_id' ];
        $roleId = $userData[ 'role_id' ];
        $this->view->is_logged = $roleId === SiteConstants::$GUESTROLE_ID ? false : true;

        $ordinances = $this->oOrdinances->getOrdinances( 0 , 0 , 'PUBLISHED' , 'BUDGET' );
        $isEditingAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'legislation' , 'editbuproc' );
        $isDeletingAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'legislation' , 'deletebuproc' );
        $finalOrdinances = $this->oOrdinances->getBuProcArray( $ordinances , $isEditingAllowed , $isDeletingAllowed );

        $ordinanceFiles = $this->oOrdinances->getFiles();

        $ordinanceFilesArray = $this->oOrdinances->getFilesArray( $ordinanceFiles );

        foreach ( $ordinanceFilesArray as $value )
        {
            if ( array_key_exists( $value[ 0 ][ 'ordinance_id' ] , $finalOrdinances ) )
            {
                $finalOrdinances[ $value[ 0 ][ 'ordinance_id' ] ][ 'files' ] = $value;
            }
        }

        $this->view->showAddBuProcButton = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'legislation' , 'addbuproc' );

        $this->view->ordinances = $finalOrdinances;
        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinancesside = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
        $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'polls' , 'vote' );
        $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $userId );
        $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
        $this->view->poll = $aFeaturedPoll;
    }

    public function procurementsAction()
    {
        $userData = $this->_helper->_aclHelper->getCurrentUserData();
        $userId = $userData[ 'sys_user_id' ];
        $roleId = $userData[ 'role_id' ];
        $this->view->is_logged = $roleId === SiteConstants::$GUESTROLE_ID ? false : true;

        $ordinances = $this->oOrdinances->getOrdinances( 0 , 0 , 'PUBLISHED' , 'PROCUREMENT' );

        $isEditingAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'legislation' , 'editbuproc' );
        $isDeletingAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'legislation' , 'deletebuproc' );
        $finalOrdinances = $this->oOrdinances->getBuProcArray( $ordinances , $isEditingAllowed , $isDeletingAllowed );

        $ordinanceFiles = $this->oOrdinances->getFiles();

        $ordinanceFilesArray = $this->oOrdinances->getFilesArray( $ordinanceFiles );

        foreach ( $ordinanceFilesArray as $value )
        {
            if ( array_key_exists( $value[ 0 ][ 'ordinance_id' ] , $finalOrdinances ) )
            {
                $finalOrdinances[ $value[ 0 ][ 'ordinance_id' ] ][ 'files' ] = $value;
            }
        }
        $this->view->showAddBuProcButton = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'legislation' , 'addbuproc' );

        $this->view->ordinances = $finalOrdinances;
        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinancesside = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
        $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'polls' , 'vote' );
        $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $userId );
        $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
        $this->view->poll = $aFeaturedPoll;
    }

    public function pdfAction()
    {
        if ( $this->getRequest()->isGet() )
        {
            $iOrId = ( int ) $this->getRequest()->getParam( 'id' );
            $oOrdinance = $this->oOrdinances->getOrdinance( $iOrId );
            $this->view->ordinance = $oOrdinance;
            $this->view->councilors = $this->oUsers->getCouncilorsInfo();
            $this->view->logoURI = $this->oImages->getDataURI( 'img/logo-bacoor.png' );
        }
    }

}
