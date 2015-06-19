<?php

class Custom_Validate_FilenameLength extends Zend_Validate_StringLength
{

    public function __construct( $options = array() )
    {
        parent::__construct( $options );
        $this->setMessages( array(
                Zend_Validate_StringLength::INVALID => "Invalid type given. String expected" ,
                Zend_Validate_StringLength::TOO_SHORT => "%value% is less than %min% characters long" ,
                Zend_Validate_StringLength::TOO_LONG => "%value% is more than %max% characters long"
        ) );
    }

    public function isValid( $value )
    {
        $basename = basename( $value );
        return parent::isValid( $basename );
    }

}
