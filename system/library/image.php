<?php
class Image {
    private $file;
    private $image;
    private $width;
    private $height;
    private $bits;
    private $mime;
    public function __construct($file) {
        if (is_file($file)) {
            $this->file = $file;
            $this->image = new Imagick($file);
            $this->width  = $this->image->getImageWidth();
            $this->height = $this->image->getImageHeight();
            $this->bits = $this->image->getImageLength();
            $this->mime = $this->image->getImageFormat();
        } else {
            exit('Error: Could not load image ' . $file . '!');
        }
    }
    public function getFile() {
        return $this->file;
    }
    public function getImage() {
        return $this->image;
    }
    public function getWidth() {
        return $this->width;
    }
    public function getHeight() {
        return $this->height;
    }
    public function getBits() {
        return $this->bits;
    }
    public function getMime() {
        return $this->mime;
    }
    public function save($file, $quality = 10) {


        if (!class_exists('Mobile_Detect')) {
            loadlibrary('md/mobile_detect');
        }

        $detect = new Mobile_Detect;

        $quality_jpeg = 90;

        if ($detect->isMobile()) {
            $quality_jpeg = 50;
        }


        if (strtolower($this->mime) == 'jpeg') {
            $this->image->setImageFormat('jpeg');
            $this->image->setImageCompression(Imagick::COMPRESSION_JPEG);
            $this->image->setImageCompressionQuality($quality_jpeg);
            $this->image->SetImageInterlaceScheme(Imagick::INTERLACE_LINE);
        }
        if (strtolower($this->mime) == 'png') {
            $this->image->setImageFormat('png');
            $this->image->setBackgroundColor(new ImagickPixel('transparent'));
            $this->image->setImageCompression(\Imagick::COMPRESSION_LZW); // int(11)
            $this->image->setImageCompressionQuality(9);
        }



        $this->image->writeImage($file);
    }
    public function resize($width = 0, $height = 0, $default = '') {
        if (!$this->width || !$this->height) {
            return;
        }

        $this->image->setbackgroundcolor('rgb(255, 255, 255)');
        $this->image->thumbnailImage($width, $height, true, true);

        $this->width = $width;
        $this->height = $height;
    }
    public function watermark($watermark, $position = 'bottomright') {

        $watermark = new Imagick($watermark);
        $watermark->setImageFormat('png');
        $watermark->setBackgroundColor(new ImagickPixel('transparent'));
        $watermark->thumbnailImage($this->width, $this->height, true);

        $watermark_pos_x = 0;
        $watermark_pos_y = 0;
        switch($position) {
            case 'topleft':
                $watermark_pos_x = 0;
                $watermark_pos_y = 0;
                break;
            case 'topright':
                $watermark_pos_x = $this->width - $this->getWidth();
                $watermark_pos_y = 0;
                break;
            case 'bottomleft':
                $watermark_pos_x = 0;
                $watermark_pos_y = $this->height - $this->getHeight();
                break;
            case 'bottomright':
                $watermark_pos_x = $this->width - $this->getWidth();
                $watermark_pos_y = $this->height - $this->getHeight();
                break;
            case 'middle':
                $watermark_pos_x = ($this->width - $this->getWidth()) / 2;
                $watermark_pos_y = ($this->height - $this->getHeight()) / 2;
        }

        $this->image->compositeImage($watermark, imagick::COMPOSITE_OVER, $watermark_pos_x, $watermark_pos_y);
        // $this->image = $watermark;
    }
    public function crop($top_x, $top_y, $bottom_x, $bottom_y) {
        $this->width = $bottom_x - $top_x;
        $this->height = $bottom_y - $top_y;
        $this->image->cropImage($top_x, $top_y, $bottom_x, $bottom_y);
    }
    public function rotate($degree, $color = '#FFFFFF') {
        $rgb = $this->html2rgb($color);
        $this->image->rotateImage(new ImagickPixel($rgb), $degree);
    }
    private function filter($filter) {
        imagefilter($this->image, $filter);
    }
    private function text($text, $x = 0, $y = 0, $size = 5, $color = '000000') {
        $draw = new ImagickDraw();
        $draw->setFontSize($size);
        $draw->setFillColor(new ImagickPixel($this->html2rgb($color)));
        $this->image->annotateImage($draw, $x, $y, 0, $text);
    }
    private function merge($merge, $x = 0, $y = 0, $opacity = 100) {
        $merge->getImage->setImageOpacity($opacity / 100);
        $this->image->compositeImage($merge, imagick::COMPOSITE_ADD, $x, $y);
    }
    private function html2rgb($color) {
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }
        if (strlen($color) == 6) {
            list($r, $g, $b) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array($r, $g, $b);
    }
    function __destruct() {
    }



}