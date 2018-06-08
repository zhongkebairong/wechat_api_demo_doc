# wechat_api_demo_doc
微信支付接口封装文档以及demo维护

## 微信支付对接文档 

```
如果您有任何问题，或发现BUG，可以发送邮件到

anna@bryzf.com

我们将第一时间进行回复，谢谢
```

本文档用于说明品牌门店POS与微信支付的接口交互。

**本文阅读对象**：研发工程师，测试工程师，系统运维工程师。

**接口地址说明**：接口地址中的域名`http://xxx.bryzf.com`为示例域名，实际接口中的域名以分配的域名为准。

**名词解释**：

*礼品卡：*
微信基于自己的生态，提供给商家的一整套营销体系，商户通过将创建的礼品卡货架配置在微信小程序或生成二维码贴在门店进行礼品卡的售卖以及赠送，用户可以通过购买礼品卡送给朋友并且附上祝福语，表达节日的祝福和慰问。其能力可用于线上线下营销。*微信商户平台：*
微信商户平台是微信支付相关的商户功能集合，包括参数配置、支付数据查询与统计、在线退款、代金券或立减优惠运营等功能。

*微信支付系统：*
微信支付系统是指完成微信支付流程中涉及的API接口、后台业务处理系统、账务系统、回调通知等系统的总称。

*商户收银系统：*
商户收银系统即商户的POS收银系统，是录入商品信息、生成订单、客户支付、打印小票等功能的系统。接入微信礼品卡能主要涉及到POS软件系统的开发和测试，所以在下文中提到的商户收银系统特指POS收银软件系统。

*扫码设备：*
一种输入设备，主要用于商户系统快速读取媒介上的图形编码信息。按读取码的类型不同，可分为条码扫码设备和二维码扫码设备。按读取物理原理可分为红外扫码设备、激光扫码设备。

*签名：*
商户后台和微信支付后台根据相同的密钥和算法生成一个结果，用于校验双方身份合法性。签名的算法，签名方式为：MD5。

签名生成的通用步骤如下：

	第一步，设所有发送或者接收到的数据为集合M，将集合M内非空参数值的参数按照参数名ASCII码
	从小到大排序（字典序），使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字
	符串stringA。
	
	特别注意以下重要规则：
	
	◆ 参数名ASCII码从小到大排序（字典序）；
	
	◆ 如果参数的值为空不参与签名；
	
	◆ 参数名区分大小写；
	
	◆ 验证调用返回或微信主动通知签名时，传送的sign参数不参与签名，将生成的签名与该sign值
	作校验。
	
	◆ 微信接口可能增加字段，验证签名时必须支持增加的扩展字段
	
	第二步，在stringA最后拼接上key得到stringSignTemp字符串，并对stringSignTemp进行
	MD5运算，再将得到的字符串所有字符转换为大写，得到sign值signValue。
	
	举例：
	
	假设传送的参数如下：

	appid：	        wxd930ea5d5a258f4f
	mch_id：        10000100
	device_info：	1000
	body：	        test
	nonce_str：	ibuaiVcKdpRxkhJA
	
​	
	第一步：对参数按照key=value的格式，并按照参数名ASCII字典序排序如下：
	 stringA="appid=wxd930ea5d5a258f4f&body=test&device_info=1000&mch_id=10000100&nonce_str=ibuaiVcKdpRxkhJA";
	
	第二步：拼接API密钥：
	
	stringSignTemp=stringA+"&key=192006250b4c09247ec02edce69f6a2d" //注：key为商户平台设置的密钥key
	
	sign=MD5(stringSignTemp).toUpperCase()="9A0A8659F005D6984697E2CA0A9CF3B7" //注：MD5签名方式
	
	最终得到最终发送的数据：
	
	<xml>
	<appid>wxd930ea5d5a258f4f</appid>
	<mch_id>10000100</mch_id>
	<device_info>1000</device_info>
	<body>test</body>
	<nonce_str>ibuaiVcKdpRxkhJA</nonce_str>
	<sign>9A0A8659F005D6984697E2CA0A9CF3B7</sign>
	</xml>



*支付密码：*
支付密码是用户开通微信支付时单独设置的密码，用于确认支付完成交易授权。该密码与微信登录密码不同。


**协议规定：**

*传输方式：*	
为保证交易安全性，采用HTTPS传输

*提交方式：*	
采用POST方法提交

*数据格式：*
提交和返回数据都为XML格式，根节点名为xml

*字符编码：*
统一采用UTF-8字符编码

*签名算法：*
MD5

*签名要求：*	
请求和接收数据均需要校验签名，详细方法请参考安全规范-签名算法

*判断逻辑：*
先判断协议字段返回，再判断业务返回，最后判断交易状态


**参数规定**

*商品描述(body)：*	
店名-活动名称   for example: 凯德MALL望京-礼品卡95折

*交易金额：*
交易金额默认为人民币交易，接口中参数支付金额单位为【分】，参数值不能带小数。对账单中的交易金额单位为【元】。

*货币类型：*
CNY：人民币

*时间：*
标准北京时间，时区为东八区；如果商户的系统时间为非标准北京时间。参数值必须根据商户系统所在时区先换算成标准北京时间， 例如商户所在地为0时区的伦敦，当地时间为2014年11月11日0时0分0秒，换算成北京时间为2014年11月11日8时0分0秒。

*时间戳：*
标准北京时间，时区为东八区，自1970年1月1日 0点0分0秒以来的秒数。注意：部分系统取到的值为毫秒级，需要转换成秒(10位数字)。


*商户订单号(out_trade_no)：*
商户支付的订单号由商户自定义生成，微信支付要求商户订单号保持唯一性（建议根据当前商户号加系统时间加随机序列来生成订单号）。重新发起一笔支付要使用原订单号，避免重复支付；已支付过或已调用关单、撤销（请见后文的API列表）的订单号不能重新发起支付。

*优惠名称：*
商户在提交支付后，用户实际享受优惠的活动名称（如有多个以|进行分割）

*优惠金额：*
POS在提交支付后，用户实际享受的优惠金额，实际收款金额 = 总订单金额 - 优惠金额。

*商品详情：*
当交易发生时，交易商品的详情，将用于商品活动的判定，该字段须严格按照规范传递。

具体请见参数规定

商品格式为json格式，具体格式举例如下：


```
{
    "cost_price": 608800, 
    "receipt_id": "wx123", 
    "goods_detail": [
        {
            "goods_id": "商品编码", 
            "goods_name": "", 
            "quantity": 1, 
            "price": 528800
        }, 
        {
            "goods_id": "商品编码", 
            "goods_name": "iPhone6s 32G", 
            "quantity": 1, 
            "price": 608800
        }
    ]
}

```

**detail字段说明**

|  字段名称  |      变量名       |     字段类型     |  必填  |    字段说明    |            字段实例            |
| :----: | :------------: | :----------: | :--: | :--------: | :------------------------: |
|  订单原价  |  `cost_price`  |     Int      |  是   | 订单不参与折扣的原价 |           608800           |
| 商品小票ID |  `receipt_id`  |  String(32)  |  是   |   商家小票ID   |           ZK123            |
|  单品列表  | `goods_detail` | String(6000) |  是   | 商品信息，详见下表  | 如订单格式说明示例中`goods_detail`所示 |

**goods_detail字段说明**

每个商品为一个对象，如果有多个商品，则以‘,’分割

| 字段名称 |     变量名      |    字段类型     |  必填  |    字段说明    |   字段实例    |
| :--: | :----------: | :---------: | :--: | :--------: | :-------: |
| 商品编码 |  `goods_id`  | String(32)  |  是   |  pos内商品编码  | Ac4852_11 |
| 商品名称 | `goods_name` | String(256) |  是   |    商品名称    |    蛋糕     |
| 商品数量 |  `quantity`  |     int     |  是   |    商品数量    |     2     |
| 商品单价 |   `price`    |     int     |  是   | 商品单价，以分为单位 |    200    |

### 刷卡支付接口
**场景介绍**

步骤1：用户选择刷卡支付付款并打开微信，进入“我”->“钱包”->“收付款”条码界面；

步骤2：收银员在商户系统操作生成支付订单，用户确认支付金额；

步骤3：商户收银员用扫码设备扫描用户的条码/二维码，商户收银系统提交支付；

步骤4：微信支付后台系统收到支付请求，根据验证密码规则判断是否验证用户的支付密码，不需要验证密码的交易直接发起扣款，需要验证密码的交易会弹出密码输入框。支付成功后微信端会弹出成功页面，支付失败会弹出错误提示。

步骤5：商户POS系统在收到支付结果后，记录实收金额以及优惠金额。

（注：用户刷卡条形码规则：18位纯数字，以10、11、12、13、14、15开头）

**验证密码规则**

◆ 支付金额>1000元的交易需要验证用户支付密码  
◆ 用户账号每天最多有5笔交易可以免密，超过后需要验证密码  
◆ 微信支付后台判断用户支付行为有异常情况，符合免密规则的交易也会要求验证密码  
注：基于一定的风控策略，存在随时需要验密的可能性。


**接口地址**：
`http://xxx.bryzf.com/micropay`

**是否需要证书** : 
不需要

**请求参数**：

|   名称   |        变量名         |  必填  |      类型      |               示例值                |                    描述                    |
| :----: | :----------------: | :--: | :----------: | :------------------------------: | :--------------------------------------: |
| 公众账号ID |      `appid`       |  是   |  String(32)  |        wx8888888888888888        |               微信分配的公众账号ID                |
|  商户号   |      `mch_id`      |  是   |  String(32)  |            1900000109            |                微信支付分配的商户号                |
|  设备号   |   `device_info`    |  否   |  String(32)  |         013467007045764          |               终端设备号(门店编号)                |
| 随机字符串  |    `nonce_str`     |  是   |  String(32)  | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |          随机字符串，不长于32位。推荐随机数生成算法          |
|   签名   |       `sign`       |  是   |  String(32)  | C380BEC2BFD727A4B6845133519F3AD6 |               签名，详见签名生成算法                |
|  商品描述  |       `body`       |  是   | String(128)  |                蛋糕                |       商品简单描述，该字段须严格按照规范传递，具体请见参数规定       |
|  商品详情  |      `detail`      |  是   | String(6000) |                                  |  当交易发生时，交易商品的详情，将用于商品活动的判定，该字段须严格按照规范传递  |
|  附加数据  |      `attach`      |  否   | String(127)  |                说明                | 附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据 |
| 商户订单号  |   `out_trade_no`   |  是   |  String(32)  |   1217752501201407033233368018   |     商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-     |
|  订单金额  |    `total_fee`     |  是   |     Int      |               888                |         订单总金额，单位为分，只能为整数，详见支付金额          |
|  终端IP  | `spbill_create_ip` |  是   |  String(16)  |             8.8.8.8              |              调用微信支付API的机器IP              |
| 订单优惠标记 |    `goods_tag`     |  否   |  String(32)  |               1234               |     订单优惠标记，代金券或立减优惠功能的参数，详见代金券或立减优惠      |
| 指定支付方式 |    `limit_pay`     |  否   |  String(32)  |            no_credit             |          no_credit--指定不能使用信用卡支付          |
|  授权码   |    `auth_code`     |  是   | String(128)  |        120061098828009406        | 扫码支付授权码，设备读取用户微信中的条码或者二维码信息（注：用户刷卡条形码规则：18位纯数字，以10、11、12、13、14、15开头） |


**请求数据示例**

```
<xml>
   <appid>wx2421b1c4370ec43b</appid>
   <attach>订单额外描述</attach>
   <auth_code>120269300684844649</auth_code>
   <body>望京凯德MALL</body>
   <device_info>08004</device_info>
   <detail>{"cost_price": 608800,"receipt_id": "wx123","goods_detail": [{"goods_id": "商品编码","goods_name": "","quantity": 1,"price": 528800},{"goods_id": "商品编码","goods_name": "iPhone6s 32G","quantity": 1,"price": 608800}]}</detail>
   <mch_id>10000100</mch_id>
   <nonce_str>8aaee146b1dee7cec9100add9b96cbe2</nonce_str>
   <out_trade_no>1415757673201708020800488654</out_trade_no>
   <spbill_create_ip>14.17.22.52</spbill_create_ip>
   <total_fee>1</total_fee>
   <sign>C29DB7DB1FD4136B84AE35604756362C</sign>
</xml>
```

**返回结果**


|  名称   |      变量名       |  必填  |     类型      |   示例值   |                    描述                    |
| :---: | :------------: | :--: | :---------: | :-----: | :--------------------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS | SUCCESS/FAIL此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断 |
| 返回信息  | `return_msg `  |  否   | String(128) |  签名失败   |      返回信息，如非空，为错误原因 签名失败 参数格式 校验错误       |

**当return_code为SUCCESS的时候，还会包括以下字段**：

|  字段名   |       变量名       |  必填  |     类型      |               示例值                |        描述        |
| :----: | :-------------: | :--: | :---------: | :------------------------------: | :--------------: |
| 公众账号ID |     `appid`     |  是   | String(32)  |        wx8888888888888888        |   微信分配的公众账号ID    |
|  商户号   |    `mch_id`     |  是   | String(32)  |            1900000109            |    调用接口提交的商户号    |
|  设备号   |  `device_info`  |  否   | String(32)  |         013467007045764          |   终端设备号(门店编号)    |
| 随机字符串  |   `nonce_str`   |  是   | String(32)  | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |    微信返回的随机字符串    |
|   签名   |     `sign`      |  是   | String(32)  | C380BEC2BFD727A4B6845133519F3AD6 | 微信返回的签名，详见签名生成算法 |
|  业务结果  |  `result_code`  |  是   | String(16)  |             SUCCESS              |   SUCCESS/FAIL   |
|  错误代码  |   `err_code`    |  否   | String(32)  |           SYSTEMERROR            |     详细参见错误列表     |
| 错误代码描述 | `	err_code_des` |  否   | String(128) |               系统错误               |    错误返回的信息描述     |

