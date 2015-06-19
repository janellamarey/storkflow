<?php

Zend_Loader::loadClass( 'DB_Ordinances' );
Zend_Loader::loadClass( 'Ordinances' );

class models_LegislationsTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        call_user_func( array( TestConfiguration::$bootstrap , "configure" ) );
        TestConfiguration::setUpOrdinanceAndOrdinanceUsersTable();
        TestConfiguration::setupUsersTables();
    }

    public function testAdd()
    {
        $ordinances = new Ordinances();
        $data = array(
                'name' => 'name input' ,
                'summary' => 'summary input' ,
                'status' => 'NOT_PUBLISHED' ,
                'type' => 'ORDINANCE' ,
                'searchable' => 1
        );
        $ordinances->add( $data );

        $db = Zend_Registry::get( 'db' );
        $select = <<<EOT
                SELECT name, summary, status, type, searchable
                FROM b_ordinances                
EOT;
        $where = $db->quoteInto( 'WHERE name=?' , $data[ 'name' ] ) . ' && ';
        $where.=$db->quoteInto( 'summary=?' , $data[ 'summary' ] ) . ' && ';
        $where.=$db->quoteInto( 'status=?' , $data[ 'status' ] ) . ' && ';
        $where.=$db->quoteInto( 'type=?' , $data[ 'type' ] ) . ' && ';
        $where.='searchable=1';
        $dbOrdinances = $db->fetchAll( $select . ' ' . $where , "" );

        $this->assertSame( 1 , count( $dbOrdinances ) );
        $this->assertSame( $data[ 'name' ] , $dbOrdinances[ 0 ][ 'name' ] );
        $this->assertSame( $data[ 'summary' ] , $dbOrdinances[ 0 ][ 'summary' ] );
        $this->assertSame( $data[ 'status' ] , $dbOrdinances[ 0 ][ 'status' ] );
        $this->assertSame( $data[ 'type' ] , $dbOrdinances[ 0 ][ 'type' ] );
    }

    public function testLegislationsForCorrectReturnedLegislationTypes()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_ONE_BUDGET );
        $db->query( Sql::$INSERT_ONE_PROCUREMENT );
        $db->query( Sql::$INSERT_ONE_RESOLUTION );
        $ordinances = new Ordinances();

        $ordinanceQuery = $ordinances->getOrdinances( 0 , 0 , 'NOT_PUBLISHED' , 'ALL' );
        $this->assertSame( 4 , count( $ordinanceQuery ) );

        $ordinanceQuery = $ordinances->getOrdinances( 0 , 0 , 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' );
        $this->assertSame( 2 , count( $ordinanceQuery ) );

        $ordinanceQuery = $ordinances->getOrdinances( 0 , 0 , 'NOT_PUBLISHED' , 'ORDINANCE' );
        $this->assertSame( 1 , count( $ordinanceQuery ) );

        $ordinanceQuery = $ordinances->getOrdinances( 0 , 0 , 'NOT_PUBLISHED' , 'RESOLUTION' );
        $this->assertSame( 1 , count( $ordinanceQuery ) );

        $ordinanceQuery = $ordinances->getOrdinances( 0 , 0 , 'NOT_PUBLISHED' , 'BUDGET' );
        $this->assertSame( 1 , count( $ordinanceQuery ) );

        $ordinanceQuery = $ordinances->getOrdinances( 0 , 0 , 'NOT_PUBLISHED' , 'PROCUREMENT' );
        $this->assertSame( 1 , count( $ordinanceQuery ) );
    }

    public function testLegislationsForCorrectReturnedApprovals()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_ONE_BUDGET );
        $db->query( Sql::$INSERT_ONE_PROCUREMENT );
        $db->query( Sql::$INSERT_ONE_RESOLUTION );

        $db->query( Sql::$INSERT_ONE_APPROVAL_FOR_EACH_LEGISLATION );

        $ordinances = new Ordinances();
        $ordinanceQuery = $ordinances->getOrdinanceApprovals( 'NOT_PUBLISHED' , 'ALL' );
        $this->assertSame( 4 , count( $ordinanceQuery ) );

        $ordinanceQuery = $ordinances->getOrdinanceApprovals( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' );
        $this->assertSame( 2 , count( $ordinanceQuery ) );

        $ordinanceQuery = $ordinances->getOrdinanceApprovals( 'NOT_PUBLISHED' , 'ORDINANCE' );
        $this->assertSame( 1 , count( $ordinanceQuery ) );

        $ordinanceQuery = $ordinances->getOrdinanceApprovals( 'NOT_PUBLISHED' , 'RESOLUTION' );
        $this->assertSame( 1 , count( $ordinanceQuery ) );
    }

    public function testGetOrdinanceApprovals()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_ONE_APPROVAL_FOR_ONE_ORDINANCE );
        $ordinances = new Ordinances();
        $ordinanceQuery = $ordinances->getOrdinanceApprovalsAll( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' );
        $approvals = $ordinances->getApprovalsArray( $ordinanceQuery );
        foreach ( $approvals as $approval )
        {
            foreach ( $approval as $item )
            {
                $this->assertSame( '27' , $item[ 'id' ] );
                $this->assertSame( '102' , $item[ 'or_id' ] );
                $this->assertSame( '1' , $item[ 'user_id' ] );
                $this->assertSame( '1' , $item[ 'district' ] );
                $this->assertSame( 'Catherine' , $item[ 'firstname' ] );
                $this->assertSame( 'Evaristo' , $item[ 'lastname' ] );
                $this->assertSame( 'sig.png' , $item[ 'signature' ] );
            }
        }
    }

    public function testOrdinanceSignedByDistrictOneCouncilorsOnly()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_DISTRICT_ONE_APPROVALS_FOR_ONE_ORDINANCE );

        $ordinances = new Ordinances();
        $approvals = $ordinances->getOrdinanceApprovalsFromOrdinanceId( Sql::$ORDINANCE_ID );
        $this->assertSame( 7 , count( $approvals ) );
        $users = new Users();
        foreach ( $approvals as $approval )
        {
            $this->assertTrue( in_array( $approval[ 'user_id' ] , $users->getCouncilors() ) );
        }
    }

    public function testOrdinanceSignedByDistrictTwoCouncilorsOnly()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_DISTRICT_TWO_APPROVALS_FOR_ONE_ORDINANCE );
    }

    public function testOrdinanceSignedByCouncilorsOnly()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_COUNCILORS_APPROVALS_FOR_ONE_ORDINANCE );
    }

    public function testOrdinanceAndResolutionSignedByCouncilorsOnly()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_COUNCILORS_APPROVALS_FOR_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_ONE_RESOLUTION );
    }

    public function testOrdinanceSignedByViceMayorAndAttyOnly()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_VICEMAYOR_ATTORNEY_APPROVALS_FOR_ONE_ORDINANCE );
    }

    public function testOrdinanceSignedBy3D1and3D2CouncilorsOnly()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_3D1_3D2_APPROVALS_FOR_ONE_ORDINANCE );
    }

    public function testGetOrdinanceApprovalsByOrdinanceId1()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_3D1_3D2_APPROVALS_FOR_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_ONE_RESOLUTION );

        $ordinances = new Ordinances();
        $approvals = $ordinances->getOrdinanceApprovalsByOrdinanceId( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' , Sql::$ORDINANCE_ID );
        $finalApprovals = $ordinances->getApprovalsArray( $approvals );
        $approvalText = $ordinances->getStatus( $finalApprovals[ Sql::$ORDINANCE_ID ] );
        $this->assertSame( "Signed by: 3 District I Councilors, 3 District II Councilors" , $approvalText );
    }

    public function testGetOrdinanceApprovalsByOrdinanceId2()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_COUNCILORS_APPROVALS_FOR_ONE_ORDINANCE );

        $ordinances = new Ordinances();
        $approvals = $ordinances->getOrdinanceApprovalsByOrdinanceId( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' , Sql::$ORDINANCE_ID );
        $finalApprovals = $ordinances->getApprovalsArray( $approvals );
        $approvalText = $ordinances->getStatus( $finalApprovals[ Sql::$ORDINANCE_ID ] );
        $this->assertSame( "Signed by: 7 District I Councilors, 6 District II Councilors" , $approvalText );
    }

    public function testGetOrdinanceApprovalsByOrdinanceId3()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_VICEMAYOR_ATTORNEY_APPROVALS_FOR_ONE_ORDINANCE );

        $ordinances = new Ordinances();
        $approvals = $ordinances->getOrdinanceApprovalsByOrdinanceId( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' , Sql::$ORDINANCE_ID );
        $finalApprovals = $ordinances->getApprovalsArray( $approvals );
        $approvalText = $ordinances->getStatus( $finalApprovals[ Sql::$ORDINANCE_ID ] );
        $this->assertSame( "Signed by: Catherine Evaristo, Khalid Atega" , $approvalText );
    }

    public function testGetOrdinanceApprovalsByOrdinanceId4()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_VICEMAYOR_ATTORNEY_COUNCILORS_APPROVALS_FOR_ONE_ORDINANCE );

        $ordinances = new Ordinances();
        $approvals = $ordinances->getOrdinanceApprovalsByOrdinanceId( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' , Sql::$ORDINANCE_ID );
        $finalApprovals = $ordinances->getApprovalsArray( $approvals );
        $approvalText = $ordinances->getStatus( $finalApprovals[ Sql::$ORDINANCE_ID ] );
        $this->assertSame( "Signed by: Catherine Evaristo, Khalid Atega, 7 District I Councilors, 6 District II Councilors" , $approvalText );
    }
    
    public function testGetOrdinanceApprovalsByOrdinanceId5()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_3D1_3D2_APPROVALS_FOR_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_ONE_RESOLUTION );
        $db->query( Sql::$INSERT_3D1_3D2_APPROVALS_FOR_ONE_RESOLUTION );

        $ordinances = new Ordinances();
        $approvals = $ordinances->getOrdinanceApprovalsByOrdinanceId( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' , Sql::$ORDINANCE_ID );
        $finalApprovals = $ordinances->getApprovalsArray( $approvals );
        $approvalText = $ordinances->getStatus( $finalApprovals[ Sql::$ORDINANCE_ID ] );
        $this->assertSame( "Signed by: 3 District I Councilors, 3 District II Councilors" , $approvalText );

        $approvalsForResolution = $ordinances->getOrdinanceApprovalsByOrdinanceId( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' , Sql::$RESOLUTION_ID );
        $finalApprovalsForResolution = $ordinances->getApprovalsArray( $approvalsForResolution );
        $approvalTextForResolution = $ordinances->getStatus( $finalApprovalsForResolution[ Sql::$RESOLUTION_ID ] );
        $this->assertSame( "Signed by: 3 District I Councilors, 3 District II Councilors" , $approvalTextForResolution );
    }
    
    public function testGetOrdinanceApprovalsByOrdinanceId6()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_ATTORNEY_APPROVALS_FOR_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_DISTRICT_ONE_APPROVALS_FOR_ONE_ORDINANCE );

        $ordinances = new Ordinances();
        $approvals = $ordinances->getOrdinanceApprovalsByOrdinanceId( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' , Sql::$ORDINANCE_ID );
        $finalApprovals = $ordinances->getApprovalsArray( $approvals );
        $approvalText = $ordinances->getStatus( $finalApprovals[ Sql::$ORDINANCE_ID ] );
        $this->assertSame( "Signed by: Khalid Atega, 7 District I Councilors" , $approvalText );
    }
    
    public function testGetOrdinanceApprovalsByOrdinanceId7()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_ATTORNEY_APPROVALS_FOR_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_DISTRICT_TWO_APPROVALS_FOR_ONE_ORDINANCE );

        $ordinances = new Ordinances();
        $approvals = $ordinances->getOrdinanceApprovalsByOrdinanceId( 'NOT_PUBLISHED' , 'ORDINANCE|RESOLUTION' , Sql::$ORDINANCE_ID );
        $finalApprovals = $ordinances->getApprovalsArray( $approvals );
        $approvalText = $ordinances->getStatus( $finalApprovals[ Sql::$ORDINANCE_ID ] );
        $this->assertSame( "Signed by: Khalid Atega, 6 District II Councilors" , $approvalText );
    }
    
    public function testPublish()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query( Sql::$INSERT_ATTORNEY_COUNCILORS_APPROVALS_FOR_ONE_ORDINANCE );

        $ordinance = new Ordinances();
        $ordinance->addPublish( array( 'ordinance_id' => Sql::$ORDINANCE_ID , 'sys_user_id' => SiteConstants::$SUPERADMIN_ID ) );
        $ordinanceResult = $ordinance->getOrdinance( Sql::$ORDINANCE_ID );
        $this->assertSame( "PUBLISHED" , $ordinanceResult->status );
    }
    
    public function testPublishForResolution()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_RESOLUTION );
        $db->query( Sql::$INSERT_ATTORNEY_COUNCILORS_APPROVALS_FOR_ONE_RESOLUTION );

        $resolution = new Ordinances();
        $resolution->addPublish( array( 'ordinance_id' => Sql::$RESOLUTION_ID , 'sys_user_id' => SiteConstants::$SUPERADMIN_ID ) );
        $ordinanceResult = $resolution->getOrdinance( Sql::$RESOLUTION_ID );
        $this->assertSame( "PUBLISHED" , $ordinanceResult->status );
    }

    public function testGetApprovedOrdinancesFromUsers()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$INSERT_ONE_ORDINANCE );
        $db->query(Sql::$INSERT_ATTORNEY_APPROVALS_FOR_ONE_ORDINANCE);
        $ordinance = new Ordinances();
        $users = new Users();
        $result = $ordinance->getApprovedOrdinancesFromUsers( array( $users->getSingleSuperuser() ) , 'NOT_PUBLISHED' );
        $this->assertSame( 1 , count( $result ) );
    }
    
}
