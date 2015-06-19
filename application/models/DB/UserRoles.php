<?php

Zend_Loader::loadClass( 'DB_Base' );

class DB_UserRoles extends DB_Base
{
    protected $_name = 'sys_user_roles';
    
    public function getRowObjectFromUserId( $iUserId )
    {
        return $this->fetchRow( 'deleted=0 AND sys_user_id=' . $iUserId );
    }
    
}