**以下字段在`return_code` 、`result_code`、`trade_state`都为SUCCESS时有返回 ，如`trade_state`不为 SUCCESS，则只返回`out_trade_no`（必传）和`attach`（选传）。**

|   字段名   |          变量名           |  必填  |     类型      |             示例值              |                    描述                    |
| :-----: | :--------------------: | :--: | :---------: | :--------------------------: | :--------------------------------------: |
|  交易类型   |      `trade_type`      |  是   | String(16)  |           MICROPAY           |           支付类型为MICROPAY(即刷卡支付)           |
|  用户标识   |       `openid `        |  是   | String(128) | oUpF8uMuAJO_M2pxb1Q9zNjWeS6o |             用户在商户appid下的唯一标识             |
|  付款银行   |      `bank_type`       |  是   | String(32)  |             CMC              |         银行类型，采用字符串类型的银行标识，详见银行类型         |
|  货币类型   |       `fee_type`       |  否   | String(16)  |             CNY              |   符合ISO 4217标准的三位字母代码，默认人民币：CNY，详见货币类型   |
|  订单金额   |      `total_fee`       |  是   |     Int     |             888              |         订单总金额，单位为分，只能为整数，详见支付金额          |
| 应结订单金额  | `settlement_total_fee` |  否   |     Int     |             100              | 当订单使用了免充值型优惠券后返回该参数，应结订单金额=订单金额-免充值优惠券金额。 |
|  优惠名称   |    `discount_name`     |  是   | String(16)  |           国庆礼品卡活动            |               市场营销设置的活动名称                |
|  优惠ID   |     `discount_id`      |  是   | String(16)  |              25              |                 优惠活动的ID                  |
|  优惠金额   |     `discount_fee`     |  是   |     Int     |             100              |              用户实际支付时候的优惠金额               |
| 微信支付订单号 |    `transaction_id`    |  是   | String(32)  | 1217752501201407033233368018 |                 微信支付订单号                  |
|  商户订单号  |     `out_trade_no`     |  是   | String(32)  | 1217752501201407033233368018 |     商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-     |
|  商家数据包  |        `attach`        |  否   | String(128) |            123456            |                商家数据包，原样返回                |
| 支付完成时间  |       `time_end`       |  是   | String(14)  |        20141030133525        | 订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。详见时间规则 |



**举例**

```
<xml>
   <return_code><![CDATA[SUCCESS]]></return_code>
   <return_msg><![CDATA[OK]]></return_msg>
   <appid>wx2421b1c4370ec43b</appid>
   <mch_id><![CDATA[10000100]]></mch_id>
   <device_info><![CDATA[08004]]></device_info>
   <nonce_str><![CDATA[GOp3TRyMXzbMlkun]]></nonce_str>
   <sign><![CDATA[D6C76CB785F07992CDE05494BB7DF7FD]]></sign>
   <result_code><![CDATA[SUCCESS]]></result_code>
   <trade_type><![CDATA[MICROPAY]]></trade_type>
   <bank_type><![CDATA[CCB_DEBIT]]></bank_type>
   <total_fee>1</total_fee>
   <settlement_total_fee>1</settlement_total_fee>
   <discount_name><![CDATA[国庆活动]]></discount_name>
   <discount_id>25</discount_id>
   <discount_fee>0</discount_fee>
   <transaction_id><![CDATA[1008450740201411110005820873]]></transaction_id>
   <out_trade_no><![CDATA[1415757673]]></out_trade_no>
   <attach><![CDATA[订单额外描述]]></attach>
   <time_end><![CDATA[20141111170043]]></time_end>
</xml>
```

**错误码**

|         名称          |         描述          |  支付状态  |            原因            |                   解决方案                   |
| :-----------------: | :-----------------: | :----: | :----------------------: | :--------------------------------------: |
|     SYSTEMERROR     |       接口返回错误        | 支付结果未知 |           系统超时           | 请立即调用被扫订单结果查询API，查询当前订单状态，并根据订单的状态决定下一步的操作。 |
|     PARAM_ERROR     |        参数错误         | 支付确认失败 |       请求参数未按指引进行填写       |            请根据接口返回的详细信息检查您的程序            |
|      ORDERPAID      |        订单已支付        | 支付确认失败 |          订单号重复           |      请确认该订单号是否重复支付，如果是新单，请使用新订单号提交       |
|       NOAUTH        |        商户无权限        | 支付确认失败 |       商户没有开通被扫支付权限       |           请开通商户号权限。请联系产品或商务申请            |
|   AUTHCODEEXPIRE    | 二维码已过期，请用户在微信上刷新后再试 | 支付确认失败 |        用户的条码已经过期         | 请收银员提示用户，请用户在微信上刷新条码，然后请收银员重新扫码。 直接将错误展示给收银员 |
|      NOTENOUGH      |        余额不足         | 支付确认失败 |        用户的零钱余额不足         | 请收银员提示用户更换当前支付的卡，然后请收银员重新扫码。建议：商户系统返回给收银台的提示为“用户余额不足.提示用户换卡支付” |
|    NOTSUPORTCARD    |       不支持卡类型        | 支付确认失败 |     用户使用卡种不支持当前支付形式      | 请用户重新选择卡种 建议：商户系统返回给收银台的提示为“该卡不支持当前支付，提示用户换卡支付或绑新卡支付” |
|     ORDERCLOSED     |        订单已关闭        | 支付确认失败 |          该订单已关           |             商户订单号异常，请重新下单支付              |
|    ORDERREVERSED    |        订单已撤销        | 支付确认失败 |        当前订单已经被撤销         |         当前订单状态为“订单已撤销”，请提示用户重新支付         |
|      BANKERROR      |       银行系统异常        | 支付结果未知 |          银行端超时           |  请立即调用被扫订单结果查询API，查询当前订单的不同状态，决定下一步的操作。  |
|     USERPAYING      |    用户支付中，需要输入密码     | 支付结果未知 | 该笔交易因为业务规则要求，需要用户输入支付密码。 | 等待3秒，然后调用被扫订单结果查询API，查询当前订单的不同状态，决定下一步的操作。 |
|   AUTH_CODE_ERROR   |       授权码参数错误       | 支付确认失败 |       请求参数未按指引进行填写       |            每个二维码仅限使用一次，请刷新再试             |
|  AUTH_CODE_INVALID  |       授权码检验错误       | 支付确认失败 |     收银员扫描的不是微信支付的条码      |             请扫描微信支付被扫条码/二维码              |
|  XML_FORMAT_ERROR   |       XML格式错误       | 支付确认失败 |         XML格式错误          |              请检查XML参数格式是否正确              |
| REQUIRE_POST_METHOD |      请使用post方法      | 支付确认失败 |       未使用post传递参数        |           请检查请求参数是否通过post方法提交            |
|      SIGNERROR      |        签名错误         | 支付确认失败 |        参数签名结果不正确         |          请检查签名参数和方法是否都符合签名算法要求           |
|     LACK_PARAMS     |        缺少参数         | 支付结果未知 |        缺少必要的请求参数         |                请检查参数是否齐全                 |
|      NOT_UTF8       |       编码格式错误        | 支付结果未知 |        未使用指定编码格式         |               请使用UTF-8编码格式               |
|   BUYER_MISMATCH    |       支付帐号错误        | 支付确认失败 |      暂不支持同一笔订单更换支付方      |                请确认支付方是否相同                |
|   MCHID_NOT_EXIST   |      MCHID不存在       | 支付确认失败 |        参数中缺少MCHID        |               请检查MCHID是否正确               |
|  OUT_TRADE_NO_USED  |       商户订单号重复       | 支付确认失败 |       同一笔交易不能多次提交        |              请核实商户订单号是否重复提交              |

### 统一下单接口
**场景介绍**

除被扫支付场景以外，调用该接口生成预支付交易单，返回正确的预支付交易回话标识后再按扫码、JSAPI、APP等不同场景生成交易串调起支付。

**接口地址**：`http://xxx.bryzf.com/unifiedorder`

**是否需要证书**: 
不需要

**请求参数**：

