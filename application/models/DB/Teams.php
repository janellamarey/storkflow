<?php

Zend_Loader::loadClass( 'DB_Base' );

class DB_Teams extends DB_Base
{
    protected $_name = 'sflow_teams';
    protected $_rowClass = 'DB_Team';
}
