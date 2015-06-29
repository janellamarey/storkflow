<?php

class Form_TaskEdit extends Zend_Form
{

    protected $_actionUrl;
    protected $taskId;

    public function __construct( $actionUrl = null , $options = null )
    {
        parent::__construct( $options );
        $this->_actionUrl = $actionUrl;
    }

    public function setData( array $options = array() )
    {
        if ( $options[ 'task_id' ] )
        {
            $this->taskId = $options[ 'task_id' ];
        }
    }

    public function init()
    {
        $this->setAction( $this->_actionUrl )->setMethod( 'post' )
                ->setAttrib( 'class' , 'form' )
                ->setAttrib( 'id' , 'edit-task' );
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

        $this->addElement( 'hidden' , 'id' )->setDecorators( $fieldDecorators );
        
        //team information title
        $titleNote = new Zend_Form_Element_Note( 'team-info' );
        $titleNote->setValue( '<h3>Task Information</h3>' )->setDecorators( $defaultDecorators );
        $this->addElement( $titleNote );

        //name label
        $nameLabel = new Zend_Form_Element_Note( 'nameLabel' );
        $nameLabel->setValue( 'Name' )->setDecorators( $defaultDecorators );
        $this->addElement( $nameLabel );

        //name field
        $this->addElement( 'text' , 'name' );
        $this->getElement( 'name' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $fieldDecorators );

        //description label
        $descriptionLabel = new Zend_Form_Element_Note( 'descriptionLabel' );
        $descriptionLabel->setValue( 'Description' )->setDecorators( $defaultDecorators );
        $this->addElement( $descriptionLabel );

        //description text area
        $this->addElement( 'textarea' , 'description' );
        $this->getElement( 'description' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $fieldDecorators );

        //login button
        $this->addElement( 'submit' , 'save' );
        $this->getElement( 'save' )->setLabel( 'Save' )->setDecorators( $buttonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