|   名称   |        变量名         |  必填  |      类型      |                  示例值                   |                    描述                    |
| :----: | :----------------: | :--: | :----------: | :------------------------------------: | :--------------------------------------: |
| 公众账号ID |      `appid`       |  是   |  String(32)  |           wx8888888888888888           |               微信分配的公众账号ID                |
|  商户号   |      `mch_id`      |  是   |  String(32)  |               1900000109               |                微信支付分配的商户号                |
|  设备号   |   `device_info`    |  否   |  String(32)  |            013467007045764             |               终端设备号(门店编号)                |
| 随机字符串  |    `nonce_str`     |  是   |  String(32)  |    5K8264ILTKCH16CQ2502SI8ZNMTM67VS    |          随机字符串，不长于32位。推荐随机数生成算法          |
|   签名   |       `sign`       |  是   |  String(32)  |    C380BEC2BFD727A4B6845133519F3AD6    |               签名，详见签名生成算法                |
|  商品描述  |       `body`       |  是   | String(128)  |                   蛋糕                   |       商品简单描述，该字段须严格按照规范传递，具体请见参数规定       |
|  商品详情  |      `detail`      |  是   | String(6000) |                                        |  当交易发生时，交易商品的详情，将用于商品活动的判定，该字段须严格按照规范传递  |
|  附加数据  |      `attach`      |  否   | String(127)  |                   说明                   | 附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据 |
| 商户订单号  |   `out_trade_no`   |  是   |  String(32)  |      1217752501201407033233368018      |     商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-     |
|  订单金额  |    `total_fee`     |  是   |     Int      |                  888                   |         订单总金额，单位为分，只能为整数，详见支付金额          |
|  终端IP  | `spbill_create_ip` |  是   |  String(16)  |                8.8.8.8                 |              调用微信支付API的机器IP              |
| 交易起始时间 |    `time_start`    |  否   |  String(14)  |             20091225091010             | 订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。 |
| 交易结束时间 |   `time_expire `   |  否   |  String(14)  |             20091225091010             | 订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。 |
| 订单优惠标记 |    `goods_tag`     |  否   |  String(32)  |                  1234                  |        订单优惠标记，使用代金券或立减优惠功能时需要的参数         |
|  通知地址  |    `notify_url`    |  是   | String(256)  | http://www.weixin.qq.com/wxpay/pay.php | 异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数。 |
|  交易类型  |    `trade_type`    |  是   |  String(16)  |                 JSAPI                  |     取值如下：JSAPI(公众号支付)，NATIVE(原生扫码支付)     |
|  商品ID  |    `product_id`    |  否   |  String(32)  |        12235413214070356458058         | rade_type=NATIVE时（即扫码支付），此参数必传。此参数为二维码中包含的商品ID，可自行定义。 |
|  用户标识  |      `openid`      |  否   | String(128)  |      oUpF8uMuAJO_M2pxb1Q9zNjWeS6o      | trade_type=JSAPI时（即公众号支付），此参数必传，此参数为微信用户在商户对应appid下的唯一标识。openid如何获取，可参考[获取openid](http://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_4)。企业号请使用[企业号OAuth2.0接口](http://qydev.weixin.qq.com/wiki/index.php?title=OAuth%E9%AA%8C%E8%AF%81%E6%8E%A5%E5%8F%A3) 获取企业号内成员userid，再调用[企业号userid转openid接口](http://qydev.weixin.qq.com/wiki/index.php?title=Userid%E4%B8%8Eopenid%E4%BA%92%E6%8D%A2%E6%8E%A5%E5%8F%A3)进行转换 |
| 指定支付方式 |    `limit_pay`     |  否   |  String(32)  |               no_credit                |          no_credit--指定不能使用信用卡支付          |



**请求数据示例**

```
<xml>
   <appid>wx2421b1c4370ec43b</appid>
   <attach>订单额外描述</attach>
   <body>望京凯德MALL</body>
   <mch_id>10000100</mch_id>
   <device_info>08004</device_info>
   <detail>{"cost_price": 608800,"receipt_id": "wx123","goods_detail": [{"goods_id": "商品编码","goods_name": "","quantity": 1,"price": 528800},{"goods_id": "商品编码","goods_name": "iPhone6s 32G","quantity": 1,"price": 608800}]}</detail>
   <notify_url>http://wxpay.wxutil.com/pub_v2/pay/notify.v2.php</notify_url>
   <openid>oUpF8uMuAJO_M2pxb1Q9zNjWeS6o</openid>
   <nonce_str>8aaee146b1dee7cec9100add9b96cbe2</nonce_str>
   <out_trade_no>1415757673201708020800488654</out_trade_no>
   <spbill_create_ip>14.17.22.52</spbill_create_ip>
   <total_fee>1</total_fee>
   <sign>C29DB7DB1FD4136B84AE35604756362C</sign>
</xml>
```

**返回结果**


|  名称   |      变量名       |  必填  |     类型      |   示例值   |                    描述                    |
| :---: | :------------: | :--: | :---------: | :-----: | :--------------------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS | SUCCESS/FAIL此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断 |
| 返回信息  | `return_msg `  |  否   | String(128) |  签名失败   |      返回信息，如非空，为错误原因 签名失败 参数格式 校验错误       |

**当return_code为SUCCESS的时候，还会包括以下字段**：

|  字段名   |       变量名       |  必填  |     类型      |               示例值                |        描述        |
| :----: | :-------------: | :--: | :---------: | :------------------------------: | :--------------: |
| 公众账号ID |     `appid`     |  是   | String(32)  |        wx8888888888888888        |   微信分配的公众账号ID    |
|  商户号   |    `mch_id`     |  是   | String(32)  |            1900000109            |    调用接口提交的商户号    |
|  设备号   |  `device_info`  |  否   | String(32)  |         013467007045764          |   终端设备号(门店编号)    |
| 随机字符串  |   `nonce_str`   |  是   | String(32)  | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |    微信返回的随机字符串    |
|   签名   |     `sign`      |  是   | String(32)  | C380BEC2BFD727A4B6845133519F3AD6 | 微信返回的签名，详见签名生成算法 |
|  业务结果  |  `result_code`  |  是   | String(16)  |             SUCCESS              |   SUCCESS/FAIL   |
|  错误代码  |   `err_code`    |  否   | String(32)  |           SYSTEMERROR            |     详细参见错误列表     |
| 错误代码描述 | `	err_code_des` |  否   | String(128) |               系统错误               |    错误返回的信息描述     |

**以下字段在`return_code` 、`result_code` 都为SUCCESS时有返回**

|    字段名    |        变量名         |  必填  |      类型      |                 示例值                  |                    描述                    |
| :-------: | :----------------: | :--: | :----------: | :----------------------------------: | :--------------------------------------: |
|   交易类型    |    `trade_type`    |  是   |  String(16)  |                JSAPI                 |  JSAPI--公众号支付、NATIVE--原生扫码支付、APP--app支付  |
|   用户标识    |    `prepay_id `    |  是   |  String(64)  | wx201410272009395522657a690389285100 |    微信生成的预支付会话标识，用于后续接口调用中使用，该值有效期为2小时    |
| JS调起支付的配置 | `prepare_pay_json` |  是   | String(3000) |         详见prepare_pay_json示例         |   在微信浏览器里面打开H5网页中执行JS调起支付时的配置项，格式为json   |
|   优惠名称    |  `discount_name`   |  是   |  String(16)  |               国庆礼品卡活动                |               市场营销设置的活动名称                |
|   优惠ID    |   `discount_id`    |  是   |  String(16)  |                  25                  |                 优惠活动的ID                  |
|   优惠金额    |   `discount_fee`   |  是   |     Int      |                 100                  |              用户实际支付时候的优惠金额               |
|   二维码链接   |    `code_url `     |  否   |  String(64)  |     URl：weixin：//wxpay/s/An4baqw     | trade_type为NATIVE时有返回，用于生成二维码，展示给用户进行扫码支付 |

**`prepare_pay_json`示例**

```
{
  "appId":"wx558d04eeb1b188a9",
  "timeStamp":"1514896093",
  "nonceStr":"5a4b7add55c97",
  "package":"prepay_id=wx2018010220281314650d793d0465718660",
  "signType":"MD5",
  "paySign":"B72BC7DFFA0722F896276711EC066B1A"
}

```

**举例**

```
<xml>
   <return_code><![CDATA[SUCCESS]]></return_code>
   <return_msg><![CDATA[OK]]></return_msg>
   <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
   <mch_id><![CDATA[10000100]]></mch_id>
   <nonce_str><![CDATA[IITRi8Iabbblz1Jc]]></nonce_str>
   <sign><![CDATA[7921E432F65EB8ED0CE9755F0E86D72F]]></sign>
   <result_code><![CDATA[SUCCESS]]></result_code>
   <prepay_id><![CDATA[wx201411101639507cbf6ffd8b0779950874]]></prepay_id>
   <trade_type><![CDATA[JSAPI]]></trade_type>
</xml>
```

**错误码**

|          名称           |       描述        |       原因        |         解决方案          |
| :-------------------: | :-------------: | :-------------: | :-------------------: |
|        NOAUTH         |      商户无权限      |  商户没有开通被扫支付权限   |  请开通商户号权限。请联系产品或商务申请  |
|       NOTENOUGH       |      余额不足       |    用户帐号余额不足     |       用户帐号余额不足        |
|       ORDERPAID       |     商户订单已支付     | 商户订单已支付，无需重复操作  |    商户订单已支付，无需更多操作     |
|      ORDERCLOSED      |      订单已关闭      |      该订单已关      |    商户订单号异常，请重新下单支付    |
|      SYSTEMERROR      |      系统错误       |      系统超时       |    系统异常，请用相同参数重新调用    |
|    APPID_NOT_EXIST    |    APPID不存在     |   参数中缺少APPID    |     请检查APPID是否正确      |
|    MCHID_NOT_EXIST    |    MCHID不存在     |   参数中缺少MCHID    |     请检查MCHID是否正确      |
| APPID_MCHID_NOT_MATCH | appid和mch_id不匹配 | appid和mch_id不匹配 |  请确认appid和mch_id是否匹配  |
|      LACK_PARAMS      |      缺少参数       |    缺少必要的请求参数    |       请检查参数是否齐全       |
|   OUT_TRADE_NO_USED   |     商户订单号重复     |   同一笔交易不能多次提交   |    请核实商户订单号是否重复提交     |
|       SIGNERROR       |      签名错误       |    参数签名结果不正确    | 请检查签名参数和方法是否都符合签名算法要求 |
|   XML_FORMAT_ERROR    |     XML格式错误     |     XML格式错误     |    请检查XML参数格式是否正确     |
|  REQUIRE_POST_METHOD  |    请使用post方法    |   未使用post传递参数   |  请检查请求参数是否通过post方法提交  |
|    POST_DATA_EMPTY    |    post数据为空     |   post数据不能为空    |     请检查post数据是否为空     |
|       NOT_UTF8        |     编码格式错误      |    未使用指定编码格式    |     请使用UTF-8编码格式      |


### 查询订单接口

**场景介绍**

该接口提供所有微信支付订单的查询，商户可以通过查询订单接口主动查询订单状态，完成下一步的业务逻辑。

需要调用查询接口的情况：

* 当商户后台、网络、服务器等出现异常，商户系统最终未接收到支付通知；
* 调用支付接口后，返回系统错误或未知交易状态情况；
* 调用刷卡支付API，返回USERPAYING的状态；
* 调用关单或撤销接口API之前，需确认支付状态；

**接口地址**：
`http://xxx.bryzf.com/queryorder`

**是否需要证书**:
不需要


**请求参数**：

|  字段名称  |       变量名        |    必填     |     类型     |               示例值                |                描述                |
| :----: | :--------------: | :-------: | :--------: | :------------------------------: | :------------------------------: |
| 公众账号ID |     `appid`      |     是     | String(32) |        wx8888888888888888        |           微信分配的公众账号ID            |
|  商户号   |     `mch_id`     |     是     | String(32) |            1230000109            |            微信支付分配的商户号            |
| 微信订单号  | `transaction_id` | 与商户订单号二选一 | String(32) |   1009660380201506130728806387   |          微信的订单号，建议优先使用           |
| 商户订单号  |  `out_trade_no`  | 与微信订单号二选一 | String(32) |          20150806125346          | 商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_- |
| 随机字符串  |   `nonce_str`    |     是     | String(32) | C380BEC2BFD727A4B6845133519F3AD6 |      随机字符串，不长于32位。推荐随机数生成算法      |
|   签名   |      `sign`      |     是     | String(32) | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |     通过签名算法计算得出的签名值，详见签名生成算法      |
|  签名类型  |   `sign_type`    |     否     | String(32) |           HMAC-SHA256            | 签名类型，目前支持HMAC-SHA256和MD5，默认为MD5  |

**请求数据示例**

```
<xml>
   <appid>wx2421b1c4370ec43b</appid>
   <mch_id>10000100</mch_id>
   <nonce_str>ec2316275641faa3aacf3cc599e8730f</nonce_str>
   <transaction_id>1008450740201411110005820873</transaction_id>
   <sign>FDD167FAA73459FD921B144BAF4F4CA2</sign>
</xml>
```
**返回结果**

|  字段名  |      变量名       |  必填  |     类型      |   示例值   |                    描述                    |
| :---: | :------------: | :--: | :---------: | :-----: | :--------------------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS | SSUCCESS/FAIL,此字段是通信标识，非交易标识，交易是否成功需要查看trade_state来判断 |
| 返回信息  | `return_msg `  |  否   | String(128) |  签名失败   |       返回信息，如非空，为错误原因,签名失败,参数格式校验错误       |

**以下字段在return_code为SUCCESS的时候有返回**

|  字段名   |       变量名       |  必填  |     类型      |               示例值                |           描述           |
| :----: | :-------------: | :--: | :---------: | :------------------------------: | :--------------------: |
| 公众账号ID |     `appid`     |  是   | String(32)  |        wx8888888888888888        |      微信分配的公众账号ID       |
|  商户号   |    `mch_id`     |  是   | String(32)  |            1230000109            |       微信支付分配的商户号       |
| 随机字符串  |   `nonce_str`   |  是   | String(32)  | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS | 随机字符串，不长于32位。推荐随机数生成算法 |
|   签名   |     `sign`      |  是   | String(32)  | C380BEC2BFD727A4B6845133519F3AD6 |      签名，详见签名生成算法       |
|  业务结果  |  `result_code`  |  是   | String(16)  |             SUCCESS              |      SUCCESS/FAIL      |
|  错误代码  |   `err_code`    |  否   | String(32)  |           SYSTEMERROR            |          错误码           |
| 错误代码描述 | `	err_code_des` |  否   | String(128) |               系统错误               |         结果信息描述         |

**以下字段在return_code 、result_code、trade_state都为SUCCESS时有返回 ，如trade_state不为 SUCCESS，则只返回out_trade_no（必传）和attach（选传）。**

|    字段名    |          变量名           |  必填  |     类型      |             示例值              |                    描述                    |
| :-------: | :--------------------: | :--: | :---------: | :--------------------------: | :--------------------------------------: |
|    设备号    |     `device_info`      |  否   | String(32)  |       013467007045764        |               终端设备号(门店编号)                |
|   用户标识    |       `openid `        |  是   | String(128) | oUpF8uMuAJO_M2pxb1Q9zNjWeS6o |             用户在商户appid下的唯一标识             |
| 是否关注公众账号  |    `is_subscribe `     |  否   |  String(1)  |              Y               |    用户是否关注公众账号，Y-关注，N-未关注，仅在公众账号类型支付有效    |
|   交易类型    |      `trade_type`      |  是   | String(16)  |            JSAPI             | 调用接口提交的交易类型，取值如下：JSAPI，NATIVE，APP，MICROPAY，详细说明见参数规定 |
|   交易状态    |     `trade_state`      |  是   | String(32)  |           SUCCESS            | SUCCESS—支付成功,REFUND—转入退款,NOTPAY—未支付,CLOSED—已关闭,REVOKED—已撤销（刷卡支付）,USERPAYING--用户支付中,PAYERROR--支付失败(其他原因，如银行返回失败),支付状态机请见下单API页面 |
|   付款银行    |      `bank_type`       |  是   | String(16)  |             CMC              |            银行类型，采用字符串类型的银行标识             |
|   标价金额    |      `total_fee`       |  是   |     Int     |             100              |             订单总金额，单位为分，只能为整数             |
|  应结订单金额   | `settlement_total_fee` |  否   |     Int     |             100              | 当订单使用了免充值型优惠券后返回该参数，应结订单金额=订单金额-免充值优惠券金额。 |
|   标价币种    |      `fee_type `       |  否   |  String(8)  |             CNY              | 货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见[货币类型](https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=4_2) |
|  现金支付金额   |      `cash_fee `       |  是   |     Int     |             100              | 现金支付金额订单现金支付金额，详见[支付金额](https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=4_2) |
|  现金支付币种   |    `cash_fee_type `    |  否   | String(16)  |             CNY              | 货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见[货币类型](https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=4_2) |
|   代金券金额   |     `coupon_fee `      |  是   |     Int     |             100              | “代金券”金额<=订单金额，订单金额-“代金券”金额=现金支付金额，详见[支付金额](https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=4_2) |
|  代金券使用数量  |    `coupon_count `     |  否   |     Int     |              1               |                 代金券使用数量                  |
|   代金券类型   |    `coupon_type_$n`    |  否   |  String(8)  |             CASH             | CASH--充值代金券 NO_CASH---非充值代金券 订单使用代金券时返回（取值：CASH、NO_CASH）。`$n`为下标,从0开始编号，举例：coupon_type_0 |
|   代金券ID   |     `coupon_id_$n`     |  否   | String(20)  |            10000             |          代金券ID, `$n`为下标，从0开始编号           |
| 单个代金券支付金额 |    `coupon_fee_$n`     |  否   |     Int     |             100              |        单个代金券支付金额, `$n`为下标，从0开始编号         |
|  微信支付订单号  |    `transaction_id`    |  是   | String(32)  | 1009660380201506130728806387 |                 微信支付订单号                  |
|   商户订单号   |    `out_trade_no `     |  是   | String(32)  |        20150806125346        |     商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-     |
|   活动名称    |    `discount_name`     |  否   | String(16)  |           国庆礼品卡活动            |               市场营销设置的活动名称                |
|   优惠ID    |     `discount_id`      |  否   | String(16)  |              25              |                 优惠活动的ID                  |
|   优惠金额    |     `discount_fee`     |  否   |     Int     |             100              |              用户实际支付时候的优惠金额               |
|   附加数据    |        `attach`        |  否   | String(128) |             深圳分店             |                附加数据，原样返回                 |
|  支付完成时间   |       `time_end`       |  是   | String(14)  |        20141030133525        | 订单支付时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。其他详见时间规则 |
|  交易状态描述   |   `trade_state_desc`   |  是   | String(256) |         支付失败，请重新下单支付         |          对当前查询订单状态的描述和下一步操作的指引           |

**返回数据示例**

```
<xml>
   <return_code><![CDATA[SUCCESS]]></return_code>
   <return_msg><![CDATA[OK]]></return_msg>
   <appid>wx2421b1c4370ec43b</appid>
   <mch_id><![CDATA[10000100]]></mch_id>
   <device_info><![CDATA[1000]]></device_info>
   <nonce_str><![CDATA[TN55wO9Pba5yENl8]]></nonce_str>
   <sign><![CDATA[BDF0099C15FF7BC6B1585FBB110AB635]]></sign>
   <result_code><![CDATA[SUCCESS]]></result_code>
   <trade_type><![CDATA[MICROPAY]]></trade_type>
   <bank_type><![CDATA[CCB_DEBIT]]></bank_type>
   <total_fee>1</total_fee>
   <fee_type><![CDATA[CNY]]></fee_type>
   <transaction_id><![CDATA[1008450740201411110005820873]]></transaction_id>
   <out_trade_no><![CDATA[1415757673]]></out_trade_no>
   <attach><![CDATA[订单额外描述]]></attach>
   <discount_name><![CDATA[国庆活动]]></discount_name>
   <discount_id>25</discount_id>
   <discount_fee>0</discount_fee>
   <time_end><![CDATA[20141111170043]]></time_end>
   <trade_state><![CDATA[SUCCESS]]></trade_state>
</xml>
```

**错误码**

|      名称       |    描述     |       原因       |                  解决方案                  |
| :-----------: | :-------: | :------------: | :------------------------------------: |
| ORDERNOTEXIST | 此交易订单号不存在 | 查询系统中不存在此交易订单号 | 该API只能查提交支付交易返回成功的订单，请商户检查需要查询的订单号是否正确 |
|  SYSTEMERROR  |   系统错误    |    后台系统返回错误    |             系统异常，请再调用发起查询              |

### 关闭订单接口

以下情况需要调用关单接口：商户订单支付失败需要生成新单号重新发起支付，要对原订单号调用关单，避免重复支付；系统下单后，用户支付超时，系统退出不再受理，避免用户继续，请调用关单接口。

注意：订单生成后不能马上调用关单接口，最短调用时间间隔为5分钟。

**接口地址**：
`http://xxx.bryzf.com/closeorder`

**是否需要证书** :
不需要

**请求参数**：

|  字段名称  |      变量名       |  必填  |     类型     |               示例值                |                描述                |
| :----: | :------------: | :--: | :--------: | :------------------------------: | :------------------------------: |
| 公众账号ID |    `appid`     |  是   | String(32) |        wx8888888888888888        |           微信分配的公众账号ID            |
|  商户号   |    `mch_id`    |  是   | String(32) |            1900000109            |            微信支付分配的商户号            |
| 商户订单号  | `out_trade_no` |  是   | String(32) |   1217752501201407033233368018   | 商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_- |
| 随机字符串  |  `nonce_str`   |  是   | String(32) | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |      随机字符串，不长于32位。推荐随机数生成算法      |
|   签名   |     `sign`     |  是   | String(32) | C380BEC2BFD727A4B6845133519F3AD6 |           签名，详见签名生成算法            |


**提交参数示例**


```
<xml>
   <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
   <mch_id>10000100</mch_id>
   <nonce_str>b7ffb16a7150cf08639db472c5f5bdae</nonce_str>
   <out_trade_no>1415717424</out_trade_no>
   <sign>9B2EA16C05A5CEF8E53B14D53932D012</sign>
</xml>
```


**返回结果**


|  字段名  |      变量名       |  必填  |     类型      |   示例值   |                    描述                    |
| :---: | :------------: | :--: | :---------: | :-----: | :--------------------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS | SUCCESS/FAIL,此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断 |
| 返回信息  | `return_msg `  |  否   | String(128) |  签名失败   |    返回信息，如非空，为错误原因;签名失败;具体某个参数格式校验错误.     |


当return_code为SUCCESS的时候，还会包括以下字段：

|  字段名称  |      变量名       |  必填  |     类型      |               示例值                |       描述       |
| :----: | :------------: | :--: | :---------: | :------------------------------: | :------------: |
| 公众账号ID |    `appid`     |  是   | String(32)  |        wx8888888888888888        |  微信分配的公众账号ID   |
|  商户号   |    `mch_id`    |  是   | String(32)  |            1900000109            |    返回提交的商户号    |
| 随机字符串  |  `nonce_str`   |  是   | String(32)  | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |   微信返回的随机字符串   |
|   签名   |     `sign`     |  是   | String(32)  | C380BEC2BFD727A4B6845133519F3AD6 | 返回数据的签名，详见签名算法 |
|  业务结果  | `result_code`  |  是   | String(16)  |             SUCCESS              |  SUCCESS/FAIL  |
| 业务结果描述 | `result_msg `  |  是   | String(32)  |                OK                |  对于业务执行的详细描述   |
|  错误代码  |   `err_code`   |  否   | String(32)  |           SYSTEMERROR            |    详细参见错误列表    |
|  错误描述  | `err_code_des` |  否   | String(128) |               系统错误               |     结果信息描述     |

**返回参数举例**


```
<xml>
   <return_code><![CDATA[SUCCESS]]></return_code>
   <return_msg><![CDATA[OK]]></return_msg>
   <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
   <mch_id><![CDATA[10000100]]></mch_id>
   <nonce_str><![CDATA[o5bAKF3o2ypC8hwa]]></nonce_str>
   <sign><![CDATA[6F5080EDDD196FFCDE53F786BBB93899]]></sign>
   <result_code><![CDATA[SUCCESS]]></result_code>
   <result_msg><![CDATA[OK]]></result_msg>
</xml>
```

**错误码**


|         名称          |    描述     |            原因            |           解决方案           |
| :-----------------: | :-------: | :----------------------: | :----------------------: |
|      ORDERPAID      |   订单已支付   | 订单已支付，不能发起关单，请当作已支付的正常交易 | 订单已支付，不能发起关单，请当作已支付的正常交易 |
|     SYSTEMERROR     |   系统错误    |           系统错误           |      系统异常，请重新调用该API      |
|     ORDERCLOSED     |   订单已关闭   |          该订单已关           |     商户订单号异常，请重新下单支付      |
|      SIGNERROR      |   签名错误    |        参数签名结果不正确         |  请检查签名参数和方法是否都符合签名算法要求   |
| REQUIRE_POST_METHOD | 请使用post方法 |       未使用post传递参数        |   请检查请求参数是否通过post方法提交    |
|  XML_FORMAT_ERROR   |  XML格式错误  |         XML格式错误          |      请检查XML参数格式是否正确      |



### 申请退款接口

当交易发生之后一段时间内，由于买家或者卖家的原因需要退款时，卖家可以通过退款接口将支付款退还给买家，微信支付将在收到退款请求并且验证成功之后，按照退款规则将支付款按原路退到买家帐号上。

注意：

1. 交易时间超过一年的订单无法提交退款

2. 微信支付退款支持单笔交易分多次退款，多次退款需要提交原支付订单的商户订单号和设置不同的退款单号。申请退款总金额不能超过订单金额。 一笔退款失败后重新提交，请不要更换退款单号，请使用原商户退款单号
3. 请求频率限制：150qps，即每秒钟正常的申请退款请求次数不超过150次
4. 错误或无效请求频率限制：6qps，即每秒钟异常或错误的退款申请请求不超过6次
5. 每个支付订单的部分退款次数不能超过50次
6. 退款金额不能超过实际退款金额


**接口地址**：
`http://xxx.bryzf.com/refund`

**是否需要证书** :
不需要

**请求参数**：

|  字段名称  |       变量名        |    必填     |     类型     |               示例值                |                 描述                  |
| :----: | :--------------: | :-------: | :--------: | :------------------------------: | :---------------------------------: |
| 公众账号ID |     `appid`      |     是     | String(32) |        wx8888888888888888        |             微信分配的公众账号ID             |
|  商户号   |     `mch_id`     |     是     | String(32) |            1900000109            |             微信支付分配的商户号              |
| 随机字符串  |   `nonce_str`    |     是     | String(32) | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |       随机字符串，不长于32位。推荐随机数生成算法        |
|   签名   |      `sign`      |     是     | String(32) | C380BEC2BFD727A4B6845133519F3AD6 |             签名，详见签名生成算法             |
| 微信订单号  | `transaction_id` | 与商户订单号二选一 | String(28) |   1217752501201407033233368018   |         微信生成的订单号，在支付通知中有返回          |
| 商户订单号  |  `out_trade_no`  | 与微信订单号二选一 | String(32) |   1217752501201407033233368018   |  商户系统内部订单号，要求32个字符内，只能是数字、大小写字母`_-  |
| 商户退款单号 | `out_refund_no`  |     是     | String(64) |   1217752501201407033233368018   | 商户系统内部的退款单号，商户系统内部唯一，只能是数字、大小写字母`_- |
|  订单金额  |   `total_fee`    |     是     |    Int     |               100                |       订单总金额，单位为分，只能为整数，详见支付金额       |
|  退款金额  |   `refund_fee`   |     是     |    Int     |               100                |    退款总金额，订单总金额，单位为分，只能为整数，详见支付金额    |

**举例**

```
<xml>
   <appid>wx2421b1c4370ec43b</appid>
   <mch_id>10000100</mch_id>
   <nonce_str>6cefdb308e1e2e8aabd48cf79e546a02</nonce_str>
   <out_refund_no>1415701182</out_refund_no>
   <out_trade_no>1415757673</out_trade_no>
   <refund_fee>1</refund_fee>
   <total_fee>1</total_fee>
   <transaction_id></transaction_id>
   <sign>FE56DD4AA85C0EECA82C35595A69E153</sign>
</xml>
```

**返回结果**

|  字段名  |      变量名       |  必填  |     类型      |   示例值   |                    描述                    |
| :---: | :------------: | :--: | :---------: | :-----: | :--------------------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS | SSUCCESS/FAIL此字段是通信标识，非交易标识，交易是否成功需要查看trade_state来判断 |
| 返回信息  | `return_msg `  |  否   | String(128) |  签名失败   |    返回信息，如非空，为错误原因;签名失败;具体某个参数格式校验错误.     |

**以下字段在return_code为SUCCESS的时候有返回**


|    字段名    |           变量名           |  必填  |     类型     |               示例值                |                    描述                    |
| :-------: | :---------------------: | :--: | :--------: | :------------------------------: | :--------------------------------------: |
|   业务结果    |      `result_code`      |  是   | String(16) |             SUCCESS              | SUCCESS/FAILSUCCESS退款申请接收成功，结果通过退款查询接口查询FAIL 提交业务失败 |
|   错误代码    |       `err_code`        |  否   | String(32) |           SYSTEMERROR            |                列表详见错误码列表                 |
|  错误代码描述   |     `err_code_des`      |  否   | String(32) |               系统错误               |                  结果信息描述                  |
|  公众账号ID   |         `appid`         |  是   | String(32) |        wx8888888888888888        |               微信分配的公众账号ID                |
|    商户号    |        `mch_id`         |  是   | String(32) |            1900000109            |                微信支付分配的商户号                |
|   随机字符串   |       `nonce_str`       |  是   | String(32) | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |               随机字符串，不长于32位               |
|    签名     |         `sign`          |  是   | String(32) | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |               签名，详见签名生成算法                |
|   微信订单号   |     transaction_id      |  是   | String(28) |   4007752501201407033233368018   |                  微信订单号                   |
|   商户订单号   |     `out_trade_no`      |  是   | String(32) |             33368018             |    商户系统内部订单号，要求32个字符内，只能是数字、大小写字母 `_-    |
|  商户退款单号   |     `out_refund_no`     |  是   | String(64) |            121775250             |    商户系统内部的退款单号，商户系统内部唯一，只能是数字、大小写字母_-    |
|  微信退款单号   |       `refund_id`       |  是   | String(32) |   2007752501201407033233368018   |                  微信退款单号                  |
|   退款金额    |      `refund_fee`       |  是   |    Int     |               100                |            退款总金额,单位为分,可以做部分退款            |
|  应结退款金额   | `settlement_refund_fee` |  否   |    Int     |               100                | 去掉非充值代金券退款金额后的退款金额，退款金额=申请退款金额-非充值代金券退款金额，退款金额<=申请退款金额 |
|   标价金额    |      `total_fee `       |  是   |    Int     |               100                |         订单总金额，单位为分，只能为整数，详见支付金额          |
|  应结订单金额   | `settlement_total_fee`  |  否   |    Int     |               100                | 当订单使用了免充值型优惠券后返回该参数，应结订单金额=订单金额-免充值优惠券金额。 |
|   标价币种    |       `fee_type`        |  否   | String(8)  |               CNY                | 订单金额货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型 |
|  现金支付金额   |       `cash_fee`        |  是   |    Int     |               100                |         现金支付金额，单位为分，只能为整数，详见支付金额         |
|  现金支付币种   |     `cash_fee_type`     |  否   | String(16) |               CNY                | 货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型 |
|  现金退款金额   |    `cash_refund_fee`    |  是   |    Int     |               100                |            现金退款金额，单位为分，只能为整数             |
|   代金券类型   |    `coupon_type_$n`     |  否   | String(8)  |               CASH               | CASH--充值代金券 NO_CASH---非充值代金券 订单使用代金券时有返回（取值：CASH、NO_CASH）。 `$n`为下标,从0开始编号，举例：coupon_type_0 |
| 代金券退款总金额  |   `coupon_refund_fee`   |  否   |    Int     |               100                |    代金券退款金额<=退款金额，退款金额-代金券或立减优惠退款金额为现金    |
| 单个代金券退款金额 | `coupon_refund_fee_$n ` |  否   |    Int     |               100                |    代金券退款金额<=退款金额，退款金额-代金券或立减优惠退款金额为现金    |
| 退款代金券使用数量 |  `coupon_refund_count`  |  否   |    Int     |                1                 |                退款代金券使用数量                 |
|  退款代金券ID  |  `coupon_refund_id_$n`  |  否   | String(20) |              10000               |         退款代金券ID, `$n`为下标，从0开始编号          |


**举例**

```
<xml>
   <return_code><![CDATA[SUCCESS]]></return_code>
   <return_msg><![CDATA[OK]]></return_msg>
   <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
   <mch_id><![CDATA[10000100]]></mch_id>
   <nonce_str><![CDATA[NfsMFbUFpdbEhPXP]]></nonce_str>
   <sign><![CDATA[B7274EB9F8925EB93100DD2085FA56C0]]></sign>
   <result_code><![CDATA[SUCCESS]]></result_code>
   <transaction_id><![CDATA[1008450740201411110005820873]]></transaction_id>
   <out_trade_no><![CDATA[1415757673]]></out_trade_no>
   <out_refund_no><![CDATA[1415701182]]></out_refund_no>
   <refund_id><![CDATA[2008450740201411110000174436]]></refund_id>
   <refund_channel><![CDATA[]]></refund_channel>
   <refund_fee>1</refund_fee>
</xml>
```


**错误码**

|          名称           |          描述          |            原因             |              解决方案               |
| :-------------------: | :------------------: | :-----------------------: | :-----------------------------: |
|      SYSTEMERROR      |        接口返回错误        |           系统超时等           |   请不要更换商户退款单号，请使用相同参数再次调用API。   |
|   BIZERR_NEED_RETRY   | 退款业务流程错误，需要商户触发重试来解决 |   并发情况下，业务被拒绝，商户重试即可解决    |   请不要更换商户退款单号，请使用相同参数再次调用API。   |
|     TRADE_OVERDUE     |      订单已经超过退款期限      | 订单已经超过可退款的最大期限(支付后一年内可退款) |           请选择其他方式自行退款           |
|         ERROR         |         业务错误         |        申请退款业务发生错误         |  该错误都会返回具体的错误原因，请根据实际返回做相应处理。   |
| USER_ACCOUNT_ABNORMAL |        退款请求失败        |          用户帐号注销           |     此状态代表退款申请失败，商户可自行处理退款。      |
| INVALID_REQ_TOO_MUCH  |        无效请求过多        |     连续错误请求数过多被系统短暂屏蔽      |   请检查业务是否正常，确认业务正常后请在1分钟后再来重试   |
|       NOTENOUGH       |         余额不足         |        商户可用退款余额不足         | 此状态代表退款申请失败，商户可根据具体的错误提示做相应的处理。 |
| INVALID_TRANSACTIONID |   无效transaction_id   |       请求参数未按指引进行填写        | 请求参数错误，检查原交易号是否存在或发起支付交易接口返回失败  |
|      PARAM_ERROR      |         参数错误         |       请求参数未按指引进行填写        |       请求参数错误，请重新检查再调用退款申请       |
|    MCHID_NOT_EXIST    |       MCHID不存在       |        参数中缺少MCHID         |          请检查MCHID是否正确           |
|  REQUIRE_POST_METHOD  |      请使用post方法       |        未使用post传递参数        |       请检查请求参数是否通过post方法提交       |
|       SIGNERROR       |         签名错误         |         参数签名结果不正确         |      请检查签名参数和方法是否都符合签名算法要求      |
|   XML_FORMAT_ERROR    |       XML格式错误        |          XML格式错误          |         请检查XML参数格式是否正确          |
|   FREQUENCY_LIMITED   |         频率限制         |     2个月之前的订单申请退款有频率限制     |        该笔退款未受理，请降低频率后重试         |



###查询退款接口

提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。


**接口地址**：
`http://xxx.bryzf.com/refundquery`


**请求参数**：

|  字段名称  |       变量名        |  必填   |     类型     |               示例值                |                    描述                    |
| :----: | :--------------: | :---: | :--------: | :------------------------------: | :--------------------------------------: |
| 公众账号ID |     `appid`      |   是   | String(32) |        wx8888888888888888        |               微信分配的公众账号ID                |
|  商户号   |     `mch_id`     |   是   | String(32) |            1900000109            |                微信支付分配的商户号                |
| 随机字符串  |   `nonce_str`    |   是   | String(32) | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |          随机字符串，不长于32位。推荐随机数生成算法          |
|   签名   |      `sign`      |   是   | String(32) | C380BEC2BFD727A4B6845133519F3AD6 |               签名，详见签名生成算法                |
| 微信订单号  | `transaction_id` | 单号四选一 | String(32) |   1217752501201407033233368018   | 微信订单号查询的优先级是： refund_id > out_refund_no > transaction_id > out_trade_no |
| 商户订单号  |  `out_trade_no`  | 单号四选一 | String(32) |   1217752501201407033233368018   |    商户系统内部订单号，要求32个字符内，只能是数字、大小写字母`_-     |
| 商户退款单号 | `out_refund_no`  | 单号四选一 | String(32) |   1217752501201407033233368018   |    商户系统内部的退款单号，商户系统内部唯一，只能是数字、大小写字母_-    |
| 微信退款单号 |   `refund_id`    | 单号四选一 | String(32) |   1217752501201407033233368018   |           微信生成的退款单号，在申请退款接口有返回           |
|  偏移量   |    `offset `     |   否   |    Int     |                15                | 偏移量，当部分退款次数超过10次时可使用，表示返回的查询结果从这个偏移量开始取记录 |

**举例**

```
<xml>
   <appid>wx2421b1c4370ec43b</appid>
   <mch_id>10000100</mch_id>
   <nonce_str>0b9f35f484df17a732e537c37708d1d0</nonce_str>
   <out_refund_no></out_refund_no>
   <out_trade_no>1415757673</out_trade_no>
   <refund_id></refund_id>
   <transaction_id></transaction_id>
   <sign>66FFB727015F450D167EF38CCC549521</sign>
</xml>
```

**返回数据**

|  字段名  |      变量名       |  必填  |     类型      |   示例值   |              描述              |
| :---: | :------------: | :--: | :---------: | :-----: | :--------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS |        SSUCCESS/FAIL         |
| 返回信息  | `return_msg `  |  否   | String(128) |  签名失败   | 返回信息，如非空，为错误原因,签名失败,参数格式校验错误 |

**以下字段在return_code为SUCCESS的时候有返回**

|    字段名    |            变量名             |  必填  |     类型      |               示例值                |                    描述                    |
| :-------: | :------------------------: | :--: | :---------: | :------------------------------: | :--------------------------------------: |
|   业务结果    |       `result_code`        |  是   | String(16)  |             SUCCESS              | SUCCESS/FAIL,SUCCESS退款申请接收成功，结果通过退款查询接口查询;FAIL |
|    错误码    |         `err_code`         |  否   | String(32)  |           SYSTEMERROR            |                 错误码详见第6节                 |
|   错误描述    |      `	err_code_des`       |  否   | String(128) |               系统错误               |                  结果信息描述                  |
|  公众账号ID   |          `appid`           |  是   | String(32)  |        wx8888888888888888        |               微信分配的公众账号ID                |
|    商户号    |          `mch_id`          |  是   | String(32)  |            1900000109            |                微信支付分配的商户号                |
|   随机字符串   |        `nonce_str`         |  是   | String(32)  | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |               随机字符串，不长于32位               |
|    签名     |           `sign`           |  是   | String(32)  | C380BEC2BFD727A4B6845133519F3AD6 |               签名，详见签名生成算法                |
|   微信订单号   |      `transaction_id`      |  是   | String(32)  |   1217752501201407033233368018   |                  微信订单号                   |
|   商户订单号   |       `out_trade_no`       |  是   | String(32)  |   1217752501201407033233368018   |    商户系统内部订单号，要求32个字符内，只能是数字、大小写字母`_-     |
|   订单金额    |        `total_fee`         |  是   |     Int     |               100                |         订单总金额，单位为分，只能为整数，详见支付金额          |
|  应结订单金额   |   `settlement_total_fee`   |  否   |     Int     |               100                | 当订单使用了免充值型优惠券后返回该参数，应结订单金额=订单金额-免充值优惠券金额。 |
|   货币种类    |         `fee_type`         |  否   |  String(8)  |               CNY                | 订单金额货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型 |
|  现金支付金额   |         `cash_fee`         |  是   |     Int     |               100                |         现金支付金额，单位为分，只能为整数，详见支付金额         |
|   退款笔数    |       `refund_count`       |  是   |     Int     |                1                 |                  退款记录数                   |
|  商户退款单号   |     `out_refund_no_$n`     |  是   | String(32)  |   1217752501201407033233368018   |   商户系统内部的退款单号，商户系统内部唯一，只能是数字、大小写字母`_-    |
|  微信退款单号   |       `refund_id_$n`       |  是   | String(32)  |   1217752501201407033233368018   |                  微信退款单号                  |
|   退款渠道    |    `refund_channel_$n`     |  是   | String(16)  |             ORIGINAL             | ORIGINAL—原路退款 BALANCE—退回到余额 `OTHER_BALANCE`— 原账户异常退到其他余额账户 OTHER_BANKCARD—原银行卡异常退到其他银行卡 |
|  申请退款金额   |      `refund_fee_$n`       |  是   |     Int     |               100                |            退款总金额,单位为分,可以做部分退款            |
|   退款金额    | `settlement_refund_fee_$n` |  否   |     Int     |               100                |   退款金额=申请退款金额-非充值代金券退款金额，退款金额<=申请退款金额    |
|   代金券类型   |    `coupon_type_$n_$m`     |  否   |  String(8)  |               CASH               | CASH--充值代金券 `NO_CASH`---非充值代金券 订单使用代金券时有返回（取值：`CASH`、`NO_CASH`）。 `$n`为下标,`$m`为下标,从0开始编号，举例：`coupon_type_$0_$1` |
| 代金券退款总金额  |   `coupon_refund_fee_$n`   |  否   |     Int     |               100                |    代金券退款金额<=退款金额，退款金额-代金券或立减优惠退款金额为现金    |
| 退款代金券使用数量 |  `coupon_refund_count_$n`  |  否   |     Int     |                1                 |        退款代金券使用数量 ,`$n`为下标,从0开始编号         |
|  退款代金券ID  |  `coupon_refund_id_$n_$m`  |  否   | String(20)  |              10000               |     退款代金券ID, `$n`为下标， `$m`为下标，从0开始编号     |
| 单个代金券退款金额 | `coupon_refund_fee_$n_$m ` |  否   |     Int     |               100                |   单个退款代金券支付金额, `$n`为下标，`$m`为下标，从0开始编号    |
|   退款状态    |     `refund_status_$n`     |  是   | String(16)  |             SUCCESS              | 退款状态：SUCCESS—退款成功 REFUNDCLOSE—退款关闭。PROCESSING—退款处理中 CHANGE—退款异常，退款到银行发现用户的卡作废或者冻结了，导致原路退款银行卡失败，可前往商户平台（pay.weixin.qq.com）-交易中心，手动处理此笔退款。`$n`为下标，从0开始编号。 |
|  退款资金来源   |    `refund_account_$n`     |  否   | String(30)  |   REFUND_SOURCE_RECHARGE_FUNDS   | REFUND_SOURCE_RECHARGE_FUNDS---可用余额退款/基本账户 REFUND_SOURCE_UNSETTLED_FUNDS---未结算资金退款 `$n`为下标，从0开始编号。 |
|  退款入账账户   |  `refund_recv_accout_$n`   |  是   | String(64)  |           招商银行信用卡0403            | 取当前退款单的退款入账方 1）退回银行卡：{银行名称}{卡类型}{卡尾号} 2）退回支付用户零钱: 支付用户零钱 3）退还商户: 商户基本账户 商户结算银行账户 4）退回支付用户零钱通:支付用户零钱通 |
|  退款成功时间   |  `refund_success_time_$n`  |  否   | String(20)  |       2016-07-25 15:26:26        |  退款成功时间，当退款状态为退款成功时有返回。`$n`为下标，从0开始编号。   |


**举例**

```
<xml>
   <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
   <mch_id><![CDATA[10000100]]></mch_id>
   <nonce_str><![CDATA[TeqClE3i0mvn3DrK]]></nonce_str>
   <out_refund_no_0><![CDATA[1415701182]]></out_refund_no_0>
   <out_trade_no><![CDATA[1415757673]]></out_trade_no>
   <refund_count>1</refund_count>
   <refund_fee_0>1</refund_fee_0>
   <refund_id_0><![CDATA[2008450740201411110000174436]]></refund_id_0>
   <refund_status_0><![CDATA[PROCESSING]]></refund_status_0>
   <result_code><![CDATA[SUCCESS]]></result_code>
   <return_code><![CDATA[SUCCESS]]></return_code>
   <return_msg><![CDATA[OK]]></return_msg>
   <sign><![CDATA[1F2841558E233C33ABA71A961D27561C]]></sign>
   <transaction_id><![CDATA[1008450740201411110005820873]]></transaction_id>
</xml>
```

**错误码**

|          名称           |        描述        |      原因       |               解决方案                |
| :-------------------: | :--------------: | :-----------: | :-------------------------------: |
|      SYSTEMERROR      |      接口返回错误      |     系统超时      |           请尝试再次掉调用API。            |
|    REFUNDNOTEXIST     |     退款订单查询失败     | 订单号错误或订单状态不正确 | 请检查订单号是否有误以及订单状态是否正确，如：未支付、已支付未退款 |
| INVALID_TRANSACTIONID | 无效transaction_id | 请求参数未按指引进行填写  |  请求参数错误，检查原交易号是否存在或发起支付交易接口返回失败   |
|      PARAM_ERROR      |       参数错误       | 请求参数未按指引进行填写  |        请求参数错误，请检查参数再调用退款申请        |
|    MCHID_NOT_EXIST    |     MCHID不存在     |  参数中缺少MCHID   |           请检查MCHID是否正确            |
|  REQUIRE_POST_METHOD  |    请使用post方法     |  未使用post传递参数  |        请检查请求参数是否通过post方法提交        |
|       SIGNERROR       |       签名错误       |   参数签名结果不正确   |       请检查签名参数和方法是否都符合签名算法要求       |
|   XML_FORMAT_ERROR    |     XML格式错误      |    XML格式错误    |          请检查XML参数格式是否正确           |


### 撤销订单接口

支付交易返回失败或支付系统超时，调用该接口撤销交易。如果此订单用户支付失败，微信支付系统会将此订单关闭；如果用户支付成功，微信支付系统会将此订单资金退还给用户。

注意：7天以内的交易单可调用撤销，其他正常支付的单如需实现相同功能请调用申请退款API

**接口地址**：
`http://xxx.bryzf.com/reverse`

**是否需要证书**:
不需要

**请求参数**：

|  字段名称  |       变量名        |  必填  |     类型     |               示例值                |                    描述                    |
| :----: | :--------------: | :--: | :--------: | :------------------------------: | :--------------------------------------: |
| 公众账号ID |     `appid`      |  是   | String(32) |        wx8888888888888888        |               微信分配的公众账号ID                |
|  商户号   |     `mch_id`     |  是   | String(32) |            1900000109            |                微信支付分配的商户号                |
| 微信订单号  | `transaction_id` |  否   | String(32) |   1217752501201407033233368018   |               微信的订单号，优先使用                |
| 商户订单号  |  `out_trade_no`  |  是   | String(32) |   1217752501201407033233368018   | 商户系统内部的订单号,transaction_id、out_trade_no二选一，如果同时存在优先级：transaction_id> out_trade_no |
| 随机字符串  |   `nonce_str`    |  是   | String(32) | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |          随机字符串，不长于32位。推荐随机数生成算法          |
|   签名   |      `sign`      |  是   | String(32) | C380BEC2BFD727A4B6845133519F3AD6 |               签名，详见签名生成算法                |


**提交参数示例**


```
<xml>
   <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
   <mch_id>10000100</mch_id>
   <nonce_str>b7ffb16a7150cf08639db472c5f5bdae</nonce_str>
   <out_trade_no>1415717424</out_trade_no>
   <sign>9B2EA16C05A5CEF8E53B14D53932D012</sign>
</xml>
```


**返回结果**


|  字段名  |      变量名       |  必填  |     类型      |   示例值   |                    描述                    |
| :---: | :------------: | :--: | :---------: | :-----: | :--------------------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS | SUCCESS/FAIL,此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断 |
| 返回信息  | `return_msg `  |  否   | String(128) |  签名失败   |    返回信息，如非空，为错误原因;签名失败;具体某个参数格式校验错误.     |


当return_code为SUCCESS的时候，还会包括以下字段：

|  字段名称  |      变量名       |  必填  |     类型      |               示例值                |          描述           |
| :----: | :------------: | :--: | :---------: | :------------------------------: | :-------------------: |
| 公众账号ID |    `appid`     |  是   | String(32)  |        wx8888888888888888        |      微信分配的公众账号ID      |
|  商户号   |    `mch_id`    |  是   | String(32)  |            1900000109            |       返回提交的商户号        |
| 随机字符串  |  `nonce_str`   |  是   | String(32)  | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |      微信返回的随机字符串       |
|   签名   |     `sign`     |  是   | String(32)  | C380BEC2BFD727A4B6845133519F3AD6 |    返回数据的签名，详见签名算法     |
|  业务结果  | `result_code`  |  是   | String(16)  |             SUCCESS              |     SUCCESS/FAIL      |
|  错误代码  |   `err_code`   |  否   | String(32)  |           SYSTEMERROR            |       详细参见错误列表        |
|  错误描述  | `err_code_des` |  否   | String(128) |               系统错误               |        结果信息描述         |
|  是否重调  |    `recall`    |  是   |  String(1)  |                Y                 | 是否需要继续调用撤销，Y-需要，N-不需要 |

**返回参数举例**


```
<xml>
   <return_code><![CDATA[SUCCESS]]></return_code>
   <return_msg><![CDATA[OK]]></return_msg>
   <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
   <mch_id><![CDATA[10000100]]></mch_id>
   <nonce_str><![CDATA[o5bAKF3o2ypC8hwa]]></nonce_str>
   <sign><![CDATA[6F5080EDDD196FFCDE53F786BBB93899]]></sign>
   <result_code><![CDATA[SUCCESS]]></result_code>
   <recall><![CDATA[N]]></recall>
</xml>
```

**错误码**


|          名称           |        描述        |         原因          |                   解决方案                   |
| :-------------------: | :--------------: | :-----------------: | :--------------------------------------: |
|      SYSTEMERROR      |      接口返回错误      |        系统超时         | 请立即调用被扫订单结果查询API，查询当前订单状态，并根据订单的状态决定下一步的操作。 |
| INVALID_TRANSACTIONID | 无效transaction_id |    请求参数未按指引进行填写     |                参数错误，请重新检查                |
|      PARAM_ERROR      |       参数错误       |    请求参数未按指引进行填写     |            请根据接口返回的详细信息检查您的程序            |
|  REQUIRE_POST_METHOD  |    请使用post方法     |     未使用post传递参数     |           请检查请求参数是否通过post方法提交            |
|       SIGNERROR       |       签名错误       |      参数签名结果不正确      |          请检查签名参数和方法是否都符合签名算法要求           |
|    REVERSE_EXPIRE     |      订单无法撤销      | 订单有7天的撤销有效期，过期将不能撤销 |           请检查需要撤销的订单是否超过可撤销有效期           |

### 下载对账单接口


**接口地址**：
`http://xxx.bryzf.com/downloadbill`

**请求参数**：

|  字段名称  |      变量名      |  必填  |     类型     |               示例值                |                    描述                    |
| :----: | :-----------: | :--: | :--------: | :------------------------------: | :--------------------------------------: |
| 公众账号ID |    `appid`    |  是   | String(32) |        wx8888888888888888        |     微信支付分配的公众账号ID（企业号corpid即为此appId）     |
|  商户号   |   `mch_id`    |  是   | String(32) |            1900000109            |                微信支付分配的商户号                |
|  设备号   | `device_info` |  否   | String(32) |         013467007045764          |               微信支付分配的终端设备号               |
| 随机字符串  |  `nonce_str`  |  是   | String(32) | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |          随机字符串，不长于32位。推荐随机数生成算法          |
|   签名   |    `sign`     |  是   | String(32) | C380BEC2BFD727A4B6845133519F3AD6 |               签名，详见签名生成算法                |
|  签名类型  |  `sign_type`  |  否   | String(32) |           HMAC-SHA256            |     签名类型，目前支持HMAC-SHA256和MD5，默认为MD5      |
| 对账单日期  |  `bill_date`  |  是   | String(8)  |             20140603             |           下载对账单的日期，格式：20140603           |
|  账单类型  |  `bill_type`  |  是   | String(8)  |               ALL                | ALL，返回当日所有订单信息，默认值;SUCCESS，返回当日成功支付的订单;REFUND，返回当日退款订单;RECHARGE_REFUND，返回当日充值退款订单（相比其他对账单多一栏“返还手续费”） |
|  压缩账单  |  `tar_type`   |  否   | String(8)  |               GZIP               | 非必传参数，固定值：GZIP，返回格式为.gzip的压缩包账单。不传则默认为数据流形式。 |

**举例**

```
<xml>
  <appid>wx2421b1c4370ec43b</appid>
  <bill_date>20141110</bill_date>
  <bill_type>ALL</bill_type>
  <mch_id>10000100</mch_id>
  <nonce_str>21df7dc9cd8616b56919f20d9f679233</nonce_str>
  <sign>332F17B766FC787203EBE9D6E40457A1</sign>
</xml>
```

**返回数据**

|  字段名  |      变量名       |  必填  |     类型      | 示例值  |               描述               |
| :---: | :------------: | :--: | :---------: | :--: | :----------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | FAIL |              FAIL              |
| 返回信息  | `return_msg `  |  否   | String(128) | 签名失败 | 返回信息，如非空，为错误原因,如：签名失败、参数格式错误等。 |

成功时，数据以文本表格的方式返回，第一行为表头，后面各行为对应的字段内容，字段内容跟查询订单或退款结果一致，具体字段说明可查阅相应接口。 
第一行为表头，根据请求下载的对账单类型不同而不同(由bill_type决定),目前有：

**当日所有订单**

交易时间,公众账号ID,商户号,子商户号,设备号,微信订单号,商户订单号,用户标识,交易类型,交易状态,付款银行,货币种类,总金额,代金券或立减优惠金额,微信退款单号,商户退款单号,退款金额,代金券或立减优惠退款金额，退款类型，退款状态,商品名称,商户数据包,手续费,费率

**当日成功支付的订单**

交易时间,公众账号ID,商户号,子商户号,设备号,微信订单号,商户订单号,用户标识,交易类型,交易状态,付款银行,货币种类,总金额,代金券或立减优惠金额,商品名称,商户数据包,手续费,费率

**当日退款的订单**

交易时间,公众账号ID,商户号,子商户号,设备号,微信订单号,商户订单号,用户标识,交易类型,交易状态,付款银行,货币种类,总金额,代金券或立减优惠金额,退款申请时间,退款成功时间,微信退款单号,商户退款单号,退款金额,代金券或立减优惠退款金额,退款类型,退款状态,商品名称,商户数据包,手续费,费率

从第二行起，为数据记录，各参数以逗号分隔，参数前增加`符号，为标准键盘1左边键的字符，字段顺序与表头一致。
倒数第二行为订单统计标题，最后一行为统计数据

总交易单数，总交易额，总退款金额，总代金券或立减优惠退款金额，手续费总金额

举例如下：

交易时间,公众账号ID,商户号,子商户号,设备号,微信订单号,商户订单号,用户标识,交易类型,交易状态,付款银行,货币种类,总金额,代金券或立减优惠金额,微信退款单号,商户退款单号,退款金额,代金券或立减优惠退款金额,退款类型,退款状态,商品名称,商户数据包,手续费,费率
`2014-11-1016：33：45,`wx2421b1c4370ec43b,`10000100,`0,`1000,`1001690740201411100005734289,`1415640626,`085e
9858e3ba5186aafcbaed1,`MICROPAY,`SUCCESS,`CFT,`CNY,`0.01,`0.0,`0,`0,`0,`0,`,`,`被扫支付测试,`订单额外描述,`0,`0.60%
`2014-11-1016：46：14,`wx2421b1c4370ec43b,`10000100,`0,`1000,`1002780740201411100005729794,`1415635270,`085e
9858e90ca40c0b5aee463,`MICROPAY,`SUCCESS,`CFT,`CNY,`0.01,`0.0,`0,`0,`0,`0,`,`,`被扫支付测试,`订单额外描述,`0,`0.60%

总交易单数,总交易额,总退款金额,总代金券或立减优惠退款金额,手续费总金额
`2,`0.02,`0.0,`0.0,`0

**错误码**

|          名称          |   描述   |          原因           |                   解决方案                   |
| :------------------: | :----: | :-------------------: | :--------------------------------------: |
|     SYSTEMERROR      |  下载失败  |         系统超时          |                 请尝试再次查询。                 |
|  invalid bill_type   |  参数错误  |     请求参数未按指引进行填写      |                参数错误，请重新检查                |
|  data format error   |  参数错误  |     请求参数未按指引进行填写      |                参数错误，请重新检查                |
|  missing parameter   |  参数错误  |     请求参数未按指引进行填写      |                参数错误，请重新检查                |
|      SIGN ERROR      |  参数错误  |     请求参数未按指引进行填写      |                参数错误，请重新检查                |
|    NO Bill Exist     | 账单不存在  | 当前商户号没有已成交的订单，不生成对账单  |         请检查当前商户号在指定日期内是否有成功的交易。          |
|    Bill Creating     | 账单未生成  | 当前商户号没有已成交的订单或对账单尚未生成 | 请先检查当前商户号在指定日期内是否有成功的交易，如指定日期有交易则表示账单正在生成中，请在上午10点以后再下载。 |
|  CompressGZip Error  | 账单压缩失败 |     账单压缩失败，请稍后重试      |               账单压缩失败，请稍后重试               |
| UnCompressGZip Error | 账单解压失败 |     账单解压失败，请稍后重试      |               账单解压失败，请稍后重试               |

### 支付结果通知

**应用场景**

支付完成后，微信会把相关支付结果和用户信息发送给商户，商户需要接收处理，并返回应答。

对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，微信会通过一定的策略定期重新发起通知，尽可能提高通知的成功率，但微信不保证通知最终能成功。 （通知频率为15/15/30/180/1800/1800/1800/1800/3600，单位：秒）

**注意：同样的通知可能会多次发送给商户系统。商户系统必须能够正确处理重复的通知。**
推荐的做法是，当收到通知进行处理时，首先检查对应业务数据的状态，判断该通知是否已经处理过，如果没有处理过再进行处理，如果处理过直接返回结果成功。在对业务数据进行状态检查和处理之前，要采用数据锁进行并发控制，以避免函数重入造成的数据混乱。

**特别提醒：商户系统对于支付结果通知的内容一定要做签名验证,并校验返回的订单金额是否与商户侧的订单金额一致，防止数据泄漏导致出现“假通知”，造成资金损失。**
技术人员可登进微信商户后台扫描加入接口报警群。

**接口链接**

该链接是通过【统一下单API】中提交的参数notify_url设置，如果链接无法访问，商户将无法接收到微信通知。

通知url必须为直接可访问的url，不能携带参数。示例：notify_url：“https://pay.weixin.qq.com/wxpay/pay.action”

**是否需要证书**
不需要。

**通知参数**


|  名称   |      变量名       |  必填  |     类型      |   示例值   |                    描述                    |
| :---: | :------------: | :--: | :---------: | :-----: | :--------------------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS | SUCCESS/FAIL此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断 |
| 返回信息  | `return_msg `  |  否   | String(128) |  签名失败   |      返回信息，如非空，为错误原因 签名失败 参数格式 校验错误       |

**当return_code为SUCCESS的时候，还会包括以下字段**：

|    字段名    |          变量名           |  必填  |     类型      |               示例值                |                    描述                    |
| :-------: | :--------------------: | :--: | :---------: | :------------------------------: | :--------------------------------------: |
|  公众账号ID   |        `appid`         |  是   | String(32)  |        wx8888888888888888        |               微信分配的公众账号ID                |
|    商户号    |        `mch_id`        |  是   | String(32)  |            1900000109            |                调用接口提交的商户号                |
|    设备号    |     `device_info`      |  否   | String(32)  |         013467007045764          |               终端设备号(门店编号)                |
|   随机字符串   |      `nonce_str`       |  是   | String(32)  | 5K8264ILTKCH16CQ2502SI8ZNMTM67VS |                微信返回的随机字符串                |
|    签名     |         `sign`         |  是   | String(32)  | C380BEC2BFD727A4B6845133519F3AD6 |             微信返回的签名，详见签名生成算法             |
|   业务结果    |     `result_code`      |  是   | String(16)  |             SUCCESS              |               SUCCESS/FAIL               |
|   错误代码    |       `err_code`       |  否   | String(32)  |           SYSTEMERROR            |                 详细参见错误列表                 |
|  错误代码描述   |    `	err_code_des`     |  否   | String(128) |               系统错误               |                错误返回的信息描述                 |
|   用户标识    |       `openid `        |  是   | String(128) |   oUpF8uMuAJO_M2pxb1Q9zNjWeS6o   |             用户在商户appid下的唯一标识             |
| 是否关注公众账号  |    `is_subscribe `     |  否   |  String(1)  |                Y                 |    用户是否关注公众账号，Y-关注，N-未关注，仅在公众账号类型支付有效    |
|   交易类型    |      `trade_type`      |  是   | String(16)  |              JSAPI               |             JSAPI、NATIVE、APP             |
|   付款银行    |      `bank_type`       |  是   | String(32)  |               CMC                | 银行类型，采用字符串类型的银行标识，银行类型见[银行列表](https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_2) |
|   订单金额    |      `total_fee`       |  是   |     Int     |               888                |         订单总金额，单位为分，只能为整数，详见支付金额          |
|  应结订单金额   | `settlement_total_fee` |  否   |     Int     |               100                | 当订单使用了免充值型优惠券后返回该参数，应结订单金额=订单金额-免充值优惠券金额。 |
|   货币类型    |       `fee_type`       |  否   | String(16)  |               CNY                | 符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见[货币类型](https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=4_2) |
|  现金支付金额   |      `cash_fee `       |  是   |     Int     |               100                | 现金支付金额订单现金支付金额，详见[支付金额](https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=4_2) |
|  现金支付币种   |    `cash_fee_type `    |  否   | String(16)  |               CNY                | 货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见[货币类型](https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=4_2) |
|  总代金券金额   |     `coupon_fee `      |  是   |     Int     |               100                | “代金券”金额<=订单金额，订单金额-“代金券”金额=现金支付金额，详见[支付金额](https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=4_2) |
|  代金券使用数量  |    `coupon_count `     |  否   |     Int     |                1                 |                 代金券使用数量                  |
|   代金券类型   |    `coupon_type_$n`    |  否   |  String(8)  |               CASH               | CASH--充值代金券 NO_CASH---非充值代金券 订单使用代金券时有返回（取值：CASH、NO_CASH）。 `$n`为下标,从0开始编号，举例：coupon_type_0 |
|   代金券ID   |     `coupon_id_$n`     |  否   | String(20)  |              10000               |          代金券ID, `$n`为下标，从0开始编号           |
| 单个代金券支付金额 |    `coupon_fee_$n`     |  否   |     Int     |               100                |        单个代金券支付金额, `$n`为下标，从0开始编号         |
|  微信支付订单号  |    `transaction_id`    |  是   | String(32)  |   1009660380201506130728806387   |                 微信支付订单号                  |
|   商户订单号   |    `out_trade_no `     |  是   | String(32)  |          20150806125346          |     商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-     |
|   活动名称    |    `discount_name`     |  否   | String(16)  |             国庆礼品卡活动              |               市场营销设置的活动名称                |
|   优惠ID    |     `discount_id`      |  否   | String(16)  |                25                |                 优惠活动的ID                  |
|   优惠金额    |     `discount_fee`     |  否   |     Int     |               100                |              用户实际支付时候的优惠金额               |
|   商家数据包   |        `attach`        |  否   | String(128) |              123456              |                商家数据包，原样返回                |
|  支付完成时间   |       `time_end`       |  是   | String(14)  |          20141030133525          | 订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。详见时间规则 |

**举例**

```
<xml>
  <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
  <attach><![CDATA[支付测试]]></attach>
  <bank_type><![CDATA[CFT]]></bank_type>
  <fee_type><![CDATA[CNY]]></fee_type>
  <is_subscribe><![CDATA[Y]]></is_subscribe>
  <mch_id><![CDATA[10000100]]></mch_id>
  <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
  <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
  <out_trade_no><![CDATA[1409811653]]></out_trade_no>
  <result_code><![CDATA[SUCCESS]]></result_code>
  <return_code><![CDATA[SUCCESS]]></return_code>
  <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
  <sub_mch_id><![CDATA[10000100]]></sub_mch_id>
  <time_end><![CDATA[20140903131540]]></time_end>
  <total_fee>1</total_fee>
  <coupon_fee><![CDATA[10]]></coupon_fee>
  <coupon_count><![CDATA[1]]></coupon_count>
  <coupon_type><![CDATA[CASH]]></coupon_type>
  <coupon_id><![CDATA[10000]]></coupon_id>
  <coupon_fee><![CDATA[100]]></coupon_fee>
  <trade_type><![CDATA[JSAPI]]></trade_type>
  <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
</xml>
```

**返回参数**
商户处理后需要返回的参数：

|  字段名  |      变量名       |  必填  |     类型      |   示例值   |              描述              |
| :---: | :------------: | :--: | :---------: | :-----: | :--------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS |        SSUCCESS/FAIL         |
| 返回信息  | `return_msg `  |  否   | String(128) |   OK    | 返回信息，如非空，为错误原因,签名失败,参数格式校验错误 |

**举例**

```
<xml>
  <return_code><![CDATA[SUCCESS]]></return_code>
  <return_msg><![CDATA[OK]]></return_msg>
</xml>
```
### 交易保障
**应用场景**

商户在调用微信支付提供的相关接口时，会得到微信支付返回的相关信息以及获得整个接口的响应时间。为提高整体的服务水平，协助商户一起提高服务质量，微信支付提供了相关接口调用耗时和返回信息的主动上报接口，微信支付可以根据商户侧上报的数据进一步优化网络部署，完善服务监控，和商户更好的协作为用户提供更好的业务体验。

**接口地址**：
`http://xxx.bryzf.com/report`

**是否需要证书**:
不需要。

**请求参数**：

|  字段名   |       变量名        |  必填  |     类型      |                   示例值                    |                    描述                    |
| :----: | :--------------: | :--: | :---------: | :--------------------------------------: | :--------------------------------------: |
| 公众账号ID |     `appid`      |  是   | String(32)  |            wx8888888888888888            |               微信分配的公众账号ID                |
|  商户号   |     `mch_id`     |  是   | String(32)  |                1900000109                |                调用接口提交的商户号                |
|  设备号   |  `device_info`   |  否   | String(32)  |             013467007045764              |               终端设备号(门店编号)                |
| 随机字符串  |   `nonce_str`    |  是   | String(32)  |     5K8264ILTKCH16CQ2502SI8ZNMTM67VS     |                微信返回的随机字符串                |
|   签名   |      `sign`      |  是   | String(32)  |     C380BEC2BFD727A4B6845133519F3AD6     |             微信返回的签名，详见签名生成算法             |
| 接口URL  | `interface_url ` |  是   | String(127) | https://api.mch.weixin.qq.com/pay/unifiedorder | 报对应的接口的完整URL，类似：https://api.mch.weixin.qq.com/pay/unifiedorder 对于刷卡支付，为更好的和商户共同分析一次业务行为的整体耗时情况，对于两种接入模式，请都在门店侧对一次刷卡支付进行一次单独的整体上报，上报URL指定为：https://api.mch.weixin.qq.com/pay/micropay/total 关于两种接入模式具体可参考本文档章节：[刷卡支付商户接入模式](https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=9_14&index=7) 其它接口调用仍然按照调用一次，上报一次来进行。 |
|接口耗时|`execute_time ` |是|Int|1000|接口耗时情况，单位为毫秒
|返回状态码|`return_code `|是|String(16)|SUCCESS| SSUCCESS/FAIL 此字段是通信标识，非交易标识，交易是否成功需要查看trade_state来判断|
|返回信息|`return_msg `|否| String(128) | 签名失败 |返回信息，如非空，为错误原因,签名失败,参数格式校验错误|
|业务结果|`result_code` |是|String(16)|SUCCESS|SUCCESS/FAIL|
|错误代码|`err_code` |否|String(32)|SYSTEMERROR|详细参见错误列表|
|错误代码描述|`	err_code_des` |否|String(128)|系统错误|错误返回的信息描述|
|商户订单号|`out_trade_no `|是|String(32)|20150806125346|商户系统内部的订单号,商户可以在上报时提供相关商户订单号方便微信支付更好的提高服务质量。|
|访问接口IP|`user_ip ` |是|String(16)|8.8.8.8|发起接口调用时的机器IP |
|商户上报时间|`time ` |否|String(14)|20091227091010|系统时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见[时间规则](https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_2)|

**返回结果**

|  字段名  |      变量名       |  必填  |     类型      |   示例值   |                    描述                    |
| :---: | :------------: | :--: | :---------: | :-----: | :--------------------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS | SSUCCESS/FAIL 此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断 |
| 返回信息  | `return_msg `  |  否   | String(128) |  签名失败   |       返回信息，如非空，为错误原因,签名失败,参数格式校验错误       |

当return_code为SUCCESS的时候，还会包括以下字段：

| 字段名称 |      变量名      |  必填  |     类型     |   示例值   |      描述      |
| :--: | :-----------: | :--: | :--------: | :-----: | :----------: |
| 业务结果 | `result_code` |  是   | String(16) | SUCCESS | SUCCESS/FAIL |

### 授权码查询openid

通过授权码查询公众号Openid，调用查询后，该授权码只能由此商户号发起扣款，直至授权码更新。 

**接口地址**：
`http://xxx.bryzf.com/authcodetoopenid `

**请求参数**：

|  字段名   |     变量名      |  必填  |     类型      |             描述              |
| :----: | :----------: | :--: | :---------: | :-------------------------: |
| 公众账号ID |   `appid`    |  是   | String(32)  |         微信分配的公众账号ID         |
|  商户号   |   `mch_id`   |  是   | String(32)  |         调用接口提交的商户号          |
|  授权码   | `auth_code ` |  是   | String(128) | 扫码支付授权码，设备读取用户微信中的条码或者二维码信息 |
| 随机字符串  | `nonce_str`  |  是   | String(32)  |         5微信返回的随机字符串         |
|   签名   |    `sign`    |  是   | String(32)  |      微信返回的签名，详见签名生成算法       |

**返回结果**

|  字段名  |      变量名       |  必填  |     类型      |                    说明                    |
| :---: | :------------: | :--: | :---------: | :--------------------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS/FAIL 此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断 |
| 返回信息  | `return_msg `  |  否   | String(128) |       返回信息，如非空，为错误原因,签名失败,参数格式校验错误       |

当return_code为SUCCESS的时候，还会包括以下字段：

|  字段名称  |      变量名      |  必填  |     类型     |        说明        |
| :----: | :-----------: | :--: | :--------: | :--------------: |
| 公众账号ID |    `appid`    |  是   | String(32) |   微信分配的公众账号ID    |
|  商户号   |   `mch_id`    |  是   | String(32) |    调用接口提交的商户号    |
| 随机字符串  |  `nonce_str`  |  是   | String(32) |   5微信返回的随机字符串    |
|   签名   |    `sign`     |  是   | String(32) | 微信返回的签名，详见签名生成算法 |
|  业务结果  | `result_code` |  是   | String(16) |     SUCCESS      |
|  错误代码  |  `err_code`   |  否   | String(32) |     SUCCESS      |

以下字段在`return_code` 和`result_code`都为SUCCESS的时候有返回

| 字段名称 |   变量名    |  必填  |     类型      |        说明        |
| :--: | :------: | :--: | :---------: | :--------------: |
| 用户标识 | `openid` |  是   | String(128) | 用户在商户appid下的唯一标识 |


### 获取通用openid

通过本接口可以获取通用openid

**接口地址**：
`http://n1.bryzf.com/openid/zkbr/?url=$url`

**请求方式**：
GET

**返回结果**

|   字段名称   |     变量名      |  必填  |     类型     |                    说明                    |
| :------: | :----------: | :--: | :--------: | :--------------------------------------: |
| 通用openid | `payOpenId ` |  是   | String(32) | 请求成功后，会跳转到`$url`，请在`$url`以GET方式获取		`payOpenId ` |



### 发放普通红包
**发放规则**

1.发送频率限制------默认1800/min

2.发送个数上限------按照默认1800/min算

3.金额限制------默认红包金额为1-200元，如有需要，可前往商户平台进行设置和申请

4.其他其他限制吗？------单个用户可领取红包上线为10个/天，如有需要，可前往商户平台进行设置和申请


**接口地址**：`http://guoran.bryzf.com/sendredpack`

**是否需要证书**: 
是

**请求参数**：

|   字段名   |    字段  |    必填 |   类型    | 示例值 |  描述 |
| :----: | :----------------: | :--: | :----------: | :------------------------------------: | :--------------------------------------: |
| 随机字符串  | `nonce_str`|  是   | String(32)| 5K8264ILTKCH16CQ2502SI8ZNMTM67VS     |  随机字符串，不长于32位              |
|签名        |  `	sign`    |  是   |  String(32)  |              C380BEC2BFD727A4B6845133519F3AD6             |  详见签名生成算法               |
|  商户号    |   `mch_id` |  是   |  String(32)  |  10000098 |微信支付分配的商户号    
|商户订单号|mch_billno|是|String(28)|10000098201411111234567890|商户订单号（每个订单号必须唯一。取值范围：0~9，a~z，A~Z）接口根据商户订单号支持重入，如出现超时可再调用。|                          
|  商户名称 | `send_name`|  是 |  String(32)  |天虹百货|红包发送者名称               |
| 用户openid|`re_openid`|  是  |  String(32)  |接受红包的用户|用户在wxappid下的openid。 5K8264ILTKCH16CQ2502SI8ZNMTM67VS   
| 公众账号appid|`wxappid`|  是  |  String(32)  |wx8888888888888888|微信分配的公众账号ID（企业号corpid即为此appId）。接口传入的所有appid应该为公众号的appid（在mp.weixin.qq.com申请的），不能为APP的appid（在open.weixin.qq.com申请的）。| 付款金额 | `wxappid`|是|  String(128)  |  感谢您参加猜灯谜活动，祝您元宵节快乐！  | 红包祝福语|
| 红包发放总人数| `total_num`|  是   | int  |  1 |红包发放总人数total_num=1|
|红包祝福语|`wishing`|  是   | String(128)  | 感谢您参加猜灯谜活动，祝您元宵节快乐！  | 红包祝福语
|  Ip地址 | `client_ip`|  是 | String(15)|192.168.0.1|调用接口的机器Ip地址               |  
| 场景id| `scene_id`|否|  int  |  PRODUCT_8  | 发放红包使用场景，红包金额大于200或者小于1元时必传`PRODUCT_1`:商品促销`PRODUCT_2`:抽奖`PRODUCT_3`:虚拟物品兑奖 `PRODUCT_4`:企业内部福利`PRODUCT_5`:渠道分润`PRODUCT_6`:保险回馈`PRODUCT_7`:彩票派奖`PRODUCT_8`:税务刮奖|
|备注|remark|是|String(256)|猜越多得越多，快来抢！|备注信息|
|付款金额|`total_amount`|是|	int|1000|	付款金额，单位分|
|活动名称|`	act_name`|是|String(32)|猜灯谜抢红包活动|活动名称|

**请求数据示例**

```      

<xml>

<sign><![CDATA[E1EE61A91C8E90F299DE6AE075D60A2D]]></sign>

<mch_billno><![CDATA[0010010404201411170000046545]]></mch_billno>

<mch_id><![CDATA[888]]></mch_id>

<wxappid><![CDATA[wxcbda96de0b165486]]></wxappid>

<send_name><![CDATA[send_name]]></send_name>

<re_openid><![CDATA[onqOjjmM1tad-3ROpncN-yUfa6uI]]></re_openid>

<total_amount><![CDATA[200]]></total_amount>

<total_num><![CDATA[1]]></total_num>

<wishing><![CDATA[恭喜发财]]></wishing>

<client_ip><![CDATA[127.0.0.1]]></client_ip>

<act_name><![CDATA[新年红包]]></act_name>

<remark><![CDATA[新年红包]]></remark>

<scene_id><![CDATA[PRODUCT_2]]></scene_id>

<consume_mch_id><![CDATA[10000097]]></consume_mch_id>

<nonce_str><![CDATA[50780e0cca98c8c8e814883e5caa672e]]></nonce_str>

<risk_info>posttime%3d123123412%26clientversion%3d234134%26mobile%3d122344545%26deviceid%3dIOS</risk_info>

</xml>

```

**返回参数**


|  字段名   |      变量名       |  必填  |     类型      |   示例值   |说明 |
| :---: | :------------: | :--: | :---------: | :-----: | :--------------------------------------: |
| 返回状态码 | `return_code ` |  是   | String(16)  | SUCCESS | SUCCESS/FAIL此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断 |
| 返回信息  | `return_msg `  |  否   | String(128) |  签名失败   |      返回信息，如非空，为错误原因 签名失败 参数格式 校验错误   |

**当`return_code`为SUCCESS的时候的时候有返回**：

|  字段名   |       变量名       |  必填  |     类型      | 示例值  | 说明 |
| :----: | :-------------: | :--: | :---------: | :------------------------------: | :--------------: |
| 签名 |     `sign`     |  是   | String(32)  | C380BEC2BFD727A4B6845133519F3AD6 |   生成签名方式详见签名生成算法    |
| 业务结果|    `result_code`     |  是   | String(16) |            SUCCESS |SUCCESS/FAIL    |
|  错误代码   |  `err_code`  |  否   | String(32)  |  SYSTEMERROR | 错误码信息 |
| 错误代码描述  |   `err_code_des`   |  否   | String(128)  | 系统错误 | 结果信息描述   |

**以下字段在`return_code`和`result_code`都为SUCCESS的时候有返回**：

|    字段名    |        变量名         |  必填  |      类型      | 示例值 |    说明                   |
| :-------: | :----------------: | :--: | :----------: | :----------------------------------: | :--------------------------------------: |
| 商户订单号 |     `mch_billno`     |  是   | String(28)  |10000098201411111234567890 |   商户订单号（每个订单号必须唯一）组成：mch_id+yyyymmdd+10位一天内不能重复的数字    |
| 商户号|    `mch_id`     |  是   | String(32) |10000098|微信支付分配的商户号
|  公众账号appid|  `wxappid`  |  是   | String(32)  |  wx8888888888888888	 | 商户appid，接口传入的所有appid应该为公众号的appid（在mp.weixin.qq.com申请的），不能为APP的appid（在open.weixin.qq.com申请的）。 |
| 用户openid  |   `re_openid`   |  是   | String(32)  | oxTWIuGaIt6gTKsQRLau2M0yL16E | 接受收红包的用户用户在wxappid下的openid
| 付款金额|   `total_amount`   |  是   | int  | 1000 | 付款金额，单位分 |
| 微信单号|  `send_listid`  |  是   | String(32)  | 100000000020150520314766074200|   红包订单的微信单号|

**成功示例**

```      


<xml>

<return_code><![CDATA[SUCCESS]]></return_code>

<return_msg><![CDATA[发放成功.]]></return_msg>

<result_code><![CDATA[SUCCESS]]></result_code>

<err_code><![CDATA[0]]></err_code>

<err_code_des><![CDATA[发放成功.]]></err_code_des>

<mch_billno><![CDATA[0010010404201411170000046545]]></mch_billno>

<mch_id>10010404</mch_id>

<wxappid><![CDATA[wx6fa7e3bab7e15415]]></wxappid>

<re_openid><![CDATA[onqOjjmM1tad-3ROpncN-yUfa6uI]]></re_openid>

<total_amount>1</total_amount>

</xml>
```
**失败示例**

``` 
<xml>

<return_code><![CDATA[FAIL]]></return_code>

<return_msg><![CDATA[系统繁忙,请稍后再试.]]></return_msg>

<result_code><![CDATA[FAIL]]></result_code>

<err_code><![CDATA[268458547]]></err_code>

<err_code_des><![CDATA[系统繁忙,请稍后再试.]]></err_code_des>

<mch_billno><![CDATA[0010010404201411170000046542]]></mch_billno>

<mch_id>10010404</mch_id>

<wxappid><![CDATA[wx6fa7e3bab7e15415]]></wxappid>

<re_openid><![CDATA[onqOjjmM1tad-3ROpncN-yUfa6uI]]></re_openid>

<total_amount>1</total_amount>

</xml>     
```

**错误码**

|  错误码   |     错误描述   |  原因  |   解决方式     | 
| :---: | :------------: | :--: | :---------: | 
| NO_AUTH | 发放失败，此请求可能存在风险，已被微信拦截 |  用户账号异常，被拦截 | 请提醒用户检查自身帐号是否异常。使用常用的活跃的微信号可避免这种情况。|
| SENDNUM_LIMIT | 该用户今日领取红包个数超过限制 |该用户今日领取红包个数超过你在微信支付商户平台配置的上限|如有需要、请在微信支付商户平台【api安全】中重新配置 【每日同一用户领取本商户红包不允许超过的个数】。|
| ILLEGAL_APPID  | 非法appid，请确认是否为公众号的appid，不能为APP的appid|  错误传入了app的appid   | 接口传入的所有appid应该为公众号的appid（在mp.weixin.qq.com申请的），不能为APP的appid（在open.weixin.qq.com申请的）。 |
|  MONEY_LIMIT  |  红包金额发放限制     |发送红包金额不再限制范围内|每个红包金额必须大于1元，小于200元（可联系微信支付wxhongbao@tencent.com申请调高额度）|
|SEND_FAILED|红包发放失败,请更换单号再重试|该红包已经发放失败|如果需要重新发放，请更换单号再发放|
|FATAL_ERROR|openid和原始单参数不一致|更换了openid，但商户单号未更新|请商户检查代码实现逻辑|
|CA_ERROR|商户API证书校验出错|请求没带商户API证书或者带上了错误的商户API证书|您使用的调用证书有误，请确认是否使用了正确的证书，可以前往商户平台重新下载，证书需与商户号对应，如果要继续付款必须使用原商户订单号重试。|
|SYSTEMERROR|请求已受理，请稍后使用原单号查询发放结果|系统无返回明确发放结果|使用原单号调用接口，查询发放结果，如果使用新单号调用接口，视为新发放请求|
|XML_ERROR|输入xml参数格式错误|请求的xml格式错误，或者post的数据为空|检查请求串，确认无误后重试|
|FREQ_LIMIT|超过频率限制,请稍后再试|受频率限制|请对请求做频率控制（可联系微信支付wxhongbao@tencent.com申请调高）
|NOTENOUGH|帐号余额不足，请到商户平台充值后再重试|账户余额不足|充值后重试|
|OPENID_ERROR|openid和appid不匹配|openid和appid不匹配|发红包的openid必须是本appid下的openid|
|PROCESSING|请求已受理，请稍后使用原单号查询发放结果|红包正在发放中|请在红包发放二十分钟后查询,按照查询结果成功失败进行处理，如果依然是PROCESSING的状态，请使用原商户订单号重试|
|PARAM_ERROR|act_name字段必填,并且少于32个字符|请求的act_name字段填写错误|填写正确的act_name后重试|
|发放金额、最小金额、最大金额必须相等|请求的金额相关字段填写错误|按文档要求填写正确的金额后重试|
|红包金额参数错误|红包金额过大|修改金额重试|
|appid字段必填,最长为32个字符|请求的appid字段填写错误|填写正确的appid后重试|
|商户号和wxappid不匹配|商户号和wxappid不匹配|请修改Mchid或wxappid参数|
|re_openid字段为必填并且少于32个字符|请求的re_openid字段非法|填写对re_openid后重试|




