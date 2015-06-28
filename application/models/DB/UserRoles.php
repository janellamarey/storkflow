<?php

Zend_Loader::loadClass( 'DB_Base' );

class DB_UserRoles extends DB_Base
{
    protected $_name = 'sys_user_roles';
    
    public function getUserId( $userRoleId )
    {
        $object = $this->fetchRow( 'deleted=0 AND id=' . $userRoleId );
        return $object->sys_user_id;                
    }
    
}
