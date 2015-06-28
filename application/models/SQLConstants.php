<?php

class SQLConstants
{
    public static $QUERY_EMAILS = 
                    "SELECT 
                            sys_users.email_add
                    FROM 
                            sys_user_roles
                            LEFT JOIN sys_users
                            ON sys_user_roles.sys_user_id = sys_users.id
                    WHERE
                            sys_user_roles.deleted = 0
                            AND sys_users.deleted = 0
                            AND sys_users.email_add <> ''
                            AND CONCAT( 'role', sys_user_roles.sys_role_id ) = ?";

    public static $QUERY_ALL_REGISTERED_USERS = 
                    "SELECT 
                            sys_user_roles.id AS sys_id,
                            sys_users.id AS user_id,
                            sys_users.firstname,
                            sys_users.lastname,
                            sys_users.mi,
                            sys_users.designation,
                            sys_users.email_add,
                            sys_users.contacts,
                            sys_users.date_created,
                            sys_users.user_created,
                            sys_users.date_last_modified,
                            sys_users.user_last_modified,
                            sys_user_roles.status
                    FROM 
                            sys_user_roles
                            LEFT JOIN sys_users
                            ON sys_user_roles.sys_user_id = sys_users.id
                    WHERE
                            sys_user_roles.deleted = 0
                            AND sys_users.deleted = 0
                            AND sys_user_roles.status = 'REG'";
}
