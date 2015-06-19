<?php

class Form_AboutUsEdit extends Zend_Form
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
        $this->setAction( $this->_actionUrl )
                ->setMethod( 'post' )
                ->setAttrib( 'class' , 'simpleForm' )
                ->setAttrib( 'enctype' , 'multipart/form-data' )
                ->setAttrib( 'id' , 'saveAdd' );
        $this->clearDecorators();
        $aFieldDecorators = array(
                array( 'ViewHelper' ) ,
                array( 'Errors' ) ,
                array( 'HtmlTag' , array( 'tag' => 'li' ) )
        );

        $aButtonDecorators = array(
                array( 'ViewHelper' ) ,
                array( 'HtmlTag' , array(
                                'tag' => 'li' ,
                                'class' => 'submit' ) ) );

        $this->addElement( 'hidden' , 'id' );
        $this->getElement( 'id' )->setValue( $this->_id );

        $this->addElement( 'textarea' , 'content' );
        $this->getElement( 'content' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        $oAppConfig = Zend_Registry::get( 'config' );
        $sDestinationFolder = $oAppConfig->upload->root->posts
                . DIRECTORY_SEPARATOR . SiteConstants::$ABOUT_POST_ID;
        if ( !file_exists( $sDestinationFolder ) )
        {
            mkdir( $sDestinationFolder );
        }

        $oFileElement = new Zend_Form_Element_File( 'images' );
        $oFileElement->setLabel( 'Upload an image: ' )
                ->setRequired( true )
                ->setDestination( $sDestinationFolder );
        
        $oFileElement->addValidator( 'Size' , false , 1999999 );
        $oFileElement->addValidator( 'Extension' , false , 'jpg,png' );
        $oFileElement->setDecorators( array( array( 'File' ) , array( 'Errors' ) , array( 'Label' ) , array( 'HtmlTag' , array( 'tag' => 'li' ) ) ) );
        $this->addElement( $oFileElement );

        $this->addElement( 'submit' , 'Save' );
        $this->getElement( 'Save' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
