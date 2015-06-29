<?php

Zend_Loader::loadClass( 'DB_Base' );

class DB_Stories extends DB_Base
{
    protected $_name = 'sflow_stories';
    protected $_rowClass = 'DB_Story';
}
