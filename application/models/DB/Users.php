<?php

Zend_Loader::loadClass( 'DB_Base' );

class DB_Users extends DB_Base
{
    protected $_name = 'sys_users';
    protected $_rowClass = 'DB_User';

    public function getRowObjectFromEmail( $sEmail )
    {
        $sSql = $this->_db->quoteInto("deleted=0 AND email_add=?", $sEmail);
        return $this->fetchRow( $sSql );
    }
    
}
