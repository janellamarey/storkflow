<?php

Zend_Loader::loadClass( 'SiteConstants' );

class User
{

    public function __construct( $identity = null )
    {
        if ( is_null( $identity ) )
        {
            $this->setDefaults();
        }
        else
        {
            $this->id = $identity->id;
            $this->sysUserId = $identity->sys_user_id;
            $this->sysRoleId = ( int ) $identity->sys_role_id;
            $this->username = $identity->username;
            $this->deleted = $identity->deleted;
        }
    }

    public function setDefaults()
    {
        $this->id = "0";
        $this->sysUserId = "0";
        $this->sysRoleId = SiteConstants::$GUEST_ID;
        $this->username = "";
        $this->deleted = 0;
    }

    public function toArray()
    {
        $result = array();
        $result[ 'id' ] = $this->id;
        $result[ 'sys_user_id' ] = $this->sysUserId;
        $result[ 'role_id' ] = $this->sysRoleId;
        $result[ 'username' ] = $this->username;
        $result[ 'deleted' ] = $this->deleted;
        return $result;
    }
    
}
