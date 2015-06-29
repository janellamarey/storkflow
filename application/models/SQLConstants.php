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
    
    public static $QUERY_ALL_TEAMS = 
                    "SELECT 
                            sflow_teams.id,
                            sflow_teams.name,
                            sflow_teams.description,
                            sflow_teams.date_created,
                            sflow_teams.user_created,
                            sflow_teams.date_last_modified,
                            sflow_teams.user_last_modified
                    FROM 
                            sflow_teams
                    WHERE
                            sflow_teams.deleted = 0";
    
    public static $QUERY_ALL_HOUSES = 
                    "SELECT 
                            sflow_houses.id,
                            sflow_houses.name,
                            sflow_houses.description,
                            sflow_houses.date_created,
                            sflow_houses.user_created,
                            sflow_houses.date_last_modified,
                            sflow_houses.user_last_modified
                    FROM 
                            sflow_houses
                    WHERE
                            sflow_houses.deleted = 0";
    
    public static $QUERY_ALL_TASKS = 
                    "SELECT 
                            sflow_tasks.id,
                            sflow_tasks.name,
                            sflow_tasks.description,
                            sflow_tasks.date_created,
                            sflow_tasks.user_created,
                            sflow_tasks.date_last_modified,
                            sflow_tasks.user_last_modified
                    FROM 
                            sflow_tasks
                    WHERE
                            sflow_tasks.deleted = 0";
    
    public static $QUERY_ALL_STORIES = 
                    "SELECT 
                            sflow_stories.id,
                            sflow_stories.name,
                            sflow_stories.description,
                            sflow_stories.date_created,
                            sflow_stories.user_created,
                            sflow_stories.date_last_modified,
                            sflow_stories.user_last_modified
                    FROM 
                            sflow_stories
                    WHERE
                            sflow_stories.deleted = 0";
}
