<?php

class Form_ForgotPass extends Zend_Form
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
        $this->setAction( $this->_actionUrl )->setMethod( 'post' )->setAttrib( 'class' , 'simpleForm' );
        $this->clearDecorators();
        $aFieldDecorators = array(
                                array( 'ViewHelper' ) ,
                                array( 'Errors' ) ,
                                array( 
                                        'HtmlTag' , 
                                        array( 'tag' => 'li' ) 
                                ) 
                            );

        $aButtonDecorators =    array(
                                    array( 'ViewHelper' ) ,
                                    array( 
                                            'HtmlTag' , 
                                            array(
                                                'tag' => 'li' ,
                                                'class' => 'submit' 
                                            ) 
                                    ) 
                                );

        $aDefaultDecorators =   array(
                                    array( 'ViewHelper' ) ,
                                    array( 
                                            'HtmlTag' , 
                                            array(
                                                'tag' => 'li' ,
                                                'class' => '' 
                                            ) 
                                    ) 
                                );
        
        $oEmailDBValidator = new Zend_Validate_Db_RecordExists(
                                array(
                                    'table' => 'sys_users',
                                    'field' => 'email_add',
                                    'adapter' => Zend_Registry::get('db')
                                ));
        //reset password
        $oTitleNote = new Zend_Form_Element_Note( 'reset_label' );
        $oTitleNote->setValue( '<h1>Reset password</h1>' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oTitleNote );
        
        //email address
        $oUsernameNote = new Zend_Form_Element_Note( 'email_label' );
        $oUsernameNote->setValue( 'Please enter your email address' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oUsernameNote );
        
        //email address field
        $this->addElement( 'text' , 'email_add' );
        $this->getElement( 'email_add' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $oEmailDBValidator )
                ->setDecorators( $aFieldDecorators );
        
        //login button
        $this->addElement( 'submit' , 'Reset' );
        $this->getElement( 'Reset' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
            array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
            'Form' ) );
    }

}
