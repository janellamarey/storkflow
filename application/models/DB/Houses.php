<?php

Zend_Loader::loadClass( 'DB_Base' );

class DB_Houses extends DB_Base
{
    protected $_name = 'sflow_houses';
    protected $_rowClass = 'DB_House';
}
