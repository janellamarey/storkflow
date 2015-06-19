<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Images
 *
 * @author Josh Auza
 */
class Images
{

    public function __construct()
    {
        $this->oImg = null;
        $this->oTmpImg = null;
        $this->iNewHeight = 200;
        $this->iNewWidth = 200;
        $this->iOldHeight = 0;
        $this->iOldWidth = 0;
    }

    public function makeJPEGThumbnail( $sourcefile , $endfile , $thumbwidth , $thumbheight , $quality )
    {
        $this->oImg = imagecreatefromjpeg( $sourcefile );

        $this->computeDimensions( $thumbwidth , $thumbheight );

        $this->oTmpImg = imagecreatetruecolor( $this->iNewWidth , $this->iNewHeight );

        $this->fastimagecopyresampled( $this->oTmpImg , $this->oImg , 0 , 0 , 0 , 0 , $this->iNewWidth , $this->iNewHeight , $this->iOldWidth , $this->iOldHeight );

        imagejpeg( $this->oTmpImg , $endfile , $quality );

        $this->releaseImageMemory();
    }

    public function makePNGThumbnail( $sourcefile , $endfile , $thumbwidth , $thumbheight , $quality )
    {
        $this->oImg = imagecreatefrompng( $sourcefile );

        $this->computeDimensions( $thumbwidth , $thumbheight );

        $this->oTmpImg = imagecreatetruecolor( $this->iNewWidth , $this->iNewHeight );
        imagealphablending( $this->oTmpImg , false );
        imagesavealpha( $this->oTmpImg , true );

        imagecopyresampled( $this->oTmpImg , $this->oImg , 0 , 0 , 0 , 0 , $this->iNewWidth , $this->iNewHeight , $this->iOldWidth , $this->iOldHeight );

        imagepng( $this->oTmpImg , $endfile , $quality );

        $this->releaseImageMemory();
    }

    private function releaseImageMemory()
    {
        if ( !is_null( $this->oTmpImg ) && !is_null( $this->oImg ) )
        {
            imagedestroy( $this->oTmpImg );
            imagedestroy( $this->oImg );
        }
    }

    public function computeDimensions( $thumbwidth , $thumbheight )
    {
        $this->iOldWidth = imagesx( $this->oImg );
        $this->iOldHeight = imagesy( $this->oImg );

        if ( $this->iOldWidth > $this->iOldHeight )
        {
            $this->iNewWidth = $thumbwidth;
            $divisor = $this->iOldWidth / $thumbwidth;
            $this->iNewHeight = floor( $this->iOldHeight / $divisor );
        }
        else
        {
            $this->iNewHeight = $thumbheight;
            $divisor = $this->iOldHeight / $thumbheight;
            $this->iNewWidth = floor( $this->iOldWidth / $divisor );
        }
    }

    private function fastimagecopyresampled( &$dst_image , $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h , $quality = 3 )
    {
        // Plug-and-Play fastimagecopyresampled function replaces much slower imagecopyresampled.
        // Just include this function and change all "imagecopyresampled" references to "fastimagecopyresampled".
        // Typically from 30 to 60 times faster when reducing high resolution images down to thumbnail size using the default quality setting.
        // Author: Tim Eckel - Date: 09/07/07 - Version: 1.1 - Project: FreeRingers.net - Freely distributable - These comments must remain.
        //
        // Optional "quality" parameter (defaults is 3). Fractional values are allowed, for example 1.5. Must be greater than zero.
        // Between 0 and 1 = Fast, but mosaic results, closer to 0 increases the mosaic effect.
        // 1 = Up to 350 times faster. Poor results, looks very similar to imagecopyresized.
        // 2 = Up to 95 times faster.  Images appear a little sharp, some prefer this over a quality of 3.
        // 3 = Up to 60 times faster.  Will give high quality smooth results very close to imagecopyresampled, just faster.
        // 4 = Up to 25 times faster.  Almost identical to imagecopyresampled for most images.
        // 5 = No speedup. Just uses imagecopyresampled, no advantage over imagecopyresampled.

        if ( empty( $src_image ) || empty( $dst_image ) || $quality <= 0 )
        {
            return false;
        }
        if ( $quality < 5 && (($dst_w * $quality) < $src_w || ($dst_h * $quality) < $src_h) )
        {
            $temp = imagecreatetruecolor( $dst_w * $quality + 1 , $dst_h * $quality + 1 );
            imagecopyresized( $temp , $src_image , 0 , 0 , $src_x , $src_y , $dst_w * $quality + 1 , $dst_h * $quality + 1 , $src_w , $src_h );
            imagecopyresampled( $dst_image , $temp , $dst_x , $dst_y , 0 , 0 , $dst_w , $dst_h , $dst_w * $quality , $dst_h * $quality );
            imagedestroy( $temp );
        }
        else
        {
            imagecopyresampled( $dst_image , $src_image , $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h );
        }
        return true;
    }

