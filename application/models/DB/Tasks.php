<?php

Zend_Loader::loadClass( 'DB_Base' );

class DB_Tasks extends DB_Base
{
    protected $_name = 'sflow_tasks';
    protected $_rowClass = 'DB_Task';
}
