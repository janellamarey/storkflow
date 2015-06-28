<?php

class Form_UserEditPersonal extends Zend_Form
{

    protected $_actionUrl;
    protected $userId;

    public function __construct( $actionUrl = null , $options = null )
    {
        parent::__construct( $options );
        $this->_actionUrl = $actionUrl;
    }

    public function setData( array $options = array() )
    {
        if ( $options[ 'user_id' ] )
        {
            $this->userId = $options[ 'user_id' ];
        }
    }

    public function init()
    {
        $this->setAction( $this->_actionUrl )->setMethod( 'post' )
                ->setAttrib( 'class' , 'form' )
                ->setAttrib( 'id' , 'edit-personal' );
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

        $emailValidator = new Zend_Validate_EmailAddress();

        $initialValidator = new Zend_Validate_StringLength( array( 'min' => 1 , 'max' => 1 ) );

        $this->addElement( 'hidden' , 'id' )->setDecorators( $fieldDecorators );

        //personal information
        $titleNote = new Zend_Form_Element_Note( 'personal_info' );
        $titleNote->setValue( '<h3>Personal Information</h3>' )->setDecorators( $defaultDecorators );
        $this->addElement( $titleNote );

        //lastname
        $lastnameLabel = new Zend_Form_Element_Note( 'lastname_label' );
        $lastnameLabel->setValue( 'Lastname' )->setDecorators( $defaultDecorators );
        $this->addElement( $lastnameLabel );

        //lastname field
        $this->addElement( 'text' , 'lastname' );
        $this->getElement( 'lastname' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $fieldDecorators );

        //firstname
        $firstnameLabel = new Zend_Form_Element_Note( 'firstname_label' );
        $firstnameLabel->setValue( 'Firstname' )->setDecorators( $defaultDecorators );
        $this->addElement( $firstnameLabel );

        //firstname field
        $this->addElement( 'text' , 'firstname' );
        $this->getElement( 'firstname' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $fieldDecorators );

        //middle initial
        $miLabel = new Zend_Form_Element_Note( 'mi_label' );
        $miLabel->setValue( 'Middle Initial' )->setDecorators( $defaultDecorators );
        $this->addElement( $miLabel );

        //middle initial field
        $this->addElement( 'text' , 'mi' );
        $this->getElement( 'mi' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $initialValidator )
                ->setDecorators( $fieldDecorators );

        //designation
        $designationLabel = new Zend_Form_Element_Note( 'designation_label' );
        $designationLabel->setValue( 'Designation' )->setDecorators( $defaultDecorators );
        $this->addElement( $designationLabel );

        //designation field
        $designation = new Zend_Form_Element_Select( 'designation' );
        $designation->setLabel( "Designation" )->addMultiOptions( array( '' => '' ,
                'Jr' => 'Jr' , 'Sr' => 'Sr' , 'III' => 'III' , 'IV' => 'IV' , 'V' => 'V' ) );
        $designation->setDecorators( $fieldDecorators );
        $this->addElement( $designation );

        //contact
        $contactLabel = new Zend_Form_Element_Note( 'contact_label' );
        $contactLabel->setValue( 'Contact number' )->setDecorators( $defaultDecorators );
        $this->addElement( $contactLabel );

        //contact field
        $this->addElement( 'text' , 'contacts' );
        $this->getElement( 'contacts' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( new Zend_Validate_Digits() )
                ->addValidator( new Zend_Validate_StringLength( array( 'min' => 7 , 'max' => 11 ) ) )
                ->setDecorators( $fieldDecorators );

        //email address
        $emailLabel = new Zend_Form_Element_Note( 'email_label' );
        $emailLabel->setValue( 'Email address' )->setDecorators( $defaultDecorators );
        $this->addElement( $emailLabel );

        //email address field
        $this->addElement( 'text' , 'email_add' );
        $this->getElement( 'email_add' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $emailValidator )
                ->setDecorators( $fieldDecorators );

        //login button
        $this->addElement( 'submit' , 'save' );
        $this->getElement( 'save' )->setLabel( 'Save' )->setDecorators( $buttonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
