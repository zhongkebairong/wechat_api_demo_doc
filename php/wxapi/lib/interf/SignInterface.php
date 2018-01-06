<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/6/006
 * Time: 11:14
 */

namespace wxapi\lib\interf;

/**
 * 签名接口
 * Interface SiginInterface
 * @package wxapi\lib\interf
 */
interface SignInterface
{
    /**
     * 签名
     * @param string|array $data 需要签名的数据
     * @param string $sign_key 签名key
     * @return string
     */
    public static function sign($data, $sign_key);
}