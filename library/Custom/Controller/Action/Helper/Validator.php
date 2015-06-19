<?php
class Custom_Controller_Action_Helper_Validator extends Zend_Controller_Action_Helper_Abstract
{

    protected $oEmptyValidator = null;

    protected $aValidatorSet = array();

    public function __construct(Zend_View_Interface $view = null, array $options = array())
    {
        $this->oEmptyValidator = new Zend_Validate();
        $this->oEmptyValidator->addValidator(new Zend_Validate_NotEmpty());

        $this->oFloatValidator = new Zend_Validate();
        $this->oFloatValidator->addValidator(new Zend_Validate_Float());

        $this->oIntValidator = new Zend_Validate();
        $this->oIntValidator->addValidator(new Zend_Validate_Int());

        $this->oEmailValidator = new Zend_Validate();
        $this->oEmailValidator->addValidator(new Zend_Validate_EmailAddress());

        $this->o0300Validator = new Zend_Validate();
        $this->o0300Validator->addValidator(new Zend_Validate_Int())->addValidator(new Zend_Validate_Between(0, 300));

        $this->oDigitValidator = new Zend_Validate();
        $this->oDigitValidator->addValidator(new Zend_Validate_Digits());
        
        $this->aValidatorSet['notempty'] = $this->oEmptyValidator;
        $this->aValidatorSet['float'] = $this->oFloatValidator;
        $this->aValidatorSet['int'] = $this->oIntValidator;
        $this->aValidatorSet['email'] = $this->oEmailValidator;
        $this->aValidatorSet['0300Rnge'] = $this->o0300Validator;
        $this->aValidatorSet['digits'] = $this->oDigitValidator;
    }
    

    public function errorMessages($sType, $sValue, $sMessage)
    {
        $sErrorMessages = array();
        if(!$this->aValidatorSet[$sType]->isValid($sValue))
        {
            $sErrorMessages[] = $sMessage;
        }
        
        return $sErrorMessages;

    }
    
    public function validate($sType, $sValue, $sMessage)
    {
        $sErrorMessage = '';
        if(!$this->aValidatorSet[$sType]->isValid($sValue))
        {
            $sErrorMessage = $sMessage;
        }        
        return $sErrorMessage;
    }
    
    public function errorMessage($sType, $sValue, $sMessage)
    {
        $sErrorMessage = '';
        if(!$this->aValidatorSet[$sType]->isValid($sValue))
        {
            $sErrorMessage = $sMessage;
        }        
        return $sErrorMessage;
    }

    public function errorDBMessages($sTable, $sWhere, $sMessage)
    {
        $sErrorMessages = array();

        if(!is_null($sTable->fetchRow($sWhere)))
        {
            $sErrorMessages[] = $sMessage;
        }

        return $sErrorMessages;

    }

    public function errorIfNotInDBMessages($sTable, $sWhere, $sMessage)
    {
        $sErrorMessages = array();

        if(is_null($sTable->fetchRow($sWhere)))
        {
            $sErrorMessages[] = $sMessage;
        }

        return $sErrorMessages;

    }



}