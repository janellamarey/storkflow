<?php

Zend_Loader::loadClass( 'DB_Ordinances' );
Zend_Loader::loadClass( 'DB_OrdinancesFiles' );
Zend_Loader::loadClass( 'Uploader' );
Zend_Loader::loadClass( 'Images' );
Zend_Loader::loadClass( 'Users' );
Zend_Loader::loadClass( 'Sql' );

class Ordinances
{

    public function __construct()
    {
        $this->oDb = Zend_Registry::get( 'db' );
        $this->oOrdinances = new DB_Ordinances();
        $this->oOrdinancesUsers = new DB_OrdinancesUsers();
        $this->oOrdinancesFiles = new DB_OrdinancesFiles();
        $this->oUploader = new Uploader();
        $this->oImages = new Images();
        $this->oUsers = new Users();

        $config = Zend_Registry::get( 'config' );
        $this->pathToDocs = $config->upload->root->ordinances;
    }

    public function add( array $data )
    {
        $legislations = new DB_Legislations();
        $legislation = $legislations->createRow();
        $legislation->name = $data[ 'name' ];
        $legislation->summary = $data[ 'summary' ];
        $legislation->type = $data[ 'type' ];
        $legislation->status = $data[ 'status' ];

        $legislation->searchable = 1;
        return $legislation->save();
    }

    public function addApproval( array $data )
    {
        return $this->oOrdinancesUsers->insertData( $data );
    }

    public function publish( array $data )
    {
        $iPublishId = $this->oOrdinancesUsers->insertData( $data );
        if ( $iPublishId )
        {
            $iOrdinanceId = $data[ 'ordinance_id' ];
            return $this->oOrdinances->updateData( array( 'status' => 'PUBLISHED' ) , $iOrdinanceId );
        }
        return 0;
    }

    public function addPublish( array $data )
    {
        $publishId = $this->oOrdinancesUsers->insertData( $data );
        if ( $publishId && $this->oOrdinances->updateData( array( 'status' => 'PUBLISHED' ) , $data[ 'ordinance_id' ] ) )
        {
            return $publishId;
        }
        return 0;
    }

    public function createDirectory( $id )
    {
        if ( !file_exists( ROOT_DIR . '/uploads/' . $id ) )
        {
            mkdir( ROOT_DIR . '/uploads/' . $id );
        }
    }

