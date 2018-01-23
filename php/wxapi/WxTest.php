<?php
require "lib/CommonTool.php";
require_once "lib/config/WxApiConfig.php";
use wxapi\lib\CommonTool;
use wxapi\lib\config\WxApiConfig;
use wxapi\lib\XML;

//------------------------------------------------------------------------------运行测试用例-------------------------------------------------------------------------------------------
/**
 * 此处是运行测试用例的代码
 */
//实例化测试用例
$isunifiedorder = false;//是否是统一下单
$instance = new WxTest();
//-------运行测试其中某一个用例----
//1.刷卡支付交易保障测试
//$result = $instance->report();
//2.不是刷卡支付交易保障测试
//$result = $instance->report2();
//3.授权码查询测试
//$result = $instance->authcodetoopenid();
//4.下载对账单测试
$result = $instance->downloadbill();
//5.关闭订单测试
//$result = $instance->closeorder();
//6.退款测试
//$result = $instance->refund();
//7.退款查询测试
//$result = $instance->refundquery();
//8.撤单测试
//$result = $instance->reverse();
//9.刷卡支付测试
//$result = $instance->micropay();
//10.统一下单测试
/**
 * $isunifiedorder=true;
 * $result = $instance->unifiedorder();
 * $result = XML::parse($result);
 * if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
 * $json = $result['prepare_pay_json'];
 * }else{
 * $isunifiedorder=false;
 * }
 * */
//11.订单查询测试
//$result = $instance->orderquery();
//-----------------打印测试结果----------------
if (!$isunifiedorder) {
    echo "佰融服务器返回的响应结果:</br>";
    CommonTool::dump_to_html($result);
    try {
        $array_result = XML::parse($result);
        echo "</br>解析成数组打印：</br>";
        CommonTool::dump_to_html($array_result);
    } catch (\Exception $e) {
    }
    return;
}
//-----------------------------------------------------------------以下是配置测试数据的   请自己修改对应的参数值------------------------------------------------------------------------
/**
 * 微信测试用例
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/5/005
 * Time: 13:11
 */
class WxTest
{
    /**
     * 刷卡支付 交易保障测试  通过
     */
    public function report()
    {
        $data = [
            'interface_url' => "https://api.mch.weixin.qq.com/pay/batchreport/micropay/total",
            'trades' => "!CDATA[[{\"out_trade_no\": \"out_trade_no_test_1\",\"begin_time\": \"20160602203256\",\"end_time\": \"20160602203257\",\"state\": \"OK\",\"err_msg\": \"\"}]]"
        ];
        return CommonTool::request(WxApiConfig::getConfig('api.report'), $data);
    }

    /**
     * 不是刷卡支付交易保障测试    通过
     */
    public function report2()
    {
        $data = [
            'interface_url' => "https://api.mch.weixin.qq.com/pay/batchreport/micropay/total",
            'execute_time' => 1000,
            'return_code' => 'SUCCESS',
            'result_code' => 'SUCCESS',
        ];
        return CommonTool::request(WxApiConfig::getConfig('api.report'), $data);
    }

    /**
     * 授权码查询测试  通过
     */
    public function authcodetoopenid()
    {
        $data = [
            'auth_code' => "33242",//授权码
        ];
        return CommonTool::request(WxApiConfig::getConfig('api.authcodetoopenid'), $data);
    }

    /**
     * 下载对账单测试  通过
     */
    public function downloadbill()
    {
        $data = [
            'bill_date' => "20180102",
            'bill_type' => "ALL"
        ];
        return CommonTool::request(WxApiConfig::getConfig('api.downloadbill'), $data);
    }

    /**
     * 关闭订单测试  通过
     */
    public function closeorder()
    {
        $data = [
            'out_trade_no' => "JSAPI136804930220180103154633",
        ];
        return CommonTool::request(WxApiConfig::getConfig('api.closeorder'), $data);
    }

    /**
     * 订单查询测试  通过
     */
    public function orderquery()
    {
        $data = [
            'out_trade_no' => "JSAPI136804930220180103154639",
            // 'transaction_id'=>''
        ];
        return CommonTool::request(WxApiConfig::getConfig('api.queryorder'), $data);
    }

    /**
     * 退款订单测试  通过
     */
    public function refund()
    {
        $data = [
            'out_trade_no' => "JSAPI136804930220180103154633",
            'total_fee' => 1,
            'refund_fee' => 1,
            'out_refund_no' => '4200000017201801048047228344'
        ];
        return CommonTool::request(WxApiConfig::getConfig('api.refund'), $data);
    }

    /**
     * 退款订单查询测试  通过
     */
    public function refundquery()
    {
        $data = [
            'out_trade_no' => "JSAPI136804930220180103154633",
        ];
        return CommonTool::request(WxApiConfig::getConfig('api.refundquery'), $data);
    }

