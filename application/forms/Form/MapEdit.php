<?php

class Form_MapEdit extends Zend_Form
{

    protected $_actionUrl;
    protected $_id;

    public function __construct( $id , $actionUrl = null , $options = null )
    {
        parent::__construct( $options );
        $this->_actionUrl = $actionUrl;
        $this->_id = $id;
        $this->init();
    }

    public function init()
    {
        $oAppConfig = Zend_Registry::get( 'config' );
        $sDestinationFolder = $oAppConfig->upload->root->maps
                . DIRECTORY_SEPARATOR . $this->_id;
        if ( !file_exists( $sDestinationFolder ) )
        {
            mkdir( $sDestinationFolder );
        }

        $this->setAction( $this->_actionUrl )
                ->setMethod( 'post' )
                ->setAttrib( 'class' , 'simpleForm' )
                ->setAttrib( 'enctype' , 'multipart/form-data' )
                ->setAttrib( 'id' , 'saveAdd' );
        $this->clearDecorators();

        $aButtonDecorators = array(
                array( 'ViewHelper' ) ,
                array( 'HtmlTag' , array(
                                'tag' => 'li' ,
                                'class' => 'submit' ) ) );

        $oFileElement = new Zend_Form_Element_File( 'images' );
        $oFileElement->setLabel( 'Upload an image: ' );
        $oFileElement->setDestination( $sDestinationFolder );
        $oFileElement->addValidator( 'Count' , false , array( 'min' => 0 , 'max' => 3 ) );
        $oFileElement->addValidator( 'Size' , false , 1999999 );
        $oFileElement->addValidator( 'Extension' , false , 'jpg,png' );
        $oFileElement->setMultiFile( 3 );
        $oFileElement->setDecorators( array( array( 'File' ) , array( 'Errors' ) , array( 'HtmlTag' , array( 'tag' => 'li' ) ) ) );

        $this->addElement( $oFileElement );

        $this->addElement( 'submit' , 'Save' );
        $this->getElement( 'Save' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
