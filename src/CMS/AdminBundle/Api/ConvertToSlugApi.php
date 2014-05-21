<?php
namespace CMS\AdminBundle\Api;

class ConvertToSlugApi
{
    public  $string;

    public function __construct($str = '')
    {
        $this->string = $str;
    }
    public function setString($str = ''){
        $this->string = $str;
    }
    public function getString(){
        return $this->string;
    }
    function convert() {
        $this->string = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $this->string);
        $this->string = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $this->string);
        $this->string = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $this->string);
        $this->string = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $this->string);
        $this->string = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $this->string);
        $this->string = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $this->string);
        $this->string = preg_replace("/(đ)/", 'd', $this->string);
        $this->string = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $this->string);
        $this->string = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $this->string);
        $this->string = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $this->string);
        $this->string = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $this->string);
        $this->string = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $this->string);
        $this->string = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $this->string);
        $this->string = preg_replace("/(Đ)/", 'D', $this->string);
        $this->string = trim(strtolower($this->string));
        $this->string = str_replace("'", '', $this->string);
        $this->string = preg_replace("/[^a-z0-9.]+/", '-', $this->string);
        $this->string = trim($this->string, '-');

        return $this->string;
    }
}