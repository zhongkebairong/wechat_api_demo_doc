<?php
namespace wxapi\lib;

require_once "config/WxApiConfig.php";
require_once "SignTool.php";
require_once "XML.php";

use wxapi\lib\config\WxApiConfig;

/**
 * 通用工具
 * Class CommonTool
 *
 * @package wxtest\lib
 */
class CommonTool
{
    /**
     * 将对象转成json字符串，中文不转义
     *
     * @param mixed $obj 对象
     *
     * @return  string  json字符串
     */
    public static function json_encode($obj)
    {
        return json_encode($obj, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 发送请求
     *
     * @param string $url  地址
     * @param array  $data 数据
     *
     * @return mixed 服务器的返回结果信息
     */
    public static function request($url, $data)
    {
        $data['mch_id'] = WxApiConfig::getConfig("wx.mch_id");
        $data['appid'] = WxApiConfig::getConfig("wx.appid");
        if (!isset($data['nonce_str'])) {
            $data['nonce_str'] = CommonTool::getNonceStr();
        }
        $data['sign'] = SignTool::sign($data, WxApiConfig::getConfig("wx.key"));
        $xml = XML::build($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 产生随机字符串，不长于32位
     *
     * @param int $length 默认32位
     *
     * @return string 产生的随机字符串
     */
    public static function getNonceStr($length = 32)
    {
        if ($length > 32) {
            $length = 32;
        }
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 将dump出来的html格式保留
     *
     * @param mixed $expression 需要打印的变量
     */
    public static function dump_to_html($expression)
    {
        echo "<pre><xmp>";
        var_dump($expression);
        echo "</xmp></pre>";
    }

    /**
     * 将dump出来的字符串保存到变量中
     *
     * @param $expression
     *
     * @return string
     */
    public static function dump_to_string($expression)
    {
        ob_start();
        var_dump($expression);
        $var = ob_get_clean();
        $var = str_replace("\n", "\r\n", $var);
        return $var;
    }

    /**
     * 获取毫秒级别的时间戳
     *
     * @return array|string 毫秒时间戳
     */
    public static function getMillisecond()
    {
        //获取毫秒的时间戳
        $time = explode(" ", microtime());
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode(".", $time);
        $time = $time2[0];
        return $time;
    }

    /**
     *  通过跳转获取用户的openid，跳转流程如下：
     * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
     * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     *
     * @return string 用户的openid
     */
    public static function GetOpenid()
    {
        //通过code获得openid
        if (!isset($_GET['code'])) {
            //触发微信返回code码
            // echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
            // echo "<br>";
            $baseUrl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']);
            $url = self::CreateOauthUrlForCode($baseUrl);
            // echo $url;exit;
            Header("Location: $url");
            exit();
        } else {
            // 获取code码，以获取openid
            $code = $_GET['code'];
            // echo 'code:'.$code;
            // echo "<br>";
            $openid = self::getOpenidFromMp($code);
            // echo 'openid:'.$openid;
            //    echo "<br>";
            return $openid;
        }
    }

    /**
     * 构造获取code的url连接
     *
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     *
     * @return string 返回构造好的url
     */
    private static function CreateOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = WxApiConfig::getConfig("wx.appid");
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE" . "#wechat_redirect";
        $bizString = self::ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?" . $bizString;
    }

    /**
     * 拼接签名字符串
     *
     * @param array $urlObj
     *
     * @return string 返回已经拼接好的字符串
     */
    private static function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v) {
            if ($k != "sign") {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 通过code从工作平台获取openid机器access_token
     *
     * @param string $code 微信跳转回来带上的code
     *
     * @return string openid
     */
    private static function GetOpenidFromMp($code)
    {
        $url = self::CreateOauthUrlForOpenid($code);
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        //取出openid
        $data = json_decode($res, true);
        $openid = $data['openid'];
        return $openid;
    }

    /**
     * 构造获取open和access_toke的url地址
     *
     * @param string $code 微信跳转带回的code
     *
     * @return string 请求的url
     */
    private static function CreateOauthUrlForOpenid($code)
    {
        $urlObj["appid"] = WxApiConfig::getConfig("wx.appid");
        $urlObj["secret"] = WxApiConfig::getConfig("wx.secret");
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        // $urlObj["state"] = "aaa";
        $bizString = self::ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?" . $bizString;
    }
}