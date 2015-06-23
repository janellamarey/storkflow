<?php

Zend_Loader::loadClass( 'DB_Base' );

class Db_EmailQueue extends DB_Base
{

    protected $_name = 'sys_email_queue';

}
