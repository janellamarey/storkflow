<?php

class SysRoleMappings extends Zend_Db_Table_Abstract
{
    protected $_name = 'sys_role_mappings';
    
    public function getMappings()
    {
        $oDb = Zend_Registry::get('db');
        $sQuery = " SELECT
                        parent_id, child_id
                    FROM
                        sys_role_mappings
                    ORDER BY
                        parent_id DESC";

        return $oDb->fetchAll($sQuery);
    }
    
    public function getMenu($iRoleId)
    {
        $oDb = Zend_Registry::get('db');
        $sQuery = " SELECT
                        parent_id, child_id
                    FROM
                        sys_role_mappings
                    ORDER BY
                        parent_id DESC";

        return $oDb->fetchAll($sQuery);
    }
}