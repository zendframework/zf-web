<?php
/**
 * Dynamic Image Text Replace
 *
 * @author  Ben Scholzen 'DASPRiD' <mail@dasprids.de>
 * @author  Matthew Weier O'Phinney <matthew@zend.com>
 * @version $Id$
 */
class ZfWeb_DynamicHeader_ImageGenerator
{
    /**
     * Font file to use for replacement
     * @var string
     */
    protected $_fontFile = 'caecilia.ttf';

    /**
     * Path to fonts
     * @var string
     */
    protected $_fontPath;

    /**
     * Text of the image
     * @var string
     */
    protected $_text = '';

    /**
     * Size of the text
     * @var int
     */
    protected $_size = 10;

    /**
     * Color of the text
     * @var int
     */
    protected $_color = array(0, 0, 0);

    /**
     * Max width of image
     * @var int
     */
    protected $_width;

    /**
     * Set up DITR
     *
     * @return void
     */
    public function __construct()
    {
    }
    
    /**
     * Set font file to use
     * 
     * @param  string $fontFile 
     * @return ZfWeb_DynamicHeader_ImageGenerator
     */
    public function setFontFile($fontFile)
    {
        $this->_fontFile = (string) $fontFile;
        return $this;
    }

    /**
     * Retrieve font file, including path
     * 
     * @return string
     */
    public function getFontFile()
    {
        return $this->getFontPath() . '/' . $this->_fontFile;
    }

    /**
     * Set font path
     * 
     * @param  string $fontPath 
     * @return ZfWeb_DynamicHeader_ImageGenerator
     */
    public function setFontPath($fontPath)
    {
        if (!file_exists($fontPath) && is_dir($fontPath)) {
            throw new Exception(sprintf('Invalid font path "%s" specified', (string) $fontPath));
        }
        $this->_fontPath = (string) $fontPath;
        return $this;
    }

    /**
     * Retrieve font path
     *
     * If null, sets sane default
     * 
     * @return string
     */
    public function getFontPath()
    {
        if (null === $this->_fontPath) {
            $fontPath = dirname(__FILE__) . '/../../../app/var/fonts/';
            $this->setFontPath($fontPath);
        }
        return $this->_fontPath;
    }

    /**
     * Set the text
     *
     * @param string $text
     * @return DynamicImageTextReplace
     */
    public function setText($text)
    {
        $this->_text = (string) $text;

        return $this;
    }

    /**
     * Set the text size in Pixel
     *
     * @param int $size
     * @return DynamicImageTextReplace
     */
    public function setFontSize($size)
    {
        $this->_size = (int) $size / 96 * 72;

        return $this;
    }

    /**
     * Set the text color
     *
     * @param  int|string $color
     * @return DynamicImageTextReplace
     */
    public function setFontColor($color)
    {
        if ('rgb(' == substr($color, 0, 4)) {
            $color = substr($color, 4, strlen($color) - 1);
            list($red, $green, $blue) = explode(',', $color);
            foreach (array('red', 'green', 'blue') as $color) {
                $$color = $this->_filterColor(trim($$color));
            }
            $this->_color = compact('red', 'green', 'blue');
        } elseif ('#' == substr($color, 0, 1)) {
            $red          = $this->_filterColor(substr($color, 1, 2));
            $green        = $this->_filterColor(substr($color, 3, 2));
            $blue         = $this->_filterColor(substr($color, 5, 2));
            $this->_color = compact('red', 'green', 'blue');
        } else {
            $this->_color = $this->_filterColor($color);
        }

        return $this;
    }

    /**
     * Retrieve args
     *
     * @return mixed
     */
    public function getArgs()
    {
        return $this->_args;
    }

    /**
     * Set args
     *
     * @param mixed $args
     * @return self
     */
    public function setArgs($args)
    {
        $this->_args = $args;
        return $this;
    }

    /**
     * Set width
     *
     * @param  int $width
     * @return DynamicImageTextReplace
     */
    public function setWidth($width)
    {
        $this->_width = (int) $width;
        return $this;
    }

