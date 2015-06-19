<?php

class Form_NewsEdit extends Zend_Form
{

    protected $_actionUrl;
    protected $_id;

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
                array( 'HtmlTag' , array( 'tag' => 'li' ) ) ,
        );

        $aButtonDecorators = array(
                array( 'ViewHelper' ) ,
                array( 'HtmlTag' , array(
                                'tag' => 'li' ,
                                'class' => 'submit' ) ) );

        $this->addElement( 'hidden' , 'id' );

        $this->addElement( 'text' , 'title' );
        $this->getElement( 'title' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        $this->addElement( 'textarea' , 'body' );
        $this->getElement( 'body' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        $oFileElement = new Zend_Form_Element_File( 'images' );
        $oFileElement->setLabel( 'Upload an image: ' );
        $oFileElement->addValidator( 'Size' , false , 1999999 );
        $oFileElement->addValidator( 'Extension' , false , 'jpg,png' );
        $oFileElement->setDecorators( array( array( 'File' ) , array( 'Errors' ) ,  array( 'Label' ), array( 'HtmlTag' , array( 'tag' => 'li' ) ) ) );
        
        $this->addElement( $oFileElement );

        $this->addElement( 'submit' , 'Save' );
        $this->getElement( 'Save' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
