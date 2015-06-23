<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Users
 *
 * @author Josh
 */
Zend_Loader::loadClass( 'DB_Users' );
Zend_Loader::loadClass( 'DB_UserRoles' );
Zend_Loader::loadClass( 'DB_Roles' );
Zend_Loader::loadClass( 'SiteConstants' );

class Users
{

    const QUERY_EMAILS = "
                    SELECT 
                            sys_users.email_add
                    FROM 
                            sys_user_roles
                            LEFT JOIN sys_users
                            ON sys_user_roles.sys_user_id = sys_users.id
                    WHERE
                            sys_user_roles.deleted = 0
                            AND sys_users.deleted = 0
                            AND sys_users.email_add <> ''
                            AND CONCAT( 'role', sys_user_roles.sys_role_id ) = ?";
    const QUERY_EMAILS_NO_ROLES = "
                SELECT 
                        sys_users.email_add AS email,
                        TRIM(CONCAT(sys_users.firstname,' ',sys_users.lastname,' ',sys_users.mi, ' ', sys_users.designation)) AS name,
                        sys_user_roles.pwc AS pwc,
                        sys_user_roles.date_created AS date_created
                FROM 
                        sys_user_roles
                        LEFT JOIN sys_users
                        ON sys_user_roles.sys_user_id = sys_users.id
                WHERE
                        sys_user_roles.deleted = 0
                        AND sys_users.deleted = 0
                        AND sys_users.email_add <> ''";
    const QUERY_ALL_COUNCIL = "
                    SELECT 
                            sys_users.id
                    FROM 
                            sys_user_roles
                            LEFT JOIN sys_users
                            ON sys_user_roles.sys_user_id = sys_users.id
                    WHERE
                            sys_user_roles.deleted = 0
                            AND sys_users.deleted = 0
                            AND sys_user_roles.sys_role_id <> 4";
    const QUERY_ALL_COUNCILORS = "
                    SELECT 
                            sys_users.id
                    FROM 
                            sys_user_roles
                            LEFT JOIN sys_users
                            ON sys_user_roles.sys_user_id = sys_users.id
                    WHERE
                            sys_user_roles.deleted = 0
                            AND sys_users.deleted = 0
                            AND sys_user_roles.sys_role_id = 5";
    const QUERY_USERS = "
                    SELECT 
                            sys_users.id
                    FROM 
                            sys_user_roles
                            LEFT JOIN sys_users
                            ON sys_user_roles.sys_user_id = sys_users.id
                    WHERE
                            sys_user_roles.deleted = 0
                            AND sys_users.deleted = 0
                            AND sys_user_roles.sys_role_id = ?";
    const QUERY_COUNCILORS_WITH_SIGNATURE = "
                    SELECT 
                            sys_users.id AS id,
                            IF( sys_users.mi='',CONCAT(sys_users.firstname,' ', sys_users.lastname),CONCAT(sys_users.firstname,' ', sys_users.mi ,'. ', sys_users.lastname)) AS name,
                            sys_users.signature AS signature,
                            sys_users.district AS district
                    FROM 
                            sys_user_roles
                            LEFT JOIN sys_users
                            ON sys_user_roles.sys_user_id = sys_users.id
                    WHERE
                            sys_user_roles.deleted = 0
                            AND sys_users.deleted = 0
                            AND sys_user_roles.sys_role_id = 5
                    ORDER BY sys_users.id";
    const QUERY_USERS_WITH_SIGNATURE = "
                    SELECT 
                            sys_users.id AS id,
                            IF( sys_users.mi='',CONCAT(sys_users.firstname,' ', sys_users.lastname),CONCAT(sys_users.firstname,' ', sys_users.mi ,'. ', sys_users.lastname)) AS name,
                            sys_users.signature AS signature,
                            sys_users.district AS district
                    FROM 
                            sys_user_roles
                            LEFT JOIN sys_users
                            ON sys_user_roles.sys_user_id = sys_users.id
                    WHERE
                            sys_user_roles.deleted = 0
                            AND sys_users.deleted = 0
                            AND sys_user_roles.sys_role_id = ";
    const QUERY_ALL_NEW_USERS = "
                    SELECT 
                            sys_users.id,
                            sys_users.firstname,
                            sys_users.lastname,
                            sys_users.mi,
                            sys_users.designation,
                            sys_users.email_add,
                            sys_users.contacts,
                            sys_users.date_created,
                            sys_users.user_created,
                            sys_users.date_last_modified,
                            sys_users.user_last_modified,
                            sys_user_roles.status
                    FROM 
                            sys_user_roles
                            LEFT JOIN sys_users
                            ON sys_user_roles.sys_user_id = sys_users.id
                    WHERE
                            sys_user_roles.deleted = 0
                            AND sys_users.deleted = 0
                            AND sys_user_roles.status = 'NEW'";
    const QUERY_ALL_REGISTERED_USERS = "
                    SELECT 
                            sys_users.id,
                            sys_users.firstname,
                            sys_users.lastname,
                            sys_users.mi,
                            sys_users.designation,
                            sys_users.email_add,
                            sys_users.contacts,
                            sys_users.date_created,
                            sys_users.user_created,
                            sys_users.date_last_modified,
                            sys_users.user_last_modified,
                            sys_user_roles.status
                    FROM 
                            sys_user_roles
                            LEFT JOIN sys_users
                            ON sys_user_roles.sys_user_id = sys_users.id
                    WHERE
                            sys_user_roles.deleted = 0
                            AND sys_users.deleted = 0
                            AND sys_user_roles.status = 'REG'";

