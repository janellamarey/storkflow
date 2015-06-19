<?php
class Errors
{    
    public function __construct() 
    {
        $this->aErrors = array();
    }
    public function addError($sString)
    {
        $this->aErrors[] = $sString;
    }
    
    public function getErrors()
    {
        return $this->aErrors;
    }
    
    public function setValidator($validator)
    {
        $this->oValidator = $validator;
    }
    
    public function checkError($validator, $data, $message)
    {
        if(!is_null($this->oValidator))
        {
            $aError = $this->oValidator->validate($validator, $data, $message);
            if($aError != '')
            {
                $this->addError($aError);
            }
        }
    }    
}