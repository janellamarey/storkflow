<?php

class SysUserRoles extends Zend_Db_Table_Abstract
{
    protected $_name = 'sys_user_roles';

    /**
     * Return the user roles of this role type
     * @param int $iProfileId
     * @return array
     */
    public function getUserRoleFromRole($iRoleId = null)
    {
        $oDb = Zend_Registry::get('db');

        $sQuery = "
                    SELECT
                                    SUR.id AS id,
                                    SU.id AS user_id,
                                    SU.firstname,
                                    SU.lastname,
                                    SU.address,
                                    SUR.username,
                                    SUR.password
                    FROM
                                    sys_user_roles AS SUR
                    INNER JOIN
                                    sys_users AS SU
                                    ON SUR.sys_user_id = SU.id
                    INNER JOIN
                                    sys_roles AS SR
                                    ON SUR.sys_role_id = SR.id
                ";

        $sWhere = "";
        if(is_null($iRoleId))
        {
            $sWhere = " WHERE SU.deleted = 0";
            return $oDb->fetchAll($sQuery.$sWhere);
        }
        else
        {
            $sWhere = " WHERE
                        SR.id = ?
                        AND SU.deleted = 0";
        }

        return $oDb->fetchAll($sQuery.$sWhere, array($iRoleId));

    }
    
    /**
     * Return the user roles information given his user role id
     * @param int $iUserRoleId
     * @return array
     */
    public function getUserRoleFromId($iUserRoleId = null)
    {
        $oDb = Zend_Registry::get('db');
        
        $sQuery = "
                    SELECT
                                SU.firstname,
                                SU.lastname,
                                SU.address,
                                SUR.username,
                                SUR.password
                    FROM
                                sys_user_roles AS SUR
                    INNER JOIN
                                sys_users AS SU
                                ON SUR.sys_user_id = SU.id
                    INNER JOIN
                                sys_roles AS SR
                                ON SUR.sys_role_id = SR.id
                    WHERE
                                SUR.id = ?";

        if(is_null($iUserRoleId))
        {
             return array();
        }
             
        return $oDb->fetchRow($sQuery, array($iUserRoleId));

    }
    
    

        
}