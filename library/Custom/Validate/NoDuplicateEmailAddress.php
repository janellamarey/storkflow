<?php

class Custom_Validate_NoDuplicateEmailAddress extends Zend_Validate_Db_NoRecordExists
{

    protected $_messageTemplates = array(
            self::ERROR_NO_RECORD_FOUND => "No record matching '%value%' was found" ,
            self::ERROR_RECORD_FOUND => "'%value%' is already being used."
    );

}
