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
                ->setAttrib( 'class' , 'form' )
                ->setAttrib( 'id' , 'add-user' );
        $this->clearDecorators();
        $fieldDecorators = array(
                array( 'ViewHelper' ) ,
                array( 'Errors' ) ,
                array(
                        'HtmlTag' ,
                        array( 'tag' => 'li' )
                )
        );

        $buttonDecorators = array(
                array( 'ViewHelper' ) ,
                array(
                        'HtmlTag' ,
                        array(
                                'tag' => 'li' ,
                                'class' => 'submit'
                        )
                )
        );

        $defaultDecorators = array(
                array( 'ViewHelper' ) ,
                array(
                        'HtmlTag' ,
                        array(
                                'tag' => 'li' ,
                                'class' => ''
                        )
                )
        );

        $noDuplicateEmailValidator = new Custom_Validate_NoDuplicateEmailAddress(
                array(
                'table' => 'sys_users' ,
                'field' => 'email_add' ,
                'adapter' => Zend_Registry::get( 'db' )
                ) );

        $uniqueUsernameValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                'table' => 'sys_user_roles' ,
                'field' => 'username' ,
                'adapter' => Zend_Registry::get( 'db' )
                ) );

        $emailFormatValidator = new Zend_Validate_EmailAddress();

        $initialLengthValidator = new Zend_Validate_StringLength( array( 'min' => 1 , 'max' => 1 ) );

        //personal information
        $titleNote = new Zend_Form_Element_Note( 'personal_info' );
        $titleNote->setValue( '<h3>Personal Information</h3>' )->setDecorators( $defaultDecorators );
        $this->addElement( $titleNote );

        //lastname
        $lastnameNote = new Zend_Form_Element_Note( 'lastname_label' );
        $lastnameNote->setValue( 'Lastname' )->setDecorators( $defaultDecorators );
        $this->addElement( $lastnameNote );

        //lastname field
        $this->addElement( 'text' , 'lastname' );
        $this->getElement( 'lastname' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $fieldDecorators );

        //firstname
        $firstnameNote = new Zend_Form_Element_Note( 'firstname_label' );
        $firstnameNote->setValue( 'Firstname' )->setDecorators( $defaultDecorators );
        $this->addElement( $firstnameNote );

        //firstname field
        $this->addElement( 'text' , 'firstname' );
        $this->getElement( 'firstname' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $fieldDecorators );

        //middle initial
        $middleInitialNote = new Zend_Form_Element_Note( 'mi_label' );
        $middleInitialNote->setValue( 'Middle Initial' )->setDecorators( $defaultDecorators );
        $this->addElement( $middleInitialNote );

        //middle initial field
        $this->addElement( 'text' , 'mi' );
        $this->getElement( 'mi' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $initialLengthValidator )
                ->setDecorators( $fieldDecorators );

        
        //designation field
        $this->addElement( 'hidden' , 'designation' );
        $this->getElement( 'designation' )->setValue( '' );

        //contact
        $contactNote = new Zend_Form_Element_Note( 'contact_label' );
        $contactNote->setValue( 'Mobile number' )->setDecorators( $defaultDecorators );
        $this->addElement( $contactNote );

        //contact field
        $this->addElement( 'text' , 'contact' );
        $this->getElement( 'contact' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( new Zend_Validate_Digits() )
                ->addValidator( new Zend_Validate_StringLength( array( 'min' => 7 , 'max' => 11 ) ) )
                ->setDecorators( $fieldDecorators );
                
        //email address
        $emailNote = new Zend_Form_Element_Note( 'email_label' );
        $emailNote->setValue( 'Email address' )->setDecorators( $defaultDecorators );
        $this->addElement( $emailNote );

        //email address field
        $this->addElement( 'text' , 'emailadd' );
        $this->getElement( 'emailadd' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $noDuplicateEmailValidator )
                ->addValidator( $emailFormatValidator )
                ->setDecorators( $fieldDecorators );

        //account information
        $accountNote = new Zend_Form_Element_Note( 'account_info' );
        $accountNote->setValue( '<h3>Account Information</h3>' )->setDecorators( $defaultDecorators );
        $this->addElement( $accountNote );

        //username
        $usernameNote = new Zend_Form_Element_Note( 'username_label' );
        $usernameNote->setValue( 'Username' )->setDecorators( $defaultDecorators );
        $this->addElement( $usernameNote );

        //username field
        $this->addElement( 'text' , 'username' );
        $this->getElement( 'username' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $uniqueUsernameValidator )
                ->setDecorators( $fieldDecorators );

        //password
        $passwordNote = new Zend_Form_Element_Note( 'password_label' );
        $passwordNote->setValue( 'Password' )->setDecorators( $defaultDecorators );
        $this->addElement( $passwordNote );

        //password field
        $this->addElement( 'password' , 'password' );
        $this->getElement( 'password' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $fieldDecorators );

        //role
        $roleNote = new Zend_Form_Element_Note( 'role_label' );
        $roleNote->setValue( 'Role' )->setDecorators( $defaultDecorators );
        $this->addElement( $roleNote );

        //role field
        $roles = new Zend_Form_Element_Select( 'roles' );
        $roles->setLabel( "Roles" )->addMultiOptions( array( '0' => '' , '1' => 'Administrator' , '2' => 'Scrum Master', '3' => 'Member' ) );
        $roles->setDecorators( $fieldDecorators );
        $this->addElement( $roles );

        //login button
        $this->addElement( 'submit' , 'save' );
        $this->getElement( 'save' )->setLabel( 'Save' )->setDecorators( $buttonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
