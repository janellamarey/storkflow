<?php

Zend_Loader::loadClass( 'DB_Base' );

class DB_RoleMappings extends DB_Base
{

    protected $_name = 'sys_role_mappings';

    public function getMappings()
    {
        $oDb = Zend_Registry::get( 'db' );
        $sQuery = " SELECT
                        parent_id, child_id
                    FROM
                        sys_role_mappings
                    ORDER BY
                        parent_id DESC";

        return $oDb->fetchAll( $sQuery );
    }

}
