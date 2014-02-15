<?php

class PrintAction extends Action
{

    protected function _initialize()
    {
    }

    public function printOrder()
    {
    	/*
    	 * 准备数据
    	 */
    	$orderInfo = null;
    	
    	$dbGoods = D("Goods");
    	$dbUser = D("User");
    	$dbUser->init("wbx");////////////////////////TODO:session("userName")
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	 
	    	/*
	    	 * //货物信息
	    	*/
    	$tmp["id"] = $dbTmpOrder->getArray("goodsIDArray");
    	$tmp["num"] = $dbTmpOrder->getArray("goodsNumArray");
    	$tmp["money"] = $dbTmpOrder->getArray("goodsMoneyArray");
    	$tmp["size"] = $dbTmpOrder->getArray("goodsSizeArray");
    	$totalNum = 0;
    	for ($i = 0; $i < count($tmp["id"]); $i++)
    	{
    		$dbGoods->init($tmp["id"][$i]);
    		$orderInfo[$i]["goodsName"] = $dbGoods->getGoodsName();
    		$orderInfo[$i]["id"] = $tmp["id"]["$i"];
    		$orderInfo[$i]["num"] = $tmp["num"]["$i"];
    		$orderInfo[$i]["money"] = $tmp["money"]["$i"];
   
    		//渲染规格
    		$tmpSize = null;
    		$tmpSize = $dbGoods->getGoodsSize();//商品规格信息
    		$orderInfo[$i]["size"] = $tmpSize[$tmp["size"]["$i"]];//选中的规格信息
    	 
    		$totalNum += $orderInfo[$i]["num"];
    	}
    	
	    	/*
	    	 * //用户信息
	    	*/
    	$customName = $dbTmpOrder->getTmpOrderCustomName();
    	$dbCustomUser = D("User");
    	$dbCustomUser->init($customName);
    	$tmpRe = $dbCustomUser->getUserInfo($customName);
    	if ( ($tmpRe === false) || ($tmpRe === null) )
    		$this->error("打印准备时查询用户失败，请重试",U("Index/closingOver"));
		
    	$originAllInfo = $dbTmpOrder->getTmpOrderInfo();
    	
    	/*
    	 * 生成输出数据
    	 */
    	$output = "%30黄海水产店出货单%%%00%%+===========================+%%%10";
    	//用户信息
    	$output .= "客户：".$customName."%%%00"
    					."电话：".$tmpRe["tel"]."%%"
    					."位置：".$tmpRe["carAddress"]."%%%30"
    					."车号：".$tmpRe["carNo"]."%%%00+===========================+%%%10";
    	//货物信息
    	for ($i = 0; $i < count($tmp["id"]); $i++)
    	{
    		$output .= $orderInfo[$i]["goodsName"]."   规格:"
    					.$orderInfo[$i]["size"]."%%".$orderInfo[$i]["num"]."件  "
    							."单价:99318  金额:86214789"."%%%00.............................%%%10";
    	}
    	$output .= "总件数：".$totalNum."件%%";
    	$output .= "总金额：123456%%%00";
    	
    	//转码
    	$output = iconv("UTF-8", "GB2312//TRANSLIT//IGNORE", $output);
    	
    	
    	
    	/*
    	 * 打印
    	 */
    	 //采用单例模式，防止传输多个XML
    	$printer = printerClass::getInstance();
    	
    	if (isset($printer->params['id']) && isset($printer->params['sta']))  // 返回打印结果
    	{
    		/*
    		 * @todo
    		*/
    	}
    	else // 传输需要打印的内容
    	{
    		echo $printer->setId($originAllInfo["id"]) // 设置ID
			    		->setTime( strtotime(date("Y-m-d H:i:s")) ) // 设置时间
			    		->setContent($output) // 设置content
			    		->setSetting("103:10") // 设置打印机参数等数据，具体参考协议部分文件，建议非必要不要设置，也可以为空
			    		->display(); // 输出
    	}
    }
    
}


class printerClass {

	private static $_instance;

	private $time;
	private $content = "";
	private $setting = "";
	private $id = "";

	public $params = array();

	private function __construct() {
		$this->getParams();
		$this->time = date('Y-m-d H:i:s');
	}

