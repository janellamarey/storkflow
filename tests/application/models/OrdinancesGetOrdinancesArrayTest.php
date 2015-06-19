<?php

Zend_Loader::loadClass( 'DB_Ordinances' );
Zend_Loader::loadClass( 'Ordinances' );

class models_OrdinancesGetOrdinancesArrayTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        call_user_func( array( TestConfiguration::$bootstrap , "configure" ) );
        TestConfiguration::setUpOrdinanceAndOrdinanceUsersTable();
        TestConfiguration::setupUsersTables();
    }

    public function testZeroSigned()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
//        $db->query( Sql::$INSERT_ATTORNEY_COUNCILORS_APPROVALS_FOR_ONE_ORDINANCE );
//
//        $dbUsers = new DB_Users();
//        //superadmin
//        $userFetched = $dbUsers->getRowObject( 1 );        
//        //setup user
//        $user = array();
//        $user[ 'id' ] = $userFetched->id;
//        $user[ 'sys_user_id' ] = $userFetched->sys_user_id;
//        $user[ 'sys_role_id' ] = $userFetched->sys_role_id;
//        $user[ 'role_id' ] = ( int ) $userFetched->sys_role_id;
//        $user[ 'username' ] = $userFetched->username;
//        $user[ 'deleted' ] = $userFetched->deleted;
//
//        $ordinanceBean = new Ordinances();
//        $ordinances = $ordinanceBean->getOrdinances( 0 , 0 , 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' );
//        $currentUserApprovedOrdinances = $this->oOrdinances->getApprovedOrdinances( $user[ 'sys_user_id' ] , 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' );
//        $userApprovals = $this->oOrdinances->getIdArray( $currentUserApprovedOrdinances );
//
//        $council = $this->oUsers->getCouncilors();
//        if ( $roleId === SiteConstants::$SUPERADMIN_ID )
//        {
//            $council[] = $this->oUsers->getSingleSuperuser();
//        }
//
//        $ordinancesApprovedByAtLeastOneInGroup = $this->oOrdinances->getApprovedOrdinancesFromAtLeastOneUser( $council , 'NOT_PUBLISHED' );
//        $halfsignedOrdinances = $this->oOrdinances->getIdArray( $ordinancesApprovedByAtLeastOneInGroup );
//
//        $ordinancesApprovedBySuperuser = $this->oOrdinances->getApprovedOrdinancesFromUsers( array( $this->oUsers->getSingleSuperuser() ) , 'NOT_PUBLISHED' );
//        $signedBySuperuser = $this->oOrdinances->getIdArray( $ordinancesApprovedBySuperuser );
//
//        $ordinancesApprovedBySuperadmin = $this->oOrdinances->getApprovedOrdinancesFromUsers( array( $this->oUsers->getSingleSuperadmin() ) , 'NOT_PUBLISHED' );
//        $signedBySuperadmin = $this->oOrdinances->getIdArray( $ordinancesApprovedBySuperadmin );
//
//        $finalOrdinances = $this->oOrdinances->getOrdinancesArray( $ordinances , $userApprovals , $isDeletingAllowed , $isApprovingAllowed , $isEditingAllowed , $isPublishingAllowed , $halfsignedOrdinances , $signedBySuperuser , $signedBySuperadmin , $roleId );
    }

}