    /**
     * Retrieve width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * Render the image
     *
     * @return string Image filename
     */
    public function generate()
    {

        $image = $this->_createImage();

        // If image is larger than bounding box, resize.
        if (imagesx($image) > $this->getWidth()) {
            $image = $this->scaleImage($image);
        }

        // Save the image
        ob_start();
        imagepng($image);
        $imageContents = ob_get_clean();
        imagedestroy($image);

        return $imageContents;
    }

    /**
     * Scale the image
     * 
     * @param  resource $image 
     * @return resource
     */
    public function scaleImage($image)
    {
        $width     = $this->getWidth();
        $curHeight = imagesy($image);
        $curWidth  = imagesx($image);
        $ratio     = $curHeight / $curWidth;

        if ($width < $curWidth) {
            $this->formatText($width / $curWidth);
            return $this->_createImage();
        }

        $height    = $width * $ratio;
        $newImage  = imagecreatetruecolor($width, $height);

        // Create transparency
        imagesavealpha($newImage, true);
        $transColor = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
        imagefill($newImage, 0, 0, $transColor);

        // Resize image
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $curWidth, $curHeight);

        return $newImage;
    }

    /**
     * Format text
     *
     * Formats text to fit in specified width by inserting line breaks.
     * 
     * @param  float $ratio 
     * @return void
     */
    public function formatText($ratio)
    {
        $stringLength = strlen($this->_text);
        $pos = floor($ratio * $stringLength);
        $stringLength = strlen($this->_text);
        $string = wordwrap($this->_text, $pos);
        $this->setText($string);
    }
    
    /**
     * Filter a color
     *
     * Determines if color is in hex, and converts to decimal. Also ensures
     * color is in range 0 -> 255.
     * 
     * @param  string|int $value 
     * @return int
     */
    protected function _filterColor($value)
    {
        if (preg_match('/[a-f]/i', $value)) {
            $value = hexdec($value);
        }

        $value = (int) $value;

        if (0 > $value) {
             return 0;
        }

        if (255 < $value) {
            return $value;
        }

        return $value;
    }

    /**
     * This function extends imagettfbbox and includes within the returned array
     * the actual text width and height as well as the x and y coordinates the
     * text should be drawn from to render correctly. This currently only works
     * for an angle of zero and corrects the issue of hanging letters e.g. jpqg.
     *
     * @param int $size
     * @param int $angle
     * @param string $fontfile
     * @param string $text
     */
    protected function _imageTtfBbox($size, $angle, $fontfile, $text)
    {
        $bbox = imagettfbbox($size, $angle, $fontfile, $text);

        // Calculate x baseline
        if ($bbox[0] >= -1) {
            $bbox['x'] = abs($bbox[0] + 1) * -1;
        } else {
            $bbox['x'] = abs($bbox[0] + 2);
        }

        // Calculate actual text width
        $bbox['width'] = abs($bbox[2] - $bbox[0]);
        if ($bbox[0] < -1) {
            $bbox['width'] = abs($bbox[2]) + abs($bbox[0]) - 1;
        }

        // Calculate y baseline
        $bbox['y'] = abs($bbox[5] + 1);

        // Calculate actual text height
        $bbox['height'] = abs($bbox[7]) - abs($bbox[1]);
        if ($bbox[3] > 0) {
            $bbox['height'] = abs($bbox[7] - $bbox[1]) - 1;
        }

        return $bbox;
    }

    /**
     * Generate image with text
     * 
     * @return resource
     */
    protected function _createImage()
    {
        
        // Get the correct bounding box of the text
        $bbox = $this->_imageTtfBbox($this->_size, 0, $this->getFontFile(), $this->_text);

        // Create the image
        $image = imagecreatetruecolor($bbox['width'], $bbox['height']);

        // Preserve image transparency
        imagesavealpha($image, true);
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);

        // Write the text
        if (is_array($this->_color)) {
            $color = imagecolorallocate($image, $this->_color['red'], $this->_color['green'], $this->_color['blue']);
        } else {
            $color = $this->_color;
        }
        imagettftext($image, $this->_size, 0, $bbox['x'], $bbox['y'], $color, $this->getFontFile(), $this->_text);

        return $image;
    }
}
