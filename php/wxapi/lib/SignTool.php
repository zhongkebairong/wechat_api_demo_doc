<?php
namespace wxapi\lib;
require "interf/SignInterface.php";

use wxapi\lib\interf\SignInterface;

/**
 * 签名工具类
 * Class SignTool
 * @package wxapi\lib
 */
class SignTool implements SignInterface
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

    /**
     * 返回大写的MD5
     * @param string $content
     * @return string
     */
    protected static function return_md5_upper($content)
    {
        return strtoupper(MD5($content));
    }
    /**
     * 微信签名转换
     * @param $array 须签名的数组
     * @return string
     */
    protected static function to_wx_url_params($array)
    {
        $content = "";
        if (is_array($array)) {//判断是不是数组
            foreach ($array as $key => $value) {
                {
                    if ($key != "sign" && $value != "" && !is_array($value)) {
                        $content .= $key . "=" . $value . "&";
                    }
                }
            }
        }
        return rtrim($content, "&");
    }


    /**
     * 转换参数
     * @param string|array $data
     * @return array|mixed
     */
    protected static function convert_params($data)
    {
        if (!is_array($data)) {
            $tempJson="";
            if (!is_string($data)) {//不是字符串转换成json字符串
                $tempJson = json_encode($data, JSON_UNESCAPED_UNICODE);
            }
            $jsonArray = json_decode($tempJson, true);//解密成数组
        } else {
            $jsonArray = $data;
        }
        if (isset($jsonArray["sign"])) {
            unset($jsonArray["sign"]);
        }
        return $jsonArray;
    }

    /**
     * 保留key排序方法
     * @param $arr 需要排序的数组
     * @param bool $is_asc 升序：true 降序：false
     * @param bool $isKey 是否key排序
     * @return array
     */
    protected static function sort_array($arr, $is_asc = true, $isKey = true)
    {
        $new_array = array();
        $new_sort = array();
        foreach ($arr as $key => $value) {
            if ($isKey) {
                $new_array[] = $key;
            } else {
                $new_array[] = $value;
            }
        }
        if ($is_asc) { //asc
            asort($new_array);
        } else {//desc
            arsort($new_array);
        }
        foreach ($new_array as $k => $v) {
            foreach ($arr as $key => $value) {
                if (($isKey && $v == $key) || (!$isKey && $v == $value)) {
                    $new_sort[$key] = $value;
                    unset($arr[$key]);
                    break;
                }
            }
        }
        return $new_sort;
    }
}