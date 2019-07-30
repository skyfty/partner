<?php
/**
 * 支付宝接口类
 *
 * 
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */
class alipay{
    private $cacert_url;
	/**
	 *支付宝网关地址（新）
	 */
	private $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';
	/**
	 * 消息验证地址
	 *
	 * @var string
	 */
	private $alipay_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
	/**
	 * 支付接口标识
	 *
	 * @var string
	 */
    private $code      = 'alipay';
    /**
	 * 支付接口配置信息
	 *
	 * @var array
	 */
    private $payment;
     /**
	 * 订单信息
	 *
	 * @var array
	 */
    private $order;
    /**
	 * 发送至支付宝的参数
	 *
	 * @var array
	 */
    private $parameter;
    /**
     * 订单类型 product_buy商品购买,predeposit预存款充值
     * @var unknown
     */
    private $order_type;
    
    public function __construct($payment_info=array(),$order_info=array()){
    	if (!extension_loaded('openssl')) {
            $this->alipay_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
        }
    	if(!empty($payment_info) and !empty($order_info)){
    		$this->payment	= $payment_info;
    		$this->order	= $order_info;
    	}
        $this->cacert_url = getcwd().'/cacert.pem';
    }

    public function format_pay_param() {
        $this->parameter = array(
            'partner'		    => $this->payment['payment_config']['alipay_partner'],	//合作伙伴ID
            'key'               => $this->payment['payment_config']['alipay_key'],
            '_input_charset'	=> "utf-8",					                            //网站编码
            'sign_type'		    => 'MD5',				                                //签名方式
            'return_url'	    => "http://www.ourbaby.cc/?m=User&a=payreturn&payment_code=alipay",	//返回URL
            'notify_url'        => "http://www.ourbaby.cc/?m=User&a=paynotify&payment_code=alipay",	//通知URL
            'subject'		    => $this->order['subject'],	                            //商品名称
            'body'			    => $this->order['pay_sn'],	                            //商品描述
            'out_trade_no'	    => $this->order['pay_sn'],		                        //外部交易编号
            'payment_type'	    => 1,							                        //支付类型
            'seller_email'		=> $this->payment['payment_config']['alipay_account'],	//卖家邮箱
            'receive_name'	    => "",                                                  //收货人姓名
            'receive_address'	=> "",
            'receive_zip'	    => "",
            'receive_phone'	    => "",
            'receive_mobile'	=> "",
        );

        if ($this->payment['payment_blank']) {
            $this->parameter['service'] = "create_direct_pay_by_user"; 	//服务名
            $this->parameter['total_fee'] = $this->order['pay_amount'];
            $this->parameter['exter_invoke_ip'] = "";
            $this->parameter['anti_phishing_key'] = "";
            $this->parameter['paymethod'] = "bankPay";
            $this->parameter['defaultbank'] = $this->payment['payment_blank'];
        } else {
            $this->parameter['service'] = "create_direct_pay_by_user"; 	            //服务名
            $this->parameter['price'] = $this->order['pay_amount'];//订单总价
            $this->parameter['quantity'] = 1;//商品数量
        }
    }

    /**
     * 获取支付接口的请求地址
     *
     * @return string
     */
    public function get_payurl(){
        $this->format_pay_param();
        $this->parameter['sign'] = $this->sign($this->parameter);
        return $this->create_url();
    }

    /**
     * 支付表单
     *
     */
    public function submit(){
        $this->format_pay_param();
        echo($this->buildRequestForm());
    }

    function getHttpResponseGET($url, $para, $input_charset = '') {

        while (list ($key, $val) = each ($para)) {
            $url.= $key.'='.$val."&";
        }

        if (trim($input_charset) != '') {
            $url = $url."_input_charset=".$input_charset;
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$this->cacert_url);//证书地址
        $responseText = curl_exec($curl);

        curl_close($curl);

        return $responseText;
    }

