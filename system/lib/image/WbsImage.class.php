<?php
/**
 * Class uses syntax Imagick lib for working with images.
 * @see http://ru2.php.net/imagick
 * @see http://ru2.php.net/gd
 * @method WbsImage clone() clone imager lib object
 */
class WbsImage 
{
    public $lib = null;
    private $isImagick = false;
    private $isGd = false;
    private $filePath = null;
    
    const LIB_GD = "gd"; 
    const LIB_IMAGICK = "imagick";
    
    public function __construct($filePath = null, $lib = "imagick") 
    {
        $this->lib = $this->openImage($filePath, $lib);
        $this->filePath = $filePath;
    }
    /**
     * @param string $filePath
     * @return WbsImage
     */
    public function openImage($file = null, $lib = "imagick")
    {    	
    	if ($lib == self::LIB_IMAGICK) {
	        if ( extension_loaded( "imagick" ) ) {
	            $this->isImagick = true;

	            return (is_null($file)) ? new Imagick() : new Imagick($file);
	        }
	        else if( extension_loaded( "gd" ) ) {
	            $this->isGd = true;
	            return new WbsImageGd($file);
	        }
    	}
    	if ($lib == self::LIB_GD) {
    		if( extension_loaded( "gd" ) ) {
	            $this->isGd = true;
	            return new WbsImageGd($file);
	        }
	        else if ( extension_loaded( "imagick" ) ) {
	            $this->isImagick = true;
	            return new Imagick($file);
	        }
    	}
    	    	
    	return $this;
    }
    /**
     * @return Boolen
     */
    public function isImagick()
    {
        return $this->isImagick;
    }
    
    public function getImageSize()
    {
		$this->lib->getImageSize();    	
    }
    
    /**
     * @return Boolen
     */
    public function isGd()
    {
        return $this->isGd;
    }
    /**
     * @return mixed - Imagick or Gd lib
     * @see isImagick() and isGd()
     */
    public function getLib()
    {
        $this->lib;
    }
    /**
     * @param mixed $lib - Imagick or Gd lib
     */
    public function setLib($lib)
    {
        $this->lib = $lib;
    }
    public function setLibType($type)
    {
    	if ( $type == self::LIB_GD )
    		$this->isGd = true;
    	else if ( $type == self::LIB_IMAGICK )
    		$this->isImagick = true;
    }
    
    /**
     * @param int $width
     * @param int $height
     * @param Boolean $isFit
     * @return WbsImage
     */
    public function thumbnailImage($width, $height, $isFit = false) 
    {
    	if ( $this->isImagick() )
    		$this->lib->thumbnailImage($width, $height, $isFit);
    	else {
    		$this->lib->resize($width, $height);
    	}
        return $this;
    }
    
    /**
     * @param int $width
     * @param int $height
     * @return WbsImage
     */
    public function cropThumbnailImage($width, $height)
    {
    	if ( $this->isImagick() )
        	$this->lib->cropThumbnailImage($width, $height);
        else 	
			$this->lib->thumbnailImage($width, $height);
        return $this;        
    }
    
    public function cropImage($width  , $height  , $x  , $y)
    {
    	if ( $this->isImagick() )
        	$this->lib->cropImage($width  , $height  , $x  , $y);
			
        return $this; 
    }
    
    /**
     * @param int $degrees
     * @return WbsImage
	 */
    public function rotateImage($degrees)
    {
    	if( $this->isImagick )
        	$this->lib->rotateImage(new ImagickPixel('white'), $degrees);
        else
        	$this->lib->rotateImage($degrees);
        return $this;
    }

    /**
     * @return int
     */
    public function getImageWidth()
    {
        return $this->lib->getImageWidth();
    }
   
    
	function __call($m, $a) {
	    if ( $m == 'clone' ) {
	        $image = new self();
	        if ( $this->isImagick() ) {
	        	$image->setLib( $this->lib->clone() );
	        	$image->setLibType( self::LIB_IMAGICK );
	        }
	        else if ( $this->isGd() ) {
	        	$image->setLib( new WbsImageGd( $this->filePath ) );
	        	$image->setLibType( self::LIB_GD );
	        }
	        return $image;
	    }
  	}    
    
    /**
     * @return int
     */
    public function getImageHeight()
    { 
        return $this->lib->getImageHeight(); 
    }

    /**
     * @param string $filename
     * @return WbsImage
     **/
    public function writeImage($filename) 
    {
//        if ( $this->isImagick() )
//            $this->lib->sharpenImage(0.5, 1.0);
            
        $this->lib->writeImage($filename); 
        return $this;
    }

    public function destroy()
    {
    	$this->lib->destroy();
    }
    
    /**
     * @param WbsImage $im
     * @param int $type
     * @param int $x
     * @param int $y
     * @return WbsImage
     */
    public function compositeImage( $im, $type, $x, $y )
    {
    	if( $this->isImagick() )
    		$this->lib->compositeImage( $im->lib, $type, $x, $y );
    	else if ( $this->isGd() ) {
    	    $this->lib->compositeImage( $im->lib,  $x, $y );
    	}
    	return $this;
    }
    
    public function newImage( $width, $height, $background, $format )
    {
		if( $this->isImagick() )
    		$this->lib->newImage( $width, $height, $background, $format );
    	else if ( $this->isGd() ) {
            $this->lib->newImage( $width, $height );
    	}
    	
    	return $this;    	
    }
    
}

?>