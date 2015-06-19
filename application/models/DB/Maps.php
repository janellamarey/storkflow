<?php

Zend_Loader::loadClass( 'DB_Base' );

class DB_Maps extends DB_Base
{

    protected $_name = 'b_maps';

    public function getRowObjectFromName( $sName )
    {
        $sSql = $this->_db->quoteInto( 'deleted=0 AND name=?' , $sName );
        return $this->fetchRow( $sSql );
    }

}