    public function getDataURI( $filePath )
    {
        if ( !file_exists( $filePath ) )
        {
            return '';
        }
        $imageData = base64_encode( file_get_contents( $filePath ) );
        $src = 'data:' . $this->getMime( $filePath ) . ';base64,' . $imageData;
        return $src;
    }

    public function getMime( $filename , $mode = 0 )
    {
        if ( function_exists( 'mime_content_type' ) && $mode == 0 )
        {
            $mimetype = mime_content_type( $filename );
            return $mimetype;
        }
        elseif ( function_exists( 'finfo_open' ) && $mode == 0 )
        {
            $finfo = finfo_open( FILEINFO_MIME );
            $mimetype = finfo_file( $finfo , $filename );
            finfo_close( $finfo );
            return $mimetype;
        }
        else
        {
            return 'image/png';
        }
    }

    private function getSize( $sourcefile )
    {
        $info = pathinfo( $sourcefile );
        $image = null;
        if ( $info[ "extension" ] == "jpg" )
        {
            $image = imagecreatefromjpeg( $sourcefile );
        }
        else if ( $info[ "extension" ] == "png" )
        {
            $image = imagecreatefrompng( $sourcefile );
        }
        return array( imagesx( $image ) , imagesy( $image ) );
    }

    public function getScaledSize( $sourcefile , $suggestedWidth , $suggestedHeight )
    {
        $actualSize = $this->getSize( $sourcefile );
        $oldWidth = $actualSize[ 0 ];
        $oldHeight = $actualSize[ 1 ];

        $dimension = array();
        if ( $oldWidth > $oldHeight )
        {
            $dimension[ 0 ] = $suggestedWidth;
            $divisor = $oldWidth / $suggestedWidth;
            $dimension[ 1 ] = floor( $oldHeight / $divisor );
        }
        else
        {
            $dimension[ 1 ] = $suggestedHeight;
            $divisor = $oldHeight / $suggestedHeight;
            $dimension[ 0 ] = floor( $oldWidth / $divisor );
        }
        return $dimension;
    }

    public function getImages( $path , $postid , $numberOfImages = 10 )
    {
        $globarg = $path . DIRECTORY_SEPARATOR . "*.{jpg,png,gif}";
        $tempImages = glob( $globarg , GLOB_BRACE );

        $trimmed = array_slice( $tempImages , 0 , $numberOfImages );
        $result = array();
        foreach ( $trimmed as $path )
        {
            $pathinfo = pathinfo( $path );
            $temp[ 'postid' ] = $postid;
            $temp[ 'path' ] = SiteConstants::convertToSrc( $path );
            $temp[ 'basename' ] = $pathinfo['basename'];
            $temp[ 'filename' ] = $pathinfo['filename'];
            $dimension = $this->getScaledSize( $path , 150 , 150 );
            $temp[ 'width' ] = $dimension[ 0 ];
            $temp[ 'height' ] = $dimension[ 1 ];
            $result[] = $temp;
        }
        return $result;
    }
}
