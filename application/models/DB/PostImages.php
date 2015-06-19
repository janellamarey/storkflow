<?php

Zend_Loader::loadClass( 'DB_Base' );

class DB_PostImages extends DB_Base
{
    protected $_name = 'b_post_images';

    public function getImagesByPostId( $post_id )
    {
        return $this->fetchAll( 'deleted=0 AND post_id=' . $post_id )->toArray();
    }
}
