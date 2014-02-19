<?php
require_once(LIB_PATH."commonAction.php");

class PrintAction extends myAction
{

    protected function _initialize()
    {
    }

    /*
     * 打印第一联单子。目前这里也打印第二联单子
     */
    public function printOneOrder()
    {
    	$blankTag = false;
    	$dbGoods = D("Goods");
    	$dbTmpOrder = D("TmpOrder");
    	$orderArray = null;
    	 
    	/*打印状态
    	 * 	0：不打印
    	*	1:立即打印，还没有发送给打印机
    	*	2：存根已经下发给打印机，但是打印机还没有返回成功打印信号
    	*	3：打印存根成功
    	*	4：发票联已经下发给打印机，但是打印机还没有返回成功打印信号
    	*	5：发票联打印成功
    	*	6：出货单已经下发给打印机，但是打印机还没有返回成功打印信号
    	*	7：出货单已经打印成功，所有打印完成
    	*/
    	$orderArray = $dbTmpOrder->where("printState=1")->order("id")->select();//选出要立即打印的单子
    	$nowPrint = $dbTmpOrder->where("printState=2")->select();
    	if (isset($nowPrint))
    		$orderArray = null;
    	 
    	if (isset($orderArray))//如果有需要打印的数据则打印，没有则输出空
    	{
    		/*
    		 * 准备数据
    		*/
    		$k = count($orderArray) - 1;
    		$orderInfo = null;
    		$dbTmpOrder->init($orderArray[$k]["id"]);
    	
    		/*
    		 * //货物信息
    		*/
    		$tmp["id"] = $dbTmpOrder->getArray("goodsIDArray");
    		$tmp["num"] = $dbTmpOrder->getArray("goodsNumArray");
    		$tmp["money"] = $dbTmpOrder->getArray("goodsMoneyArray");
    		$tmp["size"] = $dbTmpOrder->getArray("goodsSizeArray");
    		$totalNum = 0;
    		$totalMoney = 0;
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
    			$totalMoney += $orderInfo[$i]["money"] * $orderInfo[$i]["num"];
    		}
    	
    		/*
    		* //用户信息
    		*/
    		$customName = $dbTmpOrder->getTmpOrderCustomName();
    		$dbCustomUser = D("User");
    		$dbCustomUser->init($customName);
    		$tmpRe = $dbCustomUser->getUserInfo($customName);
    		if ( ($tmpRe === false) || ($tmpRe === null) )
    		$this->error("打印准备时查询用户失败，请重试",U("Index/closingadministrator	len"));
    			
    		$originAllInfo = $dbTmpOrder->getTmpOrderInfo();
    	
    	
	    	/*
    		* 生成输出数据
    		*/
    		$output = "";
    		
	    		/*
	    		* //第一次打印时添加打印时间
	    		*/
    		$data = "";
    		$data["id"] = $orderArray[$k]["id"];
    		$data["createDate"] = date("Y-m-d H:i:s");
	    	$this->isFalsePlus($dbTmpOrder->save($data),"数据库添加打印时间出错，请重试","Index/goBack");
    		    		 

	    	$output = "%30黄海水产--存根联%%%10   (博大 耘垦 安井)西北专卖%%";
    		//订单信息
            $output .= "%10     ".$originAllInfo["createDate"]."%%%00+===========================+%10";
    		//用户信息
    		$output .= "客户：".$customName."%%%00"
		    				."电话：".$tmpRe["tel"]."%%"
    			    		."位置：".$tmpRe["carAddress"]."%%%30"
    			    		."车号：".$tmpRe["carNo"]."%%%00+===========================+%%%10";
    		//货物信息
    		for ($i = 0; $i < count($tmp["id"]); $i++)
		    {
    			$output .= $orderInfo[$i]["goodsName"]."   规格:"
    			    	.$orderInfo[$i]["size"]."%%".$orderInfo[$i]["num"]."件   "
    			    	."单价:".$orderInfo[$i]["money"]."  金额:".($orderInfo[$i]["num"]*$orderInfo[$i]["money"])."%%%00.............................%%%10";
    		}
    		$output .= "总件数：".$totalNum."件%%";
    		$output .= "总金额：".$totalMoney."%%%00"
                	."以上商品均已履行进货检查验收法定程序，索验票证齐全，供货者特此声明。%%%00";
    			    					 
		    //转码
		  
		  
    		/*
		    * 打印发票联
		    */
    		$output .= "%%%%%%%%%%";
    		$output .= "%30黄海水产--发票联%%%10   (博大 耘垦 安井)西北专卖%%";
    		//订单信息
    		$output .= "%10     ".$originAllInfo["createDate"]."%%%00方欣国际食品城一楼125号(后门口)电话:029-83106853 15029483465+===========================+%10";
    		//用户信息
		    $output .= "客户：".$customName."%%%00"
    			    ."电话：".$tmpRe["tel"]."%%"
    			    ."位置：".$tmpRe["carAddress"]."%%%30"
		    		."车号：".$tmpRe["carNo"]."%%%00+===========================+%%%10";
		    //货物信息
    		for ($i = 0; $i < count($tmp["id"]); $i++)
    		{
		    	$output .= $orderInfo[$i]["goodsName"]."   规格:"
		    			.$orderInfo[$i]["size"]."%%".$orderInfo[$i]["num"]."件   "
    			    	."单价:".$orderInfo[$i]["money"]."  金额:".($orderInfo[$i]["num"]*$orderInfo[$i]["money"])."%%%00.............................%%%10";
    		}
		    $output .= "总件数：".$totalNum."件%%";
    		$output .= "总金额：".$totalMoney."%%%00"
    				."以上商品均已履行进货检查验收法定程序，索验票证齐全，供货者特此声明。此联由批发单位直接用于批发台帐资料留存。%%%20   多谢惠顾%%%00";
		    
		    //转码
		    $output = iconv("UTF-8", "GB2312//TRANSLIT//IGNORE", $output);
    	}
    	else//如果不存在需要打印的单子
    	{
    		//为空输出
    		$blankTag = true;
    	}
    		    												 
    	    	 
    	/*
    	* 打印
    	*/
    	//采用单例模式，防止传输多个XML
    	$printer = printerClass::getInstance();
    		    									 