    public function __construct()
    {
        $this->newuser = new DB_Sysusers();

        $this->oDb = Zend_Registry::get( 'db' );
        $this->oUsers = new DB_Users();
        $this->oUserRoles = new DB_UserRoles();
        $this->oRoles = new DB_Roles();
        $this->oImages = new Images();
        $this->oUploader = new Uploader();

        $this->appconfig = Zend_Registry::get( 'config' );
        $this->uploadfolder = $this->appconfig->upload->root->users;

        $aclHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'AclHelper' );
        $userData = $aclHelper->getCurrentUserData();
        $this->curRoleId = $userData[ 'sys_role_id' ];
        $this->curUserId = $userData[ 'sys_user_id' ];
    }

    public function updateAccountData( $iUserRoleId , $oPost )
    {
        $sUsername = $oPost[ 'username' ];
        $sPassword = $oPost[ 'password' ];

        $aAccountData = array(
                'username' => $sUsername ,
                'password' => $sPassword
        );
        return $this->oUserRoles->updateData( $aAccountData , $iUserRoleId );
    }

    public function updatePrivilege( $iUserRoleId , $oPost )
    {
        $iRoleId = $oPost[ 'sys_role_id' ];
        $aAccountData = array(
                'sys_role_id' => $iRoleId
        );
        return $this->oUserRoles->updateData( $aAccountData , $iUserRoleId );
    }

    public function updatePersonalData( $iUserId , $oPost )
    {

        $sLastname = $oPost[ 'lastname' ];
        $sFirstname = $oPost[ 'firstname' ];
        $sAddress = $oPost[ 'address' ];
        $sContact = $oPost[ 'contacts' ];
        $sEmail = $oPost[ 'email_add' ];
        $sDistrict = $oPost[ 'district' ];
        $sMI = $oPost[ 'mi' ];
        $sDesignation = $oPost[ 'designation' ];

        $aPersonalData = array(
                'lastname' => $sLastname ,
                'firstname' => $sFirstname ,
                'address' => $sAddress ,
                'contacts' => $sContact ,
                'email_add' => $sEmail ,
                'district' => $sDistrict ,
                'mi' => $sMI ,
                'designation' => $sDesignation
        );

        return $this->oUsers->updateData( $aPersonalData , $iUserId );
    }

    public function addNewUser( array $oPost )
    {
        $iUserId = $this->addNewPersonalData( $oPost );

        if ( $iUserId )
        {
            $iUserRoleId = $this->addNewAccountData( $iUserId , $oPost );
            if ( $iUserRoleId )
            {
                return $iUserId;
            }
        }
        return 0;
    }

    public function addNewPersonalData( $oPost )
    {
        $users = new DB_Sysusers();
        $user = $users->createRow();
        $user->lastname = $oPost[ 'lastname' ];
        $user->firstname = $oPost[ 'firstname' ];
        $user->address = $oPost[ 'address' ];
        $user->contacts = $oPost[ 'contact' ];
        $user->email_add = $oPost[ 'emailadd' ];
        $user->district = $oPost[ 'districts' ];
        $user->mi = $oPost[ 'mi' ];
        $user->designation = $oPost[ 'designation' ];
        if ( ( int ) $oPost[ 'roles' ] === SiteConstants::$VOTER_ID )
        {
            $user->searchable = 1;
        }

        return $user->save();
    }

    public function addNewAccountData( $iUserId , $oPost )
    {
        $sUsername = $oPost[ 'username' ];
        $sPassword = $oPost[ 'password' ];
        $iRoleId = $oPost[ 'roles' ];

        $sStatus = SiteConstants::$NEW;
        if ( $iRoleId === SiteConstants::$ADMIN_ID )
        {
            $sStatus = SiteConstants::$REG;
        }
        $aAccountData = array(
                'sys_user_id' => $iUserId ,
                'sys_role_id' => $iRoleId ,
                'username' => $sUsername ,
                'password' => $sPassword ,
                'status' => $sStatus
        );

        return $this->oUserRoles->insertData( $aAccountData );
    }

    public function addSignature( $iUserId , array $aFiles )
    {
        if ( !empty( $aFiles[ 'signature' ] ) )
        {
            $sPath = $aFiles[ 'signature' ][ 'name' ];
            $sExt = pathinfo( $sPath , PATHINFO_EXTENSION );
            $sFilename = "sig." . $sExt;

            $this->makeFolder( $this->uploadfolder . DIRECTORY_SEPARATOR . $iUserId );
            $sDestPath = $this->uploadfolder . DIRECTORY_SEPARATOR . $iUserId . DIRECTORY_SEPARATOR . $sFilename;
            $sLastModified = file_exists( $sDestPath ) ? filemtime( $sDestPath ) : time();

            $aErrorMessages = $this->oUploader->uploadSignature( $sDestPath , $aFiles[ 'signature' ] );
            $this->insertSignatureToDB( $aErrorMessages , $sFilename . '?' . $sLastModified , $iUserId );
            return $aErrorMessages;
        }
        return array( 'No signature added' );
    }

    private function makeFolder( $location )
    {
        if ( !file_exists( $location ) )
        {
            mkdir( $location );
        }
    }

    private function insertSignatureToDB( $aErrorMessages , $sFilename , $iUserId )
    {
        if ( empty( $aErrorMessages ) )
        {
            $aSignatureData = array(
                    'signature' => $sFilename
            );
            $this->oUsers->updateData( $aSignatureData , $iUserId );
        }
    }

    public function updatePasswordData( $iUserId , $sPassword )
    {
        $aAccountData = array(
                'password' => $sPassword
        );

        return $this->oUserRoles->updateData( $aAccountData , $iUserId );
    }

    public function updateStatusData( $iUserId , $sStatus )
    {
        $aData = array(
                'status' => $sStatus
        );
        $aAccountData = $this->getAccountDataFromUserId( $iUserId );
        return $this->oUserRoles->updateData( $aData , $aAccountData[ 'id' ] );
    }

    public function updatePasswordChangeEmailRecurrence( $iUserId , $sPwc )
    {
        $aAccountData = array(
                'pwc' => $sPwc
        );
        return $this->oUserRoles->updateData( $aAccountData , $iUserId );
    }

    public function getRoles()
    {
        return $this->oRoles->toArray();
    }

    public function getPersonalData( $iUserId )
    {
        return $this->oUsers->getRowObject( $iUserId );
    }

    public function getPersonalDataFromEmail( $sEmail )
    {
        return $this->oUsers->getRowObjectFromEmail( $sEmail );
    }

    public function getAccountData( $iUserRoleId )
    {
        return $this->oUserRoles->getRowObject( $iUserRoleId );
    }

    public function getAccountDataFromUserId( $iUserId )
    {
        return $this->oUserRoles->getRowObjectFromUserId( $iUserId );
    }

    public function getRoleData( $iRoleId )
    {
        return $this->oRoles->getRowObject( $iRoleId );
    }

    public function getActiveEmailAddresses( $sRole )
    {
        return $this->oDb->fetchAll( self::QUERY_EMAILS , array( $sRole ) , PDO::FETCH_COLUMN );
    }

    public function getActiveUsers()
    {
        return $this->oDb->fetchAll( self::QUERY_EMAILS_NO_ROLES );
    }

    public function getUserRoles()
    {
        return $this->oUserRoles->fetchAll( 'deleted=0' );
    }

    public function getCouncil()
    {
        return $this->oDb->fetchAll( self::QUERY_ALL_COUNCIL );
    }

    public function getCouncilors()
    {
        $fetch = $this->oDb->fetchAll( self::QUERY_USERS , SiteConstants::$COUNCILOR_ID , PDO::FETCH_COLUMN );
        return $fetch;
    }

    public function getCouncilorsDefaultPDO()
    {
        $fetch = $this->oDb->fetchAll( self::QUERY_USERS , SiteConstants::$COUNCILOR_ID );
        return $fetch;
    }

    public function getSuperusers()
    {
        $fetch = $this->oDb->fetchAll( self::QUERY_USERS , SiteConstants::$SUPERUSER_ID , PDO::FETCH_COLUMN );
        return $fetch;
    }

    public function getSuperadmins()
    {
        $fetch = $this->oDb->fetchAll( self::QUERY_USERS , SiteConstants::$SUPERADMIN_ID , PDO::FETCH_COLUMN );
        return $fetch;
    }

    public function getSingleSuperuser()
    {
        $aSuperUserRow = $this->getUserInfo( str_replace( 'role' , '' , SiteConstants::$SUPERUSER ) );
        if ( !is_null( $aSuperUserRow ) && !empty( $aSuperUserRow ) && $aSuperUserRow[ 0 ] )
        {
            return $aSuperUserRow[ 0 ][ 'id' ];
        }
        return 0;
    }

    public function getSingleSuperadmin()
    {
        $superadmin = $this->getUserInfo( str_replace( 'role' , '' , SiteConstants::$SUPERADMIN ) );
        if ( !is_null( $superadmin ) && !empty( $superadmin ) && $superadmin[ 0 ] )
        {
            return $superadmin[ 0 ][ 'id' ];
        }
        return 0;
    }

    public function getUserInfo( $sRoleId )
    {
        return $this->oDb->fetchAll( self::QUERY_USERS , array( ( int ) $sRoleId ) );
    }

    public function getNewUsers()
    {
        return $this->oDb->fetchAll( self::QUERY_ALL_NEW_USERS );
    }

    public function getRegisteredUsersExcept( $exceptions = null , $excludeCurrentUser = true )
    {
        $notIncludeCurrent = " AND sys_user_roles.sys_user_id <> " . $this->curUserId;

        if ( is_null( $exceptions ) || !$this->all( $exceptions , 'is_int' ) )
        {
            return array();
        }

        if ( empty( $exceptions ) && $excludeCurrentUser )
        {
            $where = $notIncludeCurrent;
            return $this->oDb->fetchAll( self::QUERY_ALL_REGISTERED_USERS . $where );
        }
        else if ( empty( $exceptions ) )
        {
            return $this->oDb->fetchAll( self::QUERY_ALL_REGISTERED_USERS );
        }

        $excludeRoles = " AND sys_user_roles.sys_role_id NOT IN(" . implode( ',' , $exceptions ) . ")";
        if ( $excludeCurrentUser )
        {
            $where = $excludeRoles . $notIncludeCurrent;
            return $this->oDb->fetchAll( self::QUERY_ALL_REGISTERED_USERS . $where );
        }

        $where = $excludeRoles;
        return $this->oDb->fetchAll( self::QUERY_ALL_REGISTERED_USERS . $where );
    }

    public function getCouncilorsInfo()
    {
        $oRowSet = $this->oDb->fetchAll( self::QUERY_COUNCILORS_WITH_SIGNATURE );
        for ( $i = 0 , $count = count( $oRowSet ); $i < $count; $i++ )
        {
            if ( $oRowSet[ $i ][ 'signature' ] )
            {
                $fileNameNoTimestamp = current( explode( '?' , $oRowSet[ $i ][ 'signature' ] ) );
                $filePath = $this->uploadfolder . DIRECTORY_SEPARATOR . $oRowSet[ $i ][ 'id' ] . '/tiny/' . $fileNameNoTimestamp;

                if ( file_exists( $filePath ) )
                {
                    $oRowSet[ $i ][ 'datauri' ] = $this->oImages->getDataURI( $filePath );
                    $image = imagecreatefrompng( $filePath );
                    $width = imagesx( $image );
                    $height = imagesy( $image );
                    $oRowSet[ $i ][ 'datauriwidth' ] = $width * .50;
                    $oRowSet[ $i ][ 'datauriheight' ] = $height * .50;
                }
                else
                {
                    $oRowSet[ $i ][ 'datauri' ] = '';
                    $oRowSet[ $i ][ 'datauriwidth' ] = 0;
                    $oRowSet[ $i ][ 'datauriheight' ] = 0;
                }
            }
        }
        return $oRowSet;
    }

    public function getUserInfoWithSignature( $sRoleId )
    {
        if ( $sRoleId )
        {
            $sQuery = self::QUERY_USERS_WITH_SIGNATURE . $sRoleId;
            $oRowSet = $this->oDb->fetchAll( $sQuery );

            for ( $i = 0 , $count = count( $oRowSet ); $i < $count; $i++ )
            {
                if ( $oRowSet[ $i ][ 'signature' ] )
                {
                    $fileNameNoTimestamp = current( explode( '?' , $oRowSet[ $i ][ 'signature' ] ) );
                    $filePath = $this->uploadfolder . DIRECTORY_SEPARATOR . $oRowSet[ $i ][ 'id' ] . '/tiny/' . $fileNameNoTimestamp;
                    if ( file_exists( $filePath ) )
                    {
                        $oRowSet[ $i ][ 'datauri' ] = $this->oImages->getDataURI( $filePath );
                        $image = imagecreatefrompng( $filePath );
                        $width = imagesx( $image );
                        $height = imagesy( $image );
                        $oRowSet[ $i ][ 'datauriwidth' ] = $width * .50;
                        $oRowSet[ $i ][ 'datauriheight' ] = $height * .50;
                    }
                    else
                    {
                        $oRowSet[ $i ][ 'datauri' ] = 0;
                        $oRowSet[ $i ][ 'datauriwidth' ] = 0;
                        $oRowSet[ $i ][ 'datauriheight' ] = 0;
                    }
                }
            }
            return $oRowSet;
        }
        return array();
    }

    public function toArray( $aUsers , $bEditAllowed = false , $bDeleteAllowed = false , $bApproveAllowed = false , $bDenyAllowed = false )
    {
        $aFinalUsers = $this->getStructuredArray( $aUsers , $bEditAllowed , $bDeleteAllowed , $bApproveAllowed , $bDenyAllowed );
        if ( count( $aFinalUsers ) > 0 )
        {
            return $aFinalUsers;
        }
        return array();
    }

    private function getStructuredArray( $aUsers , $bEditAllowed = false , $bDeleteAllowed = false , $bApproveAllowed = false , $bDenyAllowed = false )
    {
        $aFinalUsers = array();
        for ( $i = 0 , $count = count( $aUsers ); $i < $count; $i++ )
        {
            $aItem = array();
            $aItem[ 'id' ] = $aUsers[ $i ][ 'id' ];
            $aItem[ 'name' ] = SiteConstants::createName( $aUsers[ $i ][ 'firstname' ] , $aUsers[ $i ][ 'lastname' ] , $aUsers[ $i ][ 'mi' ] , $aUsers[ $i ][ 'designation' ] );
            $aItem[ 'status' ] = $aUsers[ $i ][ 'status' ];
            $aItem[ 'email_add' ] = $aUsers[ $i ][ 'email_add' ];
            $aItem[ 'contacts' ] = $aUsers[ $i ][ 'contacts' ];
            $aItem[ 'date_created' ] = $aUsers[ $i ][ 'date_created' ];
            $aItem[ 'user_created' ] = $aUsers[ $i ][ 'user_created' ];
            $aItem[ 'date_modified' ] = $aUsers[ $i ][ 'date_last_modified' ];
            $aItem[ 'user_modified' ] = $aUsers[ $i ][ 'user_last_modified' ];
            $aItem[ 'edit' ] = $bEditAllowed;
            $aItem[ 'delete' ] = $bDeleteAllowed;
            $aItem[ 'approve' ] = $bApproveAllowed;
            $aItem[ 'deny' ] = $bDenyAllowed;
            $aItem[ 'alreadyapproved' ] = $aUsers[ $i ][ 'status' ] === SiteConstants::$REG;
            $aFinalUsers[ $aUsers[ $i ][ 'id' ] ] = $aItem;
        }
        return $aFinalUsers;
    }

    public function delete( $iUserId )
    {
        $aData = array(
                'deleted' => 1
        );

        $aRow = $this->oUserRoles->getRow( $iUserId );
        $this->oUserRoles->updateData( $aData , $iUserId );
        $this->oUsers->updateData( $aData , $aRow[ 'sys_user_id' ] );

        return true;
    }

    /**
     * Tell whether all members of $array validate the $predicate.
     *
     * all(array(1, 2, 3),   'is_int'); -> true
     * all(array(1, 2, 'a'), 'is_int'); -> false
     */
    public function all( $array , $predicate )
    {
        return array_filter( $array , $predicate ) === $array;
    }

}
