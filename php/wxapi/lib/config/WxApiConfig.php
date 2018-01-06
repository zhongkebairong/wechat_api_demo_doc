<?php

namespace wxapi\lib\config;

//版本号  标识库版本
define("VERSION","1.0");
//基础网址 请自己补全   *
define("BASE_HOST","http://xxx.bryzf.com");


/**
 * 配置类
 * Class WxApiConfig
 * @package wxapi\lib\config
 */
class WxApiConfig
{
    /**
     * 配置项  请补全自己的配置项 *
     * @var array
     */
    private static $_config = [
        //微信配置
        'wx' => [
            'key' => '',//微信签名key
            'mch_id' => '',//商户id
            'appid' => '',//支付的Appid
            'secret' => '',//secret秘钥
            'notify_url' => 'http://xxx.com/notify',//支付回调地址
        ],
        //接口配置
        'api' => [
            'report' => BASE_HOST.'/report',//交易保障
            'authcodetoopenid' => BASE_HOST.'/authcodetoopenid',//授权码查询
            'downloadbill' => BASE_HOST.'/downloadbill',//下载对账单
            'closeorder' => BASE_HOST.'/closeorder',//关闭订单
            'refund' =>BASE_HOST. '/refund',//退款
            'refundquery' =>BASE_HOST. '/refundquery',//退款查询
            'reverse' => BASE_HOST.'/reverse',//撤单
            'micropay' => BASE_HOST.'/micropay',//刷卡支付
            'unifiedorder' => BASE_HOST.'/unifiedorder',//统一下单
        ]
    ];

    /**
     * 配置初始化
     * @access public
     * @param  array $config 配置参数
     * @return void
     */
    public static function init($config = [])
    {
        if (is_null($config) || !is_array($config)) {
            return;
        }
        self::$_config = array_merge(self::$_config, $config);
    }

    /**
     *  获取配置文件
     * @access public
     * @param string $key 配置key
     * @param null $default 默认返回值  null
     * @return array|mixed|null
     */
    public static function getConfig($key = "", $default = null)
    {
        if (empty($key)) {
            return self::$_config;
        } else {
            $arr = explode(".", $key);
            try {
                switch (count($arr)) {
                    case 0:
                        return $default;
                    case 1:
                        return self::$_config[$arr[0]];
                    case 2:
                        return self::$_config[$arr[0]][$arr[1]];
                    case 3:
                        return self::$_config[$arr[0]][$arr[1][$arr[2]]];
                    case 4:
                        return self::$_config[$arr[0]][$arr[1][$arr[2]][$arr[3]]];
                }
            } catch (\Exception $e) {
                return $default;
            }
        }
    }
}