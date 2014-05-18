<?php
namespace CMS\AdminBundle\Api;

class ImageResizeApi
{
    /**
     * @param string $image_path
     */
    public $image_path;

    /**
     * @param string $thumb_path
     */
    public $thumb_path;

    /**
     * @param string $image_name
     */
    public $image_name;

    /**
     * @param string $image_path
     */
    public $thumb_name;

    /**
     * @param string $image_path
     */
    public $dimension;

    /**
     * @param int (1 or 0) $square
     */
    public $square;

    /**
     * @param int (0 - 100) $quality
     */
    public $quality;

    public function __construct($image_path, $thumb_path, $image_name, $thumb_name, $dimension, $square=0, $quality=90)
    {
        $this->image_path = $image_path;
        $this->thumb_path = $thumb_path;
        $this->image_name = $image_name;
        $this->thumb_name = $thumb_name;
        $this->dimension  = $dimension;
        $this->square     = $square;
        $this->quality    = $quality;
    }

    public function resize()
    {
        $type = strtolower(substr($this->image_name,-3));
        switch ($type) {
            case 'jpg':
                $src = imagecreatefromjpeg("$this->image_path/$this->image_name");
                break;
            case 'gif':
                $src = imagecreatefromgif("$this->image_path/$this->image_name");
                break;
            case 'png':
                $src = imagecreatefrompng("$this->image_path/$this->image_name");
                break;
            default:
                if(strtolower(substr($this->image_name,-4)) == 'jpeg'){
                    $src = imagecreatefromjpeg("$this->image_path/$this->image_name");
                }else{
                    die('Image upload not format correct!');
                }
                break;
        }

        $ow=imagesx($src);
        $oh=imagesy($src);
        $src_x = 0;
        $src_y = 0;
        if($ow>$oh) {
            if($ow>$this->dimension) {
                $nw = $this->dimension;
                $nh = (int)$oh*$nw/$ow;
            } else {
                $nw = $ow;
                $nh = (int)$oh*$nw/$ow;
            }
        } else {
            if($oh>$this->dimension) {
                $nh = $this->dimension;
                $nw = (int)$ow*$nh/$oh;
            } else {
                $nh = $oh;
                $nw = (int)$ow*$nh/$oh;
            }
        }
        if($this->square) {
            $length = min($ow,$oh);
            $src_x = ceil( $ow / 2 ) - ceil( $length / 2 );
            $src_y = ceil( $oh / 2 ) - ceil( $length / 2 );
            $nlength = max($nw,$nh);
            $nw = $nlength;
            $nh = $nlength;
            $ow = $length;
            $oh = $length;
        }
        $dst = imagecreatetruecolor($nw,$nh);
        imagecopyresampled($dst,$src,0,0,$src_x,$src_y,$nw,$nh,$ow,$oh);
        imagejpeg($dst, "$this->thumb_path/$this->thumb_name",$this->quality);
        imagedestroy($src);
        imagedestroy($dst);
        return true;
    }

}
?>