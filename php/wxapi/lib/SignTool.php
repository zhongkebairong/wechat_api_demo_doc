<?php
namespace wxapi\lib;
require "interf/SignAbstract.php";

use wxapi\lib\interf\SignAbstract;

/**
 * 签名工具类
 * Class SignTool
 * @package wxapi\lib
 */
class SignTool extends SignAbstract
{
    /**
     * 微信签名工具
     * @param string|array $data 需要签名的数据 json array
     * @param string $sign_key 签名key
     * @return string
     */
    public static function sign($data, $sign_key)
    {
       $array_data=self::convert_params($data);
       $result="";
       if(!is_null($array_data)){
           $sort_data=self::sort_array($array_data);
           $result=self::to_wx_url_params($sort_data);
       }
       $result.="&key=".$sign_key;
       return self::return_md5_upper($result,$sign_key);
    }
}