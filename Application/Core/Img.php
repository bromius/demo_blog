<?php

namespace Application\Core;

/**
 * Images core module
 */
class Img
{
    protected $_image;
    protected $_imageType = '';
    protected $_width = 0;
    protected $_height = 0;

    public function load($fileName)
    {
        list ($this->_width, $this->_height, $this->_imageType) = getimagesize($fileName);

        if ($this->_imageType == IMAGETYPE_JPEG)
            $this->_image = imagecreatefromjpeg($fileName);
        else if ($this->_imageType == IMAGETYPE_GIF)
            $this->_image = imagecreatefromgif($fileName);
        else if ($this->_imageType == IMAGETYPE_PNG)
            $this->_image = imagecreatefrompng($fileName);

        return $this;
    }

    public function width($width = null)
    {
        if (!$width)
            return $this->_width;

        $ratio = $width / imagesx($this->_image);
        $height = imagesy($this->_image) * $ratio;
        $this->resize($width, $height);

        return $this;
    }

    public function height($height = null)
    {
        if (!$height)
            return $this->_height;

        $ratio = $height / imagesy($this->_image);
        $width = imagesx($this->_image) * $ratio;
        $this->resize($width, $height);

        return $this;
    }

    function scale($scale)
    {
        $width = $this->width() * $scale / 100;
        $height = $this->height() * $scale / 100;

        $this->resize($width, $height);

        return $this;
    }

    function resize($width, $height)
    {
        $newImage = imagecreatetruecolor($width, $height);

        imagecopyresampled($newImage, $this->_image, 0, 0, 0, 0, $width, $height, $this->width(), $this->height());

        $this->_image = $newImage;
        $this->_width = $width;
        $this->_height = $height;

        return $this;
    }

    /**
     * Save image
     * 
     * @param type $filePath - File save path
     * @param type $type - IMAGETYPE_JPEG | IMAGETYPE_PNG | IMAGETYPE_GIF
     * @param type $compression - 0 = worst / smaller file, 100 = better / bigger file 
     */
    public function save($filePath, $type = IMAGETYPE_JPEG, $compression = 75)
    {
        if ($type == IMAGETYPE_JPEG) {
            // If image type is PNG, use special way to convert it correctly to JPG (transparent background problem)
            if ($this->_imageType == IMAGETYPE_PNG) {
                $bg = imagecreatetruecolor($this->width(), $this->height());
                imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
                imagealphablending($bg, true);
                imagecopy($bg, $this->_image, 0, 0, 0, 0, $this->width(), $this->height());
                imagejpeg($bg, $filePath, $compression);
                imagedestroy($bg);
            } else {
                imagejpeg($this->_image, $filePath, $compression);
            }
        } else if ($type == IMAGETYPE_GIF) {
            imagegif($this->_image, $filePath);
        } else if ($type == IMAGETYPE_PNG) {
            imagepng($this->_image, $filePath);
        }
    }

    public function output($type = IMAGETYPE_JPEG)
    {
        if ($type == IMAGETYPE_JPEG)
            imagejpeg($this->_image);
        else if ($type == IMAGETYPE_GIF)
            imagegif($this->_image);
        else if ($type == IMAGETYPE_PNG)
            imagepng($this->_image);
    }
}