	private function __clone() {}  //覆盖__clone()方法，禁止克隆

	public static function getInstance()
	{
		if(! (self::$_instance instanceof self) ) {
			self::$_instance = new self();
		}

		/*
		 * 验证是否为正常连接
		*/
		/*
		 if (!isset(self::$_instance->params['usr'])
		 		&& !isset(self::$_instance->params['sgn'])
		 		&& md5(self::$_instance->params['usr']) != self::$_instance->params['sgn'])
		 {
		return false;
		}
		*/
		return self::$_instance;
	}
	/*
	 * 打印终端请求平台下发数据
	*
	*/
	/**
	 +----------------------------------------------------------
	 * 设置时间 时间不能小于2013-08-01 00:00:00 同时 时间不能于大于2030-08-01 00:00:00
	 +----------------------------------------------------------
	 * @param string $timestamp 时间戳
	 +----------------------------------------------------------
	 */
	public function setTime( $timestamp )
	{
		if ($timestamp > 1375315200 && $timestamp < 1911772800) {
			$this->time = date('Y-m-d H:i:s', $timestamp);
		}
		return $this;
	}


	/**
	 +----------------------------------------------------------
	 * 写入内容
	 +----------------------------------------------------------
	 * @param string $content 内容
	 +----------------------------------------------------------
	 */
	public function setContent( $content )
	{
		$this->content = strip_tags($content);
		return $this;
	}

	/**
	 +----------------------------------------------------------
	 * 设置打印机参数
	 +----------------------------------------------------------
	 * @param array $setting 设置 key(响应码) => value(内容)
	 +----------------------------------------------------------
	 */
	public function setSetting( $setting )
	{
		if (!empty($setting) && is_array($setting)) {
			$this->setting = "";
			foreach ($setting as $k => $v) {
				if (is_numeric($k))
				{
					$this->setting .= $k.":".strip_tags($v)."|";
				}
			}
		}
		else
		{
			$this->setting = strip_tags($setting);
		}
		return $this;
	}

	/**
	 +----------------------------------------------------------
	 * 设置ID
	 +----------------------------------------------------------
	 * @param string $id id SYD123456789
	 +----------------------------------------------------------
	 */
	public function setId( $id )
	{
		$this->id = strip_tags($id);
		return $this;
	}


	/**
	 +----------------------------------------------------------
	 * 传输内容是否大于最大内容长度 不能多于2000字节
	 +----------------------------------------------------------
	 * @return boolean
	 +----------------------------------------------------------
	 */
	public function maxLength($str, $length = 2000)
	{
		if (mb_strlen($str) > 2000)
		{
			return false;
		}
		return true;
	}

	/**
	 +----------------------------------------------------------
	 * 生成传输用XML 不能多于2000字节
	 +----------------------------------------------------------
	 * @return string xml
	 +----------------------------------------------------------
	 */
	public function display()
	{

		$xml = '<?xml version="1.0" encoding="GBK"?>';
		$xml .= "<r>";

		$xml .= "<id>".$this->id."</id>";
		$xml .= "<time>".$this->time."</time>";
		$xml .= "<content>".$this->content."</content>";
		$xml .= "<setting>".$this->setting."</setting>";

		$xml .= "</r>";

		if ($this->maxLength($xml)) {
			header("Content-type: text/xml");
			return $xml;
		}
		return false;
	}


	/**
	 +----------------------------------------------------------
	 * 解析返回参数
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	public function getParams()
	{
		$arr = array();

		if (isset($_REQUEST['usr'])) $arr['usr'] = $_REQUEST['usr']; // 用户IMEI号码
		if (isset($_REQUEST['ord'])) $arr['ord'] = $_REQUEST['ord']; // 本次交易的序列号，不得重复
		if (isset($_REQUEST['sgn'])) $arr['sgn'] = $_REQUEST['sgn']; // 交易签名。 MD5(usr)转大写

		if (isset($_REQUEST['id'])) $arr['id'] = $_REQUEST['id']; // 平台下发打印数据的ID号
		if (isset($_REQUEST['sta'])) $arr['sta'] = $_REQUEST['sta']; // 打印机状态（0为打印成功， 1为过热，3为缺纸卡纸等）

		$this->params = $arr;

		return $arr;
	}
}

?>