    	if (isset($printer->params['id']) && isset($printer->params['sta']))  // 返回打印结果
    	{
    		 if ($printer->params['sta'] == 0)//0为打印成功
    		 {
    		 	$dbTmpOrder->init($printer->params['id']);
    		    $dbTmpOrder->updatePrintState(5);
    		 }
    	}
    	elseif (!$blankTag)// 传输需要打印的内容
    	{
    		echo $printer->setId($originAllInfo["id"]) // 设置ID
    		->setTime( strtotime(date("Y-m-d H:i:s")) ) // 设置时间
    	    		->setContent($output) // 设置content
    	    		->setSetting("103:10") // 设置打印机参数等数据，具体参考协议部分文件，建议非必要不要设置，也可以为空
    	    		->display(); // 输出
    	    $dbTmpOrder->updatePrintState(2);
    	}
    }
    
    /*
     * 库房那边的打印机输出页面
     */
    public function printThreeOrder()
    {
    	$blankTag = false;
    	$dbGoods = D("Goods");
    	$dbTmpOrder = D("TmpOrder");
    	$orderArray = null;
    	
    	/*打印状态
    	 * 	0：不打印
    	*	1:立即打印，还没有发送给打印机
    	*	2：存根已经下发给打印机，但是打印机还没有返回成功打印信号
    	*	3：打印存根成功
    	*	4：发票联已经下发给打印机，但是打印机还没有返回成功打印信号
    	*	5：发票联打印成功
    	*	6：出货单已经下发给打印机，但是打印机还没有返回成功打印信号
    	*	7：出货单已经打印成功，所有打印完成
    	*/
   		$orderArray = $dbTmpOrder->where("printState=5")->order("id")->select();//选出要立即打印的单子
   		$nowPrint = $dbTmpOrder->where("printState=6")->select();
   		if (isset($nowPrint))
   			$orderArray = null;
    	
    	if (isset($orderArray))//如果有需要打印的数据则打印，没有则输出空
    	{
    		/*
    		 * 准备数据
    		*/
    		$k = count($orderArray) - 1;
    		$orderInfo = null;
    		$dbTmpOrder->init($orderArray[$k]["id"]);
    		
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
	    	$output = "";
	    	
    		$output = "%30黄海水产--出货单%%";
    		//订单信息
            $output .= "%10     ".$originAllInfo["createDate"]."%%%00+===========================+%10";
    		//用户信息
    		$output .= "客户：".$customName."%%%00"
    						."位置：".$tmpRe["carAddress"]."%%%30"
    						."车号：".$tmpRe["carNo"]."%%%00+===========================+%%%10";
    		//货物信息
    		for ($i = 0; $i < count($tmp["id"]); $i++)
    		{
    			$output .= $orderInfo[$i]["goodsName"]."  "
    					.$orderInfo[$i]["size"]."  数量:".$orderInfo[$i]["num"]."件"
    					."%%%00.............................%%%10";
	    	}
	    	$output .= "总件数：".$totalNum."件%%%00";
    		
	    	//转码
    		$output = iconv("UTF-8", "GB2312//TRANSLIT//IGNORE", $output);
    	}
    	else//如果不存在需要打印的单子
    	{
    		//为空输出
    		$blankTag = true;
    	}
    	
    	
    	
    	/*
    	 * 打印
    	*/
    	//采用单例模式，防止传输多个XML
    	$printer = printerClass::getInstance();
    	
    	if (isset($printer->params['id']) && isset($printer->params['sta']))  // 返回打印结果
    	{
    		if ($printer->params['sta'] == 0)//0为打印成功
    		{
    			$dbTmpOrder->init($printer->params['id']);
    			$dbTmpOrder->updatePrintState(7);
    		}
    	}
    	elseif (!$blankTag)// 传输需要打印的内容
    	{
    		echo $printer->setId($originAllInfo["id"]) // 设置ID
    		->setTime( strtotime(date("Y-m-d H:i:s")) ) // 设置时间
    		->setContent($output) // 设置content
    		->setSetting("103:10") // 设置打印机参数等数据，具体参考协议部分文件，建议非必要不要设置，也可以为空
    		->display(); // 输出
    		$dbTmpOrder->updatePrintState(6);
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
		 if (!isset(self::$_instance->params['usr'])
		 		&& !isset(self::$_instance->params['sgn'])
		 		&& md5(self::$_instance->params['usr']) != self::$_instance->params['sgn'])
		{
			return false;
		}
		
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