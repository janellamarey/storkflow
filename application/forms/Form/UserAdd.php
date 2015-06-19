<?php

class Form_UserAdd extends Zend_Form
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
                ->setAttrib( 'id' , 'saveAdd' );
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

        $oEmailDBValidator = new Custom_Validate_NoDuplicateEmailAddress(
                array(
                'table' => 'sys_users' ,
                'field' => 'email_add' ,
                'adapter' => Zend_Registry::get( 'db' )
                ) );

        $oUsernameDBValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                'table' => 'sys_user_roles' ,
                'field' => 'username' ,
                'adapter' => Zend_Registry::get( 'db' )
                ) );

        $oEmailValidator = new Zend_Validate_EmailAddress();

        $oInitialValidator = new Zend_Validate_StringLength( array( 'min' => 1 , 'max' => 1 ) );

        //personal information
        $oTitleNote = new Zend_Form_Element_Note( 'personal_info' );
        $oTitleNote->setValue( '<h3>Personal Information</h3>' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oTitleNote );

        //lastname
        $oLastnameNote = new Zend_Form_Element_Note( 'lastname_label' );
        $oLastnameNote->setValue( 'Lastname' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oLastnameNote );

        //lastname field
        $this->addElement( 'text' , 'lastname' );
        $this->getElement( 'lastname' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        //firstname
        $oFirstnameNote = new Zend_Form_Element_Note( 'firstname_label' );
        $oFirstnameNote->setValue( 'Firstname' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oFirstnameNote );

        //firstname field
        $this->addElement( 'text' , 'firstname' );
        $this->getElement( 'firstname' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        //middle initial
        $oMINote = new Zend_Form_Element_Note( 'mi_label' );
        $oMINote->setValue( 'Middle Initial' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oMINote );

        //middle initial field
        $this->addElement( 'text' , 'mi' );
        $this->getElement( 'mi' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $oInitialValidator )
                ->setDecorators( $aFieldDecorators );

        //designation
        $oDesignationNote = new Zend_Form_Element_Note( 'designation_label' );
        $oDesignationNote->setValue( 'Designation' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oDesignationNote );

        //designation field
        $oDesignation = new Zend_Form_Element_Select( 'designation' );
        $oDesignation->setLabel( "Designation" )->addMultiOptions( array( '' => '' ,
                'Jr' => 'Jr' , 'Sr' => 'Sr' , 'III' => 'III' , 'IV' => 'IV' , 'V' => 'V' ) );
        $oDesignation->setDecorators( $aFieldDecorators );
        $this->addElement( $oDesignation );

        //address
        $oAddressNote = new Zend_Form_Element_Note( 'address_label' );
        $oAddressNote->setValue( 'Address' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oAddressNote );

        //address field
        $this->addElement( 'text' , 'address' );
        $this->getElement( 'address' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        //contact
        $oContactNote = new Zend_Form_Element_Note( 'contact_label' );
        $oContactNote->setValue( 'Contact number' )->setDecorators( $aDefaultDecorators );
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
        $oEmailNote->setValue( 'Email address' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oEmailNote );

        //email address field
        $this->addElement( 'text' , 'emailadd' );
        $this->getElement( 'emailadd' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $oEmailDBValidator )
                ->addValidator( $oEmailValidator )
                ->setDecorators( $aFieldDecorators );

        //account information
        $oAccountNote = new Zend_Form_Element_Note( 'account_info' );
        $oAccountNote->setValue( '<h3>Account Information</h3>' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oAccountNote );

        //username
        $oUsernameNote = new Zend_Form_Element_Note( 'username_label' );
        $oUsernameNote->setValue( 'Username' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oUsernameNote );

        //username field
        $this->addElement( 'text' , 'username' );
        $this->getElement( 'username' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $oUsernameDBValidator )
                ->setDecorators( $aFieldDecorators );

        //password
        $oPasswordNote = new Zend_Form_Element_Note( 'password_label' );
        $oPasswordNote->setValue( 'Password' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oPasswordNote );

        //password field
        $this->addElement( 'password' , 'password' );
        $this->getElement( 'password' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        //role
        $oRoleNote = new Zend_Form_Element_Note( 'role_label' );
        $oRoleNote->setValue( 'Role' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oRoleNote );

        //role field
        $oRoles = new Zend_Form_Element_Select( 'roles' );
        $oRoles->setLabel( "Roles" )->addMultiOptions( array( '0' => '' , '1' => 'Super Administrator' ,
                '2' => 'Super User' , '3' => 'Administrator' , '4' => 'Voter' , '5' => 'Councilor' ) );
        $oRoles->setDecorators( $aFieldDecorators );
        $this->addElement( $oRoles );

        //district
        $oDistrictNote = new Zend_Form_Element_Note( 'district_label' );
        $oDistrictNote->setValue( 'District' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oDistrictNote );

        //districts
        $oDistricts = new Zend_Form_Element_Select( 'districts' );
        $oDistricts->setLabel( "Districts" )->addMultiOptions( array( '0' => '' , '1' => 'District 1' ,
                '2' => 'District 2' ) );
        $oDistricts->setDecorators( $aFieldDecorators );
        $this->addElement( $oDistricts );

        //login button
        $this->addElement( 'submit' , 'Save' );
        $this->getElement( 'Save' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
