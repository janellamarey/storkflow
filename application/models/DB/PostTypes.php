<?php

Zend_Loader::loadClass( 'DB_Base' );

class Db_PostTypes extends DB_Base
{

    protected $_name = 'b_post_types';

    public function getIdByName( $name )
    {
        return $this->fetchRow( 'deleted=0 AND name="' . $name . '"' )->toArray();
    }

}
