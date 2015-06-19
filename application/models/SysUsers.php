<?php

class SysUsers extends Zend_Db_Table_Abstract
{
    protected $_name = 'sys_users';

    /**
     * Return the users of this role type
     * @param int $iProfileId
     * @return array
     */
    public function getUserFromRole($iRoleId = null)
    {
        $oDb = Zend_Registry::get('db');

        $sQuery = " SELECT
                        SU.id,
                        SU.firstname,
                        SU.lastname,
                        SU.address
                    FROM
                        sys_users AS SU
                    INNER JOIN
                        sys_user_roles AS SUR
                        ON SU.id = SUR.sys_user_id";
        $sWhere = "";
        if(is_null($iRoleId))
        {
            $sWhere = " WHERE SU.deleted = 0";
            return $oDb->fetchAll($sQuery.$sWhere);
        }
        else
        {
            $sWhere = " WHERE
                        SUR.sys_role_id = ?
                        AND SU.deleted = 0";
        }
        
        return $oDb->fetchAll($sQuery.$sWhere, array($iRoleId));

    }

    /**
     * Return the user given his ID
     * @param int $iProfileId
     * @return array
     */
    public function getUserFromId($iUserId)
    {
        $oDb = Zend_Registry::get('db');

        $sQuery = " SELECT
                        SU.id AS id,
                        SUR.id AS user_role_id,
                        SU.firstname AS firstname,
                        SU.lastname AS lastname,
                        SU.address AS address
                    FROM
                        sys_users AS SU
                    INNER JOIN
                        sys_user_roles AS SUR
                        ON SU.id = SUR.sys_user_id
                    WHERE
                        SU.id = ?";

        return $oDb->fetchRow($sQuery, array($iUserId));
    }
}