    function getHttpResponsePOST($url, $para, $input_charset = '') {
        if (trim($input_charset) != '') {
            $url = $url."_input_charset=".$input_charset;
        }
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$this->cacert_url);//证书地址
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl,CURLOPT_POST,true); // post传输数据
        curl_setopt($curl,CURLOPT_POSTFIELDS, $para);// post传输数据
        $responseText = curl_exec($curl);
        curl_close($curl);
        return $responseText;
    }

    function buildRequestHttp() {
        $request_data = $this->buildRequestPara();
        return $this->getHttpResponsePOST($this->alipay_gateway_new,$request_data,trim(strtolower($request_data['_input_charset'])));
    }

    function buildRequestForm() {
        //待请求参数数组
        $para = $this->buildRequestPara();
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->alipay_gateway_new."_input_charset=".$para['_input_charset']."' method='post'>";
        while (list ($key, $val) = each ($para)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        return $sHtml."</form><script>document.forms['alipaysubmit'].submit();</script>";
    }

    function buildRequestPara() {
        $sort_array = $this->arg_sort($this->para_filter($this->parameter));

        $sort_array['sign'] = $this->sign($this->parameter);
        $sort_array['sign_type'] = strtoupper(trim($this->parameter['sign_type']));

        return $sort_array;
    }

        /**
	 * 通知地址验证
	 *
	 * @return bool
	 */
	public function notify_verify() {
		$param	= $_POST;
		$param['key']	= $this->payment['payment_config']['alipay_key'];
		$veryfy_url = $this->alipay_verify_url. "partner=" .$this->payment['payment_config']['alipay_partner']. "&notify_id=".$param["notify_id"];
		$veryfy_result  = $this->getHttpResponse($veryfy_url);
		$mysign = $this->sign($param);
		if (preg_match("/true$/i",$veryfy_result) && $mysign == $param["sign"])  {
		    $this->order_type = $param['extra_common_param'];
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 返回地址验证
	 *
	 * @return bool
	 */
	public function return_verify() {
		$param	= $_GET;
		$param['act']	= "";//将系统的控制参数置空，防止因为加密验证出错
		$param['op']	= "";
		$param['key']	= $this->payment['payment_config']['alipay_key'];
		$veryfy_url = $this->alipay_verify_url. "partner=" .$this->payment['payment_config']['alipay_partner']. "&notify_id=".$param["notify_id"];
		$veryfy_result  = $this->getHttpResponse($veryfy_url);
		$mysign = $this->sign($param);
		return preg_match("/true$/i",$veryfy_result) && $mysign == $param["sign"];
	}

	/**
	 * 
	 * 取得订单支付状态，成功或失败
	 * @param array $param
	 * @return array
	 */
	public function getPayResult($param){
		return $param['trade_status'] == 'TRADE_SUCCESS';
	}

	/**
	 * 
	 *
	 * @param string $name
	 * @return 
	 */
	public function __get($name){
	    return $this->$name;
	}

	/**
	 * 远程获取数据
	 * $url 指定URL完整路径地址
	 * @param $time_out 超时时间。默认值：60
	 * return 远程输出的数据
	 */
	private function getHttpResponse($url,$time_out = "60") {
		$urlarr     = parse_url($url);
		$errno      = "";
		$errstr     = "";
		$transports = "";
		$responseText = "";
		if($urlarr["scheme"] == "https") {
			$transports = "ssl://";
			$urlarr["port"] = "443";
		} else {
			$transports = "tcp://";
			$urlarr["port"] = "80";
		}
		$fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
		if(!$fp) {
			die("ERROR: $errno - $errstr<br />\n");
		} else {
			if (trim(CHARSET) == '') {
				fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
			} else {
				fputs($fp, "POST ".$urlarr["path"].'?_input_charset='.CHARSET." HTTP/1.1\r\n");
			}
			fputs($fp, "Host: ".$urlarr["host"]."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $urlarr["query"] . "\r\n\r\n");
			while(!feof($fp)) {
				$responseText .= @fgets($fp, 1024);
			}
			fclose($fp);
			$responseText = trim(stristr($responseText,"\r\n\r\n"),"\r\n");
			return $responseText;
		}
	}

    /**
     * 制作支付接口的请求地址
     *
     * @return string
     */
    private function create_url() {
		$url        = $this->alipay_gateway_new;
		$filtered_array	= $this->para_filter($this->parameter);
		$sort_array = $this->arg_sort($filtered_array);
		$arg        = "";
		while (list ($key, $val) = each ($sort_array)) {
			$arg.=$key."=".urlencode($val)."&";
		}
		$url.= $arg."sign=" .$this->parameter['sign'] ."&sign_type=".$this->parameter['sign_type'];
		return $url;
	}

	/**
	 * 取得支付宝签名
	 *
	 * @return string
	 */
	private function sign($parameter) {
		$mysign = "";
		
		$filtered_array	= $this->para_filter($parameter);
		$sort_array = $this->arg_sort($filtered_array);
		$arg = "";
        while (list ($key, $val) = each ($sort_array)) {
			$arg	.= $key."=".$this->charset_encode($val,(empty($parameter['_input_charset'])?"UTF-8":$parameter['_input_charset']),(empty($parameter['_input_charset'])?"UTF-8":$parameter['_input_charset']))."&";
		}
		$prestr = substr($arg,0,-1);  //去掉最后一个&号
		$prestr	.= $parameter['key'];
        if($parameter['sign_type'] == 'MD5') {
			$mysign = md5($prestr);
		}elseif($parameter['sign_type'] =='DSA') {
			//DSA 签名方法待后续开发
			die("DSA 签名方法待后续开发，请先使用MD5签名方式");
		}else {
			die("支付宝暂不支持".$parameter['sign_type']."类型的签名方式");
		}
		return $mysign;

	}

	/**
	 * 除去数组中的空值和签名模式
	 *
	 * @param array $parameter
	 * @return array
	 */
	private function para_filter($parameter) {
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == "sign" || $key == "sign_type" || $key == "key" || $val == "")
                continue;
			else
                $para[$key] = $parameter[$key];
		}
		return $para;
	}

	/**
	 * 重新排序参数数组
	 *
	 * @param array $array
	 * @return array
	 */
	private function arg_sort($array) {
		ksort($array);
		reset($array);
		return $array;

	}

	/**
	 * 实现多种字符编码方式
	 */
	private function charset_encode($input,$_output_charset,$_input_charset="UTF-8") {
		$output = "";
		if(!isset($_output_charset))$_output_charset  = $this->parameter['_input_charset'];
		if($_input_charset == $_output_charset || $input == null) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset change.");
		return $output;
	}
}