    /**
     * 撤单测试  通过
     */
    public function reverse()
    {
        $data = [
            'out_trade_no' => "JSAPI136804930220180103154633",
        ];
        return CommonTool::request(WxApiConfig::getConfig('api.reverse'), $data);
    }

    /**
     * 刷卡支付 通过
     */
    public function micropay()
    {
        $data = [
            'device_info' => "7777",
            'out_trade_no' => "JSAPI136804930220180103154635",
            'body' => "测试刷卡支付",
            'total_fee' => 1,
            'auth_code' => "134615930132188192",
            'detail' => $this->get_good_list()
        ];
        return CommonTool::request(WxApiConfig::getConfig('api.micropay'), $data);
    }

    /**
     * 获取模拟的商品信息
     *
     * @return string 商品信息 json格式
     */
    private function get_good_list()
    {
        $goods_list = array();
        $goods_list['cost_price'] = 1002;
        $goods_list['receipt_id'] = "wx123";
        $goods_list['goods_detail'] = [];
        $good1 = array();
        $good1['goods_id'] = "78";
        $good1['goods_name'] = "牛肉面";
        $good1['quantity'] = 1;
        $good1['price'] = 1000;
        $good2 = array();
        $good2['goods_id'] = "商品编码2";
        $good2['goods_name'] = "商品2";
        $good2['quantity'] = 2;
        $good2['price'] = 1;
        $goods_list['goods_detail'][] = $good1;
        $goods_list['goods_detail'][] = $good2;
        return CommonTool::json_encode($goods_list);
    }

    //---------------------------通知类----------------------

    /**
     * 统一下单测试  需要授权
     */
    public function unifiedorder()
    {
        $post_string = array();
        $post_string['openid'] = CommonTool::GetOpenid();//或者通过授权码查询得到openid
        $post_string['trade_type'] = 'JSAPI';
        $post_string['device_info'] = '7777';
        $post_string['body'] = "商品信息";
        $post_string['detail'] = $this->get_good_list();
        $post_string['out_trade_no'] = 'JSAPI' . $post_string['mch_id'] . date('YmdHis');
        $post_string['total_fee'] = 1;
        $post_string['goods_tag'] = "1234";
        $post_string['limit_pay'] = "no_credit";
        $post_string['spbill_create_ip'] = "110.110.110.11";
        //发送请求
        $post_string['notify_url'] = WxApiConfig::getConfig("wx.notify_url");
        $result = CommonTool::request(WxApiConfig::getConfig('api.unifiedorder'), $post_string);
        return $result;
        /**
         * $result = XML::parse($json);
         * dump($result);
         * if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
         * $json = $result['prepare_pay_json'];
         * $this->assign('order_info', $post_string);
         * $this->assign('json', $json);
         * return view();
         * }
         * */
    }

    /**
     * 支付结果通知回应方法
     */
    public function notify()
    {
        $content = @file_get_contents("php://input");  //收到的结果信息了
        echo "<xml>
                <return_code><![CDATA[SUCCESS]]></return_code>
                <return_msg><![CDATA[" . $content . "]]></return_msg>
            </xml>";
    }
}
//-----------------------------------------------------------统一下单网页测试------------------------------------------
if ($isunifiedorder) {
    ?>
    <html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>微信支付样例-支付</title>
        <script type="text/javascript">
            //调用微信JS api 支付
            function jsApiCall() {
                WeixinJSBridge.invoke(
                    'getBrandWCPayRequest',
                    <?php echo $json; ?>,
                    function (res) {
                        var description = "";
                        for (var i in res) {
                            description += i + " = " + res[i] + "\n";
                        }
                        alert(description);
                        WeixinJSBridge.log(res.err_msg);
                        <!--alert(res.code+res.err_desc+res.err_msg);-->
                    }
                )
                ;
            }
            function callpay() {
                if (typeof WeixinJSBridge == "undefined") {
                    if (document.addEventListener) {
                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                    } else if (document.attachEvent) {
                        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                    }
                } else {
                    jsApiCall();
                }
            }
        </script>
        <script type="text/javascript">
            window.onload = function () {
                if (typeof WeixinJSBridge == "undefined") {
                    if (document.addEventListener) {
                        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
                    } else if (document.attachEvent) {
                        document.attachEvent('WeixinJSBridgeReady', editAddress);
                        document.attachEvent('onWeixinJSBridgeReady', editAddress);
                    }
                } else {
                    editAddress();
                }
            };
        </script>
    </head>
    <body onload="callpay()">
    <br/>
    <p>服务器返回结果打印：</p>
    <br/>
    <?php CommonTool::dump_to_html($result) ?>
    <br/>
    <font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px">1分</span>钱</b></font><br/><br/>
    <div align="center">
        <button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;"
                type="button" onclick="callpay()">立即支付
        </button>
    </div>
    </body>
    </html>
<?php } ?>