    public function getApprovedOrdinance( $iUserId , $iOrId )
    {
        $sQuery = "
                    SELECT 
                            b_ordinances.id
                    FROM 
                            b_ordinances_users
                            LEFT JOIN b_ordinances
                            ON b_ordinances_users.ordinance_id = b_ordinances.id
                    WHERE
                            b_ordinances.deleted = 0
                            AND b_ordinances_users.sys_user_id=" . $iUserId . "
                            AND b_ordinances.id=" . $iOrId;

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getApprovedOrdinances( $iUserId , $sStatus = 'NOT_PUBLISHED' , $sType = 'ALL' )
    {
        $sAllTypes = "";
        if ( $sType === 'ORDINANCE|RESOLUTION' )
        {
            $sAllTypes = "AND b_ordinances.type IN ('ORDINANCE','RESOLUTION')";
        }
        else if ( $sType !== 'ALL' )
        {
            $sAllTypes = "AND b_ordinances.type = '" . $sType . "'";
        }

        $sQuery = "
                    SELECT 
                            b_ordinances.id
                    FROM 
                            b_ordinances_users
                            LEFT JOIN b_ordinances
                            ON b_ordinances_users.ordinance_id = b_ordinances.id
                    WHERE
                            b_ordinances.deleted = 0 "
                . $sAllTypes .
                " AND b_ordinances.status = '" . $sStatus . "'
                            AND b_ordinances_users.sys_user_id=" . $iUserId;
        return $this->oDb->fetchAll( $sQuery );
    }

    public function getApprovedOrdinancesFromUsers( $aUserIds , $sStatus = 'NOT_PUBLISHED' )
    {
        $sArray = join( ',' , $aUserIds );
        $sArrayLength = count( $aUserIds );

        $sQuery = "
                    SELECT 
                            b_ordinances.id, count( b_ordinances.id ) AS id_count
                    FROM
                            b_ordinances_users
                            LEFT JOIN b_ordinances
                            ON b_ordinances_users.ordinance_id = b_ordinances.id
                    WHERE
                            b_ordinances_users.sys_user_id IN (" . $sArray . ")
                            AND b_ordinances.status = '" . $sStatus . "'
                            AND b_ordinances.deleted = 0
                            AND b_ordinances_users.deleted = 0
                    GROUP BY
                            b_ordinances_users.ordinance_id
                    HAVING
                            count( b_ordinances.id ) = " . $sArrayLength;
        return $this->oDb->fetchAll( $sQuery );
    }

    public function getApprovedOrdinancesFromAtLeastOneUser( $aUserIds , $sStatus = 'NOT_PUBLISHED' )
    {
        $sArray = join( ',' , $aUserIds );

        $sQuery = "
                    SELECT 
                            b_ordinances.id, count( b_ordinances.id ) AS id_count
                    FROM
                            b_ordinances_users
                            LEFT JOIN b_ordinances
                            ON b_ordinances_users.ordinance_id = b_ordinances.id
                    WHERE
                            b_ordinances_users.sys_user_id IN (" . $sArray . ")
                            AND b_ordinances.status = '" . $sStatus . "'
                            AND b_ordinances.deleted = 0
                            AND b_ordinances_users.deleted = 0
                    GROUP BY
                            b_ordinances_users.ordinance_id
                    HAVING
                            count( b_ordinances.id ) > 0";
        return $this->oDb->fetchAll( $sQuery );
    }

    public function getOrdinanceApprovalsAll( $sStatus = 'NOT_PUBLISHED' , $sType = "ALL" )
    {
        $sAllTypes = "";
        if ( $sType === 'ORDINANCE|RESOLUTION' )
        {
            $sAllTypes = "AND b_ordinances.type IN ('ORDINANCE','RESOLUTION')";
        }
        else if ( $sType !== 'ALL' )
        {
            $sAllTypes = "AND b_ordinances.type = '" . $sType . "'";
        }

        $sQuery = Sql::$SELECT_ORDINANCE_APPROVALS . " " . $sAllTypes . " AND b_ordinances.status = '" . $sStatus . "'";
        return $this->oDb->fetchAll( $sQuery );
    }

    public function getOrdinanceApprovalsByOrdinanceId( $sStatus = 'NOT_PUBLISHED' , $sType = "ALL" , $ordinanceId = 0 )
    {
        if ( $ordinanceId === 0 )
        {
            return array();
        }

        $sAllTypes = "";
        if ( $sType === 'ORDINANCE|RESOLUTION' )
        {
            $sAllTypes = "AND b_ordinances.type IN ('ORDINANCE','RESOLUTION')";
        }
        else if ( $sType !== 'ALL' )
        {
            $sAllTypes = "AND b_ordinances.type = '" . $sType . "'";
        }

        $sQuery = Sql::$SELECT_ORDINANCE_APPROVALS . " " . $sAllTypes
                . " AND b_ordinances.status = '" . $sStatus
                . "' AND b_ordinances_users.ordinance_id = " . $ordinanceId;

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getOrdinanceApprovals( $sStatus = 'NOT_PUBLISHED' , $sType = "ALL" )
    {
        $sAllTypes = "";
        if ( $sType === 'ORDINANCE|RESOLUTION' )
        {
            $sAllTypes = "AND b_ordinances.type IN ('ORDINANCE','RESOLUTION')";
        }
        else if ( $sType !== 'ALL' )
        {
            $sAllTypes = "AND b_ordinances.type = '" . $sType . "'";
        }

        $sQuery = "
                    SELECT 
                            b_ordinances_users.id AS id,
                            b_ordinances_users.ordinance_id AS or_id,
                            b_ordinances_users.sys_user_id AS user_id,
                            sys_users.firstname AS firstname,
                            sys_users.lastname AS lastname,
                            sys_users.signature AS signature,
                            sys_users.district AS district
                    FROM 
                            b_ordinances_users
                            LEFT JOIN b_ordinances
                            ON b_ordinances_users.ordinance_id = b_ordinances.id
                            LEFT JOIN sys_users
                            ON b_ordinances_users.sys_user_id = sys_users.id
                    WHERE
                            b_ordinances.deleted = 0
                            AND b_ordinances_users.deleted = 0
                            AND sys_users.deleted = 0 "
                . $sAllTypes .
                " AND b_ordinances.status = '" . $sStatus . "'";

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getOrdinanceApprovalsFromOrdinanceId( $iOrId )
    {
        $sQuery = "
                    SELECT 
                            b_ordinances_users.id AS id,
                            b_ordinances_users.ordinance_id AS or_id,
                            b_ordinances_users.sys_user_id AS user_id,
                            sys_users.firstname AS firstname,
                            sys_users.lastname AS lastname,
                            sys_users.signature AS signature,
                            sys_users.district AS district
                    FROM 
                            b_ordinances_users
                            LEFT JOIN b_ordinances
                            ON b_ordinances_users.ordinance_id = b_ordinances.id
                            LEFT JOIN sys_users
                            ON b_ordinances_users.sys_user_id = sys_users.id
                    WHERE
                            b_ordinances.deleted = 0
                            AND b_ordinances_users.deleted = 0
                            AND sys_users.deleted = 0
                            AND b_ordinances_users.ordinance_id = " . $iOrId . "
                    ORDER BY
                            b_ordinances_users.sys_user_id";

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getOrdinances( $iLimit = 10 , $iSummaryLength = 0 , $sStatus = 'NOT_PUBLISHED' , $sType = 'ORDINANCE|RESOLUTION' )
    {
        $sSummaryQuery = "CONCAT(SUBSTRING(TRIM(b_ordinances.summary), -LENGTH(TRIM(b_ordinances.summary)), " . $iSummaryLength . "), '...') AS summary";
        if ( $iSummaryLength == 0 )
        {
            $sSummaryQuery = "TRIM(b_ordinances.summary) AS summary";
        }

        $sLimitQuery = "LIMIT 0, " . $iLimit;
        if ( $iLimit == 0 )
        {
            $sLimitQuery = "";
        }

        $sAllTypes = "";
        if ( $sType === 'ORDINANCE|RESOLUTION' )
        {
            $sAllTypes = "AND b_ordinances.type IN ('ORDINANCE','RESOLUTION')";
        }
        else if ( $sType !== 'ALL' )
        {
            $sAllTypes = "AND b_ordinances.type = '" . $sType . "'";
        }


        $sQuery = "
                    SELECT 
                            b_ordinances.id,
                            b_ordinances.name,
                            b_ordinances.type,
                            b_ordinances.date_created,
                            b_ordinances.user_created,
                  " . $sSummaryQuery .
                " FROM 
                            b_ordinances
                    WHERE
                            b_ordinances.deleted = 0
                            " . $sAllTypes .
                " AND b_ordinances.status = '" . $sStatus . "' " . $sLimitQuery;

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getFiles()
    {
        $sQuery = "
                    SELECT 
                            b_ordinances_files.id AS id,
                            b_ordinances_files.ordinance_id AS ordinance_id,
                            b_ordinances_files.filename AS filename
                    FROM 
                            b_ordinances_files
                            LEFT JOIN b_ordinances
                            ON b_ordinances_files.ordinance_id = b_ordinances.id 
                    WHERE
                            b_ordinances_files.deleted = 0
                            AND b_ordinances.deleted = 0";

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getApprovals()
    {
        $sQuery = "
                    SELECT 
                            b_ordinances.id AS or_id,
                            b_files.id AS f_id,
                            b_files.name AS f_name,
                            b_files.url
                    FROM 
                            b_ordinances_files
                            LEFT JOIN b_ordinances
                            ON b_ordinances_files.ordinance_id = b_ordinances.id
                            LEFT JOIN b_files
                            ON b_ordinances_files.file_id = b_files.id
                    WHERE
                            b_ordinances_files.deleted = 0
                            AND b_ordinances.deleted = 0
                            AND b_files.deleted = 0";

        return $this->oDb->fetchAll( $sQuery );
    }

    /**
     * 
     * @param type $id represents the b_ordinance id
     * @return type
     */
    public function getOrdinance( $id )
    {
        return $this->oOrdinances->getRowObject( $id );
    }

    public function update( $aData , $id )
    {
        $this->oOrdinances->updateData( $aData , $id );

        return true;
    }

    public function delete( $id )
    {
        $aData = array(
                'deleted' => 1
        );

        $deleteSuccess = $this->oOrdinances->updateData( $aData , $id );
        if ( $deleteSuccess )
        {
            $files = $this->getFilesFromOrdinanceId( $id );
            if ( !is_null( $files ) && !empty( $files ) )
            {
                foreach ( $files as $file )
                {
                    $this->deleteFile( $file[ 'id' ] , $file[ 'ordinance_id' ] , $file[ 'filename' ] );
                }
            }
        }

        return true;
    }

    public function getIdArray( array $values )
    {
        $result = array();
        foreach ( $values as $value )
        {
            $result[ $value[ 'id' ] ] = 1;
        }
        return $result;
    }

    public function getColumnValues( array $values , $columnName )
    {
        $result = array();
        foreach ( $values as $value )
        {
            $result[] = $value[ $columnName ];
        }
        return $result;
    }

    public function getBuProcArray( $ordinances , $isEditingAllowed , $isDeletingAllowed )
    {
        $finalOrdinances = array();
        for ( $i = 0 , $count = count( $ordinances ); $i < $count; $i++ )
        {
            $item = array();
            $item[ 'id' ] = $ordinances[ $i ][ 'id' ];
            $item[ 'name' ] = $ordinances[ $i ][ 'name' ];
            $item[ 'type' ] = $ordinances[ $i ][ 'type' ];
            $item[ 'date_created' ] = $ordinances[ $i ][ 'date_created' ];
            $item[ 'user_created' ] = $ordinances[ $i ][ 'user_created' ];
            $item[ 'showEditButton' ] = $isEditingAllowed;
            $item[ 'showDeleteButton' ] = $isDeletingAllowed;
            $finalOrdinances[ $ordinances[ $i ][ 'id' ] ] = $item;
        }
        return $finalOrdinances;
    }

    public function getOrdinancesArray( $ordinances , $currentUserApprovedOrdinances , $isDeletingAllowed = false , $isApprovingAllowed = false
    , $isEditingAllowed = false , $isPublishingAllowed = false , $halfsignedOrdinances = array() , $signedBySuperuser = array() , $signedBySuperadmin = array() , $roleId = 0 )
    {
        $finalOrdinances = array();

        $logo = $this->oImages->getDataURI( 'img/logo-bacoor.png' );
        $councilors = $this->oUsers->getUserInfoWithSignature( SiteConstants::$COUNCILOR_ID );
        $admins = $this->oUsers->getUserInfoWithSignature( str_replace( 'role' , '' , SiteConstants::$ADMIN ) );
        $superusers = $this->oUsers->getUserInfoWithSignature( str_replace( 'role' , '' , SiteConstants::$SUPERUSER ) );
        $superadmins = $this->oUsers->getUserInfoWithSignature( str_replace( 'role' , '' , SiteConstants::$SUPERADMIN ) );

        for ( $i = 0 , $count = count( $ordinances ); $i < $count; $i++ )
        {
            $item = array();
            $item[ 'id' ] = $ordinances[ $i ][ 'id' ];
            $item[ 'name' ] = $ordinances[ $i ][ 'name' ];
            $item[ 'summary' ] = $ordinances[ $i ][ 'summary' ];
            $item[ 'type' ] = $ordinances[ $i ][ 'type' ];
            $item[ 'date_created' ] = $ordinances[ $i ][ 'date_created' ];
            $item[ 'user_created' ] = $ordinances[ $i ][ 'user_created' ];

            $item[ 'alreadyApproved' ] = $this->hasKey( $currentUserApprovedOrdinances , $item[ 'id' ] );
            $item[ 'showEditButton' ] = $isEditingAllowed && !$this->hasKey( $halfsignedOrdinances , $item[ 'id' ] );
            $item[ 'showDeleteButton' ] = $isDeletingAllowed && !$this->hasKey( $halfsignedOrdinances , $item[ 'id' ] );
            $item[ 'showPublishButton' ] = $isPublishingAllowed && $this->hasKey( $signedBySuperuser , $item[ 'id' ] );
            $item[ 'showApproveButton' ] = $this->isApproveButtonEnabled( $isApprovingAllowed , $item[ 'id' ] , $roleId , $currentUserApprovedOrdinances , $signedBySuperadmin , $signedBySuperuser );

            $item[ 'logouri' ] = $logo;

            $approvals = $this->getOrdinanceApprovalsFromOrdinanceId( $item[ 'id' ] );

            $filteredList = $this->filterSignatures( $councilors , $approvals );
            $item[ 'councilors' ] = $filteredList;
            $item[ 'councilors1' ] = $this->getCouncilorsByDistrict( $filteredList , '1' );
            $item[ 'councilors2' ] = $this->getCouncilorsByDistrict( $filteredList , '2' );

            $admins = $this->filterSignatures( $admins , $approvals );
            $item[ 'admin' ] = $admins;
            $superusers = $this->filterSignatures( $superusers , $approvals );
            $item[ 'superuser' ] = $superusers;
            $superadmins = $this->filterSignatures( $superadmins , $approvals );
            $item[ 'superadmin' ] = $superadmins;

            $finalOrdinances[ $ordinances[ $i ][ 'id' ] ] = $item;
        }
        return $finalOrdinances;
    }

    public function filterSignatures( $array , $approvals )
    {
        $userIds = $this->getColumnValues( $approvals , 'user_id' );
        $arrayCopy = $array;
        for ( $i = 0; $i < count( $array ); $i++ )
        {
            if ( !in_array( $arrayCopy[ $i ][ 'id' ] , $userIds ) )
            {
                $arrayCopy[ $i ][ 'signature' ] = "";
                $arrayCopy[ $i ][ 'datauri' ] = "";
                $arrayCopy[ $i ][ 'datauriwidth' ] = "";
                $arrayCopy[ $i ][ 'datauriheight' ] = "";
            }
        }
        return $arrayCopy;
    }

    public function isApproveButtonEnabledForSingleOrdinance( $isApprovingAllowed , $isAlreadyApproved , $ordinanceId , $roleId , $superuserId , $superadminId )
    {
        $isSignedBySuperadmin = $this->checkIfAlreadySigned( $ordinanceId , $superadminId );
        $isSignedByCurrentUser = $isAlreadyApproved;
        $isSignedBySuperuser = $this->checkIfAlreadySigned( $ordinanceId , $superuserId );
        $isCurrentUserASuperUser = $roleId === SiteConstants::$SUPERUSER_ID;
        return $this->isApproveEnabled( $isSignedBySuperadmin , $isSignedByCurrentUser , $isSignedBySuperuser , $isCurrentUserASuperUser , $isApprovingAllowed );
    }

    public function isApproveButtonEnabled( $isApprovingAllowed , $ordinanceId , $roleId , $currentUserApprovedOrdinances , $signedBySuperadmin , $signedBySuperuser )
    {
        $isSignedBySuperadmin = $this->hasKey( $signedBySuperadmin , $ordinanceId );
        $isSignedByCurrentUser = $this->hasKey( $currentUserApprovedOrdinances , $ordinanceId );
        $isSignedBySuperuser = $this->hasKey( $signedBySuperuser , $ordinanceId );
        $isCurrentUserASuperUser = $roleId === SiteConstants::$SUPERUSER_ID;
        return $this->isApproveEnabled( $isSignedBySuperadmin , $isSignedByCurrentUser , $isSignedBySuperuser , $isCurrentUserASuperUser , $isApprovingAllowed );
    }

    private function isApproveEnabled( $isSignedBySuperadmin , $isSignedByCurrentUser , $isSignedBySuperuser , $isCurrentUserASuperUser , $isApprovingAllowed )
    {
        if ( !$isSignedBySuperadmin )
        {
            if ( $isApprovingAllowed && $isCurrentUserASuperUser && !$isSignedByCurrentUser )
            {
                return true;
            }
            else if ( $isApprovingAllowed && $isCurrentUserASuperUser )
            {
                return false;
            }
            else if ( $isApprovingAllowed && !$isCurrentUserASuperUser && !$isSignedByCurrentUser && $isSignedBySuperuser )
            {
                return true;
            }
            else if ( $isApprovingAllowed && !$isCurrentUserASuperUser && !$isSignedByCurrentUser && !$isSignedBySuperuser )
            {
                return false;
            }
            else if ( $isApprovingAllowed && !$isCurrentUserASuperUser && $isSignedByCurrentUser )
            {
                return false;
            }
        }
        return false;
    }

    public function getCouncilorsByDistrict( $councilors , $district )
    {
        $returnCouncil = array();
        foreach ( $councilors as $councilor )
        {
            if ( $councilor[ 'district' ] === $district )
            {
                $returnCouncil[] = $councilor;
            }
        }
        return $returnCouncil;
    }

    public function hasKey( $aArray , $iKey )
    {
        if ( array_key_exists( $iKey , $aArray ) )
        {
            return true;
        }
        return false;
    }

    public function getApprovalsArray( $approvals )
    {
        $finalApprovals = array();
        for ( $i = 0 , $count = count( $approvals ); $i < $count; $i++ )
        {
            $aItem = array();
            $aItem[ 'id' ] = $approvals[ $i ][ 'id' ];
            $aItem[ 'or_id' ] = $approvals[ $i ][ 'or_id' ];
            $aItem[ 'user_id' ] = $approvals[ $i ][ 'user_id' ];
            $aItem[ 'firstname' ] = $approvals[ $i ][ 'firstname' ];
            $aItem[ 'lastname' ] = $approvals[ $i ][ 'lastname' ];
            $aItem[ 'signature' ] = $approvals[ $i ][ 'signature' ];
            $aItem[ 'district' ] = $approvals[ $i ][ 'district' ];
            $aItem[ 'role_id' ] = $approvals[ $i ][ 'role_id' ];

            $finalApprovals[ $approvals[ $i ][ 'or_id' ] ][] = $aItem;
        }
        return $finalApprovals;
    }

    public function getFilesArray( $files )
    {
        $filesArray = array();
        for ( $i = 0 , $count = count( $files ); $i < $count; $i++ )
        {
            $aItem = array();
            $aItem[ 'id' ] = $files[ $i ][ 'id' ];
            $aItem[ 'ordinance_id' ] = $files[ $i ][ 'ordinance_id' ];
            $aItem[ 'filename' ] = $files[ $i ][ 'filename' ];
            $aItem[ 'url' ] = SiteConstants::convertToSrc( $this->getDocURL( $aItem[ 'ordinance_id' ] , $aItem[ 'filename' ] ) );
            $filesArray[ $files[ $i ][ 'ordinance_id' ] ][] = $aItem;
        }
        return $filesArray;
    }

    public function checkIfAlreadySigned( $ordinanceId , $userId = 0 )
    {
        $query = "ordinance_id=" . $ordinanceId;
        if ( $userId !== 0 )
        {
            $query = $query . " AND sys_user_id=" . $userId;
        }
        $aHaveSigned = $this->oOrdinancesUsers->fetchRow( $query );
        if ( !is_null( $aHaveSigned ) && !empty( $aHaveSigned ) && !empty( $aHaveSigned[ 'id' ] ) )
        {
            return true;
        }
        return false;
    }

    public function getStatus( $approval )
    {
        if ( count( $approval ) === 0 )
        {
            return "";
        }
        $result = "Signed by: ";
        $superadmin = "";
        $superuser = "";
        $district1 = 0;
        $district2 = 0;
        foreach ( $approval as $item )
        {
            if ( ( int ) $item[ 'role_id' ] === SiteConstants::$SUPERADMIN_ID )
            {
                $superadmin = $item[ 'firstname' ] . " " . $item[ 'lastname' ];
            }
            else if ( ( int ) $item[ 'role_id' ] === SiteConstants::$SUPERUSER_ID )
            {
                $superuser = $item[ 'firstname' ] . " " . $item[ 'lastname' ];
            }
            else if ( ( int ) $item[ 'district' ] === 1 )
            {
                $district1++;
            }
            else if ( ( int ) $item[ 'district' ] === 2 )
            {
                $district2++;
            }
        }
        if ( $superadmin !== '' && $superuser !== '' )
        {
            $result = $result . $superadmin . ", " . $superuser;
        }
        else if ( $superadmin !== '' )
        {
            $result = $result . $superadmin;
        }
        else if ( $superuser !== '' )
        {
            $result = $result . $superuser;
        }

        if ( $district1 > 0 )
        {
            if ( $superadmin !== '' || $superuser !== '' )
            {
                $result = $result . ", ";
            }
            $result = $result . $district1 . " District I Councilors";
        }

        if ( $district2 > 0 )
        {
            if ( $superadmin !== '' || $superuser !== '' || $district1 > 0 )
            {
                $result = $result . ", ";
            }
            $result = $result . $district2 . " District II Councilors";
        }

        return $result;
    }

    public function saveDocs( $data )
    {
        $this->oOrdinancesFiles->insertData( $data );
    }

    public function getFilesFromOrdinanceId( $ordinanceId )
    {
        $sQuery = "
                    SELECT 
                            b_ordinances_files.id AS id,
                            b_ordinances_files.ordinance_id AS ordinance_id,
                            b_ordinances_files.filename AS filename
                    FROM 
                            b_ordinances_files
                    WHERE
                            b_ordinances_files.deleted = 0
                            AND b_ordinances_files.ordinance_id = " . $ordinanceId;

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getDocURL( $ordinanceId , $filename )
    {
        $config = Zend_Registry::get( 'config' );
        return $config->upload->root->ordinances . DIRECTORY_SEPARATOR . $ordinanceId . DIRECTORY_SEPARATOR . $filename;
    }

    public function addURLs( $files )
    {
        for ( $i = 0; $i < count( $files ); $i++ )
        {
            $files[ $i ][ 'url' ] = SiteConstants::convertToSrc( $this->getDocURL( $files[ $i ][ 'ordinance_id' ] , $files[ $i ][ 'filename' ] ) );
        }
        return $files;
    }

    public function deleteFile( $fileId , $ordinanceId , $fileName )
    {
        $path = $this->pathToDocs . DIRECTORY_SEPARATOR
                . $ordinanceId . DIRECTORY_SEPARATOR . $fileName;
        if ( is_readable( $path ) )
        {
            unlink( $path );

            $data = array( "deleted" => 1 );
            $this->oOrdinancesFiles->updateData( $data , $fileId );
            return true;
        }
        $data = array( "deleted" => 1 );
        $this->oOrdinancesFiles->updateData( $data , $fileId );
        return false;
    }

}
