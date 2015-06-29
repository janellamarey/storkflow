<?php

Zend_Loader::loadClass( 'DB_Users' );
Zend_Loader::loadClass( 'DB_UserRoles' );
Zend_Loader::loadClass( 'DB_Roles' );
Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'SQLConstants' );

class Users
{

    public function __construct()
    {
        $this->db = Zend_Registry::get( 'db' );
        $this->users = new DB_Users();
        $this->userRoles = new DB_UserRoles();
        $this->roles = new DB_Roles();

        $this->appConfig = Zend_Registry::get( 'config' );
        $this->uploadFolder = $this->appConfig->upload->root->users;

        $aclHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'AclHelper' );
        $user = $aclHelper->getCurrentUser();
        $this->currentRoleId = $user->sysRoleId;
        $this->currentUserId = $user->sysUserId;
    }

    public function addNewUser( array $post )
    {
        $userId = $this->addNewPersonalData( $post );

        if ( $userId )
        {
            $userRoleId = $this->addNewAccountData( $userId , $post );
            if ( $userRoleId )
            {
                return $userId;
            }
        }
        return 0;
    }

    public function addNewPersonalData( $post )
    {
        $users = new DB_Users();
        $user = $users->createRow();
        $user->lastname = $post[ 'lastname' ];
        $user->firstname = $post[ 'firstname' ];
        $user->contacts = $post[ 'contact' ];
        $user->email_add = $post[ 'emailadd' ];
        $user->mi = $post[ 'mi' ];
        $user->designation = $post[ 'designation' ];
        return $user->save();
    }

    public function addNewAccountData( $userId , $post )
    {
        $username = $post[ 'username' ];
        $password = $post[ 'password' ];
        $roleId = $post[ 'roles' ];
        $status = SiteConstants::$REG;

        $accountData = array(
                'sys_user_id' => $userId ,
                'sys_role_id' => $roleId ,
                'username' => $username ,
                'password' => $password ,
                'status' => $status
        );
        return $this->userRoles->insertData( $accountData );
    }

    public function getPersonalData( $userId )
    {
        return $this->users->getRowObject( $userId );
    }
    
    public function getUserId( $userRoleId )
    {
        return $this->userRoles->getUserId( $userRoleId );
    }

    public function getAccountData( $userRoleId )
    {
        return $this->userRoles->getRowObject( $userRoleId );
    }

    public function getRoleData( $roleId )
    {
        return $this->roles->getRowObject( $roleId );
    }

    public function getActiveEmailAddresses( $role )
    {
        return $this->db->fetchAll( SQLConstants::$QUERY_EMAILS , array( $role ) , PDO::FETCH_COLUMN );
    }

    public function getRegisteredUsersExcept( $exceptions = null , $excludeCurrentUser = true )
    {
        if ( is_null( $exceptions ) || !$this->all( $exceptions , 'is_int' ) )
        {
            return array();
        }

        $notIncludeCurrent = " AND sys_user_roles.sys_user_id <> " . $this->currentUserId;
        if ( empty( $exceptions ) && $excludeCurrentUser )
        {
            $where = $notIncludeCurrent;
            return $this->db->fetchAll( SQLConstants::$QUERY_ALL_REGISTERED_USERS . $where );
        }
        else if ( empty( $exceptions ) )
        {
            return $this->db->fetchAll( SQLConstants::$QUERY_ALL_REGISTERED_USERS );
        }

        $excludeRoles = " AND sys_user_roles.sys_role_id NOT IN(" . implode( ',' , $exceptions ) . ")";
        if ( $excludeCurrentUser )
        {
            $where = $excludeRoles . $notIncludeCurrent;
            return $this->db->fetchAll( SQLConstants::$QUERY_ALL_REGISTERED_USERS . $where );
        }

        $where = $excludeRoles;
        return $this->db->fetchAll( SQLConstants::$QUERY_ALL_REGISTERED_USERS . $where );
    }

    public function toArray( $users , $isEditAllowed = false , $isDeleteAllowed = false , $isApproveAllowed = false , $isDenyAllowed = false )
    {
        $finalUsers = $this->getStructuredArray( $users , $isEditAllowed , $isDeleteAllowed , $isApproveAllowed , $isDenyAllowed );
        if ( count( $finalUsers ) > 0 )
        {
            return $finalUsers;
        }
        return array();
    }

    private function getStructuredArray( $users , $isEditAllowed = false , $isDeleteAllowed = false , $isApproveAllowed = false , $isDenyAllowed = false )
    {
        $finalUsers = array();
        for ( $i = 0 , $count = count( $users ); $i < $count; $i++ )
        {
            $item = array();
            $item[ 'sys_id' ] = $users[ $i ][ 'sys_id' ];
            $item[ 'user_id' ] = $users[ $i ][ 'user_id' ];
            $item[ 'name' ] = SiteConstants::createName( $users[ $i ][ 'firstname' ] , $users[ $i ][ 'lastname' ] , $users[ $i ][ 'mi' ] , $users[ $i ][ 'designation' ] );
            $item[ 'status' ] = $users[ $i ][ 'status' ];
            $item[ 'email_add' ] = $users[ $i ][ 'email_add' ];
            $item[ 'contacts' ] = $users[ $i ][ 'contacts' ];
            $item[ 'date_created' ] = $users[ $i ][ 'date_created' ];
            $item[ 'user_created' ] = $users[ $i ][ 'user_created' ];
            $item[ 'date_modified' ] = $users[ $i ][ 'date_last_modified' ];
            $item[ 'user_modified' ] = $users[ $i ][ 'user_last_modified' ];
            $item[ 'edit' ] = $isEditAllowed;
            $item[ 'delete' ] = $isDeleteAllowed;
            $item[ 'approve' ] = $isApproveAllowed;
            $item[ 'deny' ] = $isDenyAllowed;
            $item[ 'alreadyapproved' ] = $users[ $i ][ 'status' ] === SiteConstants::$REG;
            $finalUsers[ $users[ $i ][ 'sys_id' ] ] = $item;
        }
        return $finalUsers;
    }

    public function delete( $sysId )
    {
        $data = array(
                'deleted' => 1
        );

        $row = $this->userRoles->getRow( $sysId );

        if ( !is_null( $row ) )
        {
            $this->userRoles->updateData( $data , $sysId );
            $this->users->updateData( $data , $row[ 'sys_user_id' ] );
        }
        return true;
    }

    public function getAccountDataFromUserId( $userId )
    {
        return $this->userRoles->getRowObjectFromUserId( $userId );
    }

    public function updatePasswordData( $userId , $password )
    {
        $data = array(
                'password' => $password
        );
        return $this->userRoles->updateData( $data , $userId );
    }

    public function updatePersonalData( $userId , $post )
    {

        $lastname = $post[ 'lastname' ];
        $firstname = $post[ 'firstname' ];
        $contact = $post[ 'contacts' ];
        $email = $post[ 'email_add' ];
        $middleInitial = $post[ 'mi' ];
        $designation = $post[ 'designation' ];

        $personalData = array(
                'lastname' => $lastname ,
                'firstname' => $firstname ,
                'contacts' => $contact ,
                'email_add' => $email ,
                'mi' => $middleInitial ,
                'designation' => $designation
        );

        return $this->users->updateData( $personalData , $userId );
    }

    public function updateAccountData( $userRoleId , $post )
    {
        $username = $post[ 'username' ];
        $accountData = array(
                'username' => $username
        );
        return $this->userRoles->updateData( $accountData , $userRoleId );
    }
    
    public function updatePrivilege( $userRoleId , $post )
    {
        $roleId = $post[ 'sys_role_id' ];
        $accountData = array(
                'sys_role_id' => $roleId
        );
        return $this->userRoles->updateData( $accountData , $userRoleId );
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
