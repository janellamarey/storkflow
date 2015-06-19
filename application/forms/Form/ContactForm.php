<?php

class Form_ContactForm extends Zend_Form
{

    protected $_actionUrl;

    public function __construct( $actionUrl = null , $options = null )
    {
        parent::__construct( $options );
        $this->_actionUrl = $actionUrl;
        $this->init();
    }

    public function init()
    {
        $this->setAction( $this->_actionUrl )->setMethod( 'post' )
                ->setAttrib( 'class' , 'simpleForm' )
                ->setAttrib( 'id' , 'saveChange' );
        $this->clearDecorators();
        $aFieldDecorators = array(
                array( 'ViewHelper' ) ,
                array( 'Errors' ) ,
                array(
                        'HtmlTag' ,
                        array( 'tag' => 'li' )
                )
        );

        $aButtonDecorators = array(
                array( 'ViewHelper' ) ,
                array(
                        'HtmlTag' ,
                        array(
                                'tag' => 'li' ,
                                'class' => 'submit'
                        )
                )
        );

        $aDefaultDecorators = array(
                array( 'ViewHelper' ) ,
                array(
                        'HtmlTag' ,
                        array(
                                'tag' => 'li' ,
                                'class' => ''
                        )
                )
        );

        $oEmailValidator = new Zend_Validate_EmailAddress();

        //person you would contact
        $oPersonNote = new Zend_Form_Element_Note( 'person_label' );
        $oPersonNote->setValue( 'Person you would like to contact' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oPersonNote );

        //to field
        $oTo = new Zend_Form_Element_Select( 'to' );
        $oTo->setLabel( "to" )->setRequired( true )->addMultiOptions( array( '0' => 'Please select...' , '1' => 'Vice Mayor Karen Sarino-Evaristo' ,
                '2' => 'Atty. Khalid' , '3' => 'All Councilors' ) );
        $oTo->setDecorators( $aFieldDecorators );
        $this->addElement( $oTo );

        //fullname
        $oFullNameNote = new Zend_Form_Element_Note( 'fullname_label' );
        $oFullNameNote->setValue( 'Fullname' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oFullNameNote );

        //fullname field
        $this->addElement( 'text' , 'fullname' );
        $this->getElement( 'fullname' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        //company name
        $oCompanyNote = new Zend_Form_Element_Note( 'company_label' );
        $oCompanyNote->setValue( 'Company Name' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oCompanyNote );

        //company name field
        $this->addElement( 'text' , 'company' );
        $this->getElement( 'company' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        $this->addDisplayGroup(
                array(
                'person_label' , 'to' , 'fullname_label' , 'fullname' , 'company_label' , 'company'
                ) , 'firstColumn' , array( 'disableLoadDefaultDecorators' => true ) );

        $aFirstColumnDecorator = array(
                array( 'FormElements' ) ,
                array( array( 'ul' => 'HtmlTag' ) , array( 'tag' => 'ul' ) ) ,
                array( array( 'div' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'contact-div-first-col' ) ) ,
                array( array( 'prependDiv' => 'HtmlTag' ) , array( 'tag' => 'div' , 'openOnly' => true , 
                        'placement' => 'prepend', 'class'=>'contact-div-fieldsrow' ) )
        );

        $this->getDisplayGroup( 'firstColumn' )->setDecorators( $aFirstColumnDecorator );

        //contact
        $oContactNote = new Zend_Form_Element_Note( 'contact_label' );
        $oContactNote->setValue( 'Telephone Number' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oContactNote );

        //contact field
        $this->addElement( 'text' , 'contact' );
        $this->getElement( 'contact' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( new Zend_Validate_Digits() )
                ->addValidator( new Zend_Validate_StringLength( array( 'min' => 7 , 'max' => 11 ) ) )
                ->setDecorators( $aFieldDecorators );

        //email address
        $oEmailNote = new Zend_Form_Element_Note( 'email_label' );
        $oEmailNote->setValue( 'Email Address' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oEmailNote );

        //email address field
        $this->addElement( 'text' , 'emailadd' );
        $this->getElement( 'emailadd' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $oEmailValidator )
                ->setDecorators( $aFieldDecorators );

        //address
        $oAddressNote = new Zend_Form_Element_Note( 'address_label' );
        $oAddressNote->setValue( 'Address' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oAddressNote );

        //address field
        $this->addElement( 'text' , 'address' );
        $this->getElement( 'address' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        //message
        $oMessageNote = new Zend_Form_Element_Note( 'message_label' );
        $oMessageNote->setValue( 'Message' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oMessageNote );

        $this->addElement( 'textarea' , 'message' );
        $this->getElement( 'message' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        //login button
        $this->addElement( 'submit' , 'Send' );
        $this->getElement( 'Send' )->setDecorators( $aButtonDecorators );

        $this->addDisplayGroup(
                array(
                'contact_label' , 'contact' , 'email_label' ,
                'emailadd' , 'address_label' , 'address'
                ) , 'secondColumn' , array( 'disableLoadDefaultDecorators' => true ) );

        $aSecondColumnDecorator = array(
                array( 'FormElements' ) ,
                array( array( 'ul' => 'HtmlTag' ) , array( 'tag' => 'ul' ) ) ,
                array( array( 'div' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'contact-div-second-col' ) ),
                array( array( 'appendDiv' => 'HtmlTag' ) , array( 'tag' => 'div' , 'closeOnly' => true , 'placement' => 'append' ) )
        );

        $this->getDisplayGroup( 'secondColumn' )->setDecorators( $aSecondColumnDecorator );
        
        
        $this->addDisplayGroup(
                array(
                'message_label' , 'message', 'Send'
                ) , 'messageRow' , array( 'disableLoadDefaultDecorators' => true ) );
        $aMessageRowDecorator = array(
                array( 'FormElements' ) ,
                array( array( 'ul' => 'HtmlTag' ) , array( 'tag' => 'ul' ) ),
                array( array( 'div' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'contact-div-messagerow' ) )
        );
        $this->getDisplayGroup( 'messageRow' )->setDecorators( $aMessageRowDecorator );
        
        $this->setDecorators(
                array( 'FormElements' ,
                        array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'contact-div' ) ) ,
                        'Form'
                )
        );
    }

}
