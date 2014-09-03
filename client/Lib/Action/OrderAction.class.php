<?php
require_once(LIB_PATH."commonAction.php");

class OrderAction extends myAction
{
	protected function _initialize()
	{
		 if (!$this->checkPower("canEditOrderMoney",session("userPower")))
		 	$this->error("非法访问",U("Index/index"));
		 
		 //https开启了，且当前不是https访问，则强制跳转
		 if ( (_ISHTTPS === true) && ($this->_server["HTTPS"] <> "on") )
		 	header("Location:https://".__SELF__);
	}

    public function inputPanel()//点击开始界面中的图标后进入的界面
    {
    	$dbGoods = D("Goods");
    	if (!$dbGoods->checkID($this->_get("id")))
    	{
    		$this->error("商品选择不正确，请重新选择",U("Index/index"));
    	}
    	$dbGoods->init($this->_get("id"));
    	
    	$this->assign("id",$this->_get("id"));
    	$this->assign("goodsName",$dbGoods->getGoodsName());
    	$this->assign("sizeArray",$dbGoods->getGoodsSize());
    	$this->display();
    }
    
    public function inputPanelIn()//在开始界面上弹出来的界面
    {
    	$dbGoods = D("Goods");
    	if (!$dbGoods->checkID($this->_get("id")))
    	{
    		$this->error("商品选择不正确，请重新选择",U("Index/noDisplay"));
    	}
    	$dbGoods->init($this->_get("id"));
    	
    	$this->assign("id",$this->_get("id"));
    	$this->assign("goodsName",$dbGoods->getGoodsName());
    	$this->assign("sizeArray",$dbGoods->getGoodsSize());
    	$this->display();
    }
    
    public function edit()//修改订单界面
    {
    	$dbGoods = D("Goods");
    	if (!isNum($this->_get("id")))
    	{
    		$this->error("商品选择不正确，请重新选择",U("Index/index"));
    	}
    	$dbGoods->init($this->_get("id"));
    	$this->assign("goodsName",$dbGoods->getGoodsName());//$db在下面用了，所以要在这里渲染
    	
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	$idArray = $dbTmpOrder->getArray("goodsIDArray");
    	$numArray = $dbTmpOrder->getArray("goodsNumArray");
    	$moneyArray = $dbTmpOrder->getArray("goodsMoneyArray");
    	$sizeArray = $dbTmpOrder->getArray("goodsSizeArray");
    	for ($i = 0; $i < count($idArray); $i++)
    	{
	    	if ($idArray[$i] == $this->_get("id"))
	    	{
		    	$num = $numArray[$i];
		    	$money = $moneyArray[$i];
		    	$size = $sizeArray[$i];
		    	$totalMoney = $num * $money;
		    	break;
	    	}
    	}
    	
    	$this->assign("id",$this->_get("id"));
    	$this->assign("num",$num);
    	$this->assign("money",$money);
    	$this->assign("size",$size);
    	$this->assign("totalMoney",$totalMoney);
    	$this->display();
    }
    
    public function toOneOrder()
    {
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$this->isFalse($dbTmpOrder->create(),$dbTmpOrder->getError(),"Index/goBack");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	if ($dbTmpOrder->addTmpOrder($this->_post("id"),$this->_post("num"),$this->_post("size"),$this->_post("money")))
    		redirect(U("Index/index"),0);
    	else
    		$this->error("订单提交失败，请重试",U("Order/inputPanel","id=".$this->_post('id')));
    }
    
    public function toOneOrderIn()
    {
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$this->isFalse($dbTmpOrder->create(),$dbTmpOrder->getError(),"Index/goBack");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	if ($dbTmpOrder->addTmpOrder($this->_post("id"),$this->_post("num"),$this->_post("size"),$this->_post("money")))
    		redirect(U("Index/noDisplay"),0);
    	else
    		$this->error("订单提交失败，请重试",U("Order/inputPanel","id=".$this->_post('id')));
    }
    
    /**
     * 结算页面
     */
    public function closing()
    {
    	$orderInfo = null;
    	
    	/*
    	 * 获取tmpOrder信息
    	 */
    	$dbGoods = D("Goods");
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	$tmp["id"] = $dbTmpOrder->getArray("goodsIDArray");
    	$tmp["num"] = $dbTmpOrder->getArray("goodsNumArray");
    	$tmp["money"] = $dbTmpOrder->getArray("goodsMoneyArray");
    	$tmp["size"] = $dbTmpOrder->getArray("goodsSizeArray");
    	$totalJine = 0;
    	$totalNum = 0;
    	for ($i = 0; $i < count($tmp["id"]); $i++)
    	{
    		$dbGoods->init($tmp["id"][$i]);
    		$orderInfo[$i]["goodsName"] = $dbGoods->getGoodsName();
    		$orderInfo[$i]["id"] = $tmp["id"]["$i"];
    		$orderInfo[$i]["num"] = $tmp["num"]["$i"];
    		$orderInfo[$i]["money"] = $tmp["money"]["$i"];
    		
    		//渲染规格
    		$orderInfo[$i]["goodsInfoSize"] = $dbGoods->getGoodsSize();//商品规格信息
    		$orderInfo[$i]["size"] = $tmp["size"]["$i"];//选中的规格信息
    		
    		$orderInfo[$i]["jine"] = $orderInfo[$i]["num"] * $orderInfo[$i]["money"];//金额
    		$totalJine += $orderInfo[$i]["jine"];
    		$totalNum += $orderInfo[$i]["num"];
    		
    		//md5
    		$orderInfo[$i]["tkey"] = md5(
    				"8"//printState
    				.$dbUser->getTmpOrderID()
    				.($i+1)
    				.date("Y-m-d H:i")
    		);
    	}
    	
    	//渲染list
    	if (count($orderInfo) == 0)
    	{
    		$orderInfo = null;
    	}
    	$this->assign("list",$orderInfo);
    	
    	$this->assign("totalJine",$totalJine);
    	$this->assign("totalNum",$totalNum);
    	$this->assign("totalID",count($tmp["id"]));
    	
    	//防止非法提交
    	$this->assign("myTest",
    			md5( $dbTmpOrder->getOriginArrayResult("goodsIDArray")
    				.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    				.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    				.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    				.$dbUser->getTmpOrderID()
    				.date("Y-m-d H")
    			   )
    				  );
    	
    	$this->display();
    }
    
    /**
     * 结算页面中删除一条商品的TmpOrder记录
     */
    public function closingDelete()
    {
    	/*
    	 * 验证是否是合法访问
    	*/
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	 
    	//检查是否是非法提交
    	$tmpMD5 = md5(
    			"8"//printState
    			.$dbUser->getTmpOrderID()
    			.$this->_get("no")
    			.date("Y-m-d H:i")
    	);
    	if ($this->_get("t") != $tmpMD5)
    	{
    		$this->error("非法访问",U("Index/index"));
    		return false;
    	}
    	
    	
    	
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	
    	$this->isFalse($dbTmpOrder->deleteFromTmpOrder($this->_get("no") - 1),"删除的商品不在购物车内","Order/closing");
    	redirect(U("Order/closing"),0);
    }
    
    /**
     * 结算的相关信息页面
    */
    public function closingInfo()
    {
    	/*
    	 * 从结算页面提交的订单处理
    	*/
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	
    	$tmpMD5 = md5( $dbTmpOrder->getOriginArrayResult("goodsIDArray")
    				.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    				.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    				.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    				.$dbUser->getTmpOrderID()
    				.date("Y-m-d H")
    			   );
    	$tmp = $this->_post();
    	if ($tmp["myTest"] != $tmpMD5)
    		$this->error("非法操作",U("Index/index"));
    	array_pop($tmp);//把最后一个令牌字段弹出
    	array_pop($tmp);//把倒数第二个myTest字段弹出
    	$this->isFalse($dbTmpOrder->updateTmpOrderWithGoods($tmp),$dbTmpOrder->updateTmpOrderGetError(),"Index/goBack");
    	
    	
    	/*
    	 * 本页面的显示
    	 */
    	$orderInfo = null;
    	
    	/*
    	 * 获取tmpOrder信息
    	*/
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	$tmp["num"] = $dbTmpOrder->getArray("goodsNumArray");
    	$tmp["money"] = $dbTmpOrder->getArray("goodsMoneyArray");
    	$totalJinE = 0;
    	for ($i = 0; $i < count($tmp["num"]); $i++)
    	{
    		$orderInfo[$i]["num"] = $tmp["num"]["$i"];
    		$orderInfo[$i]["money"] = $tmp["money"]["$i"];
    		$orderInfo[$i]["jinE"] = $orderInfo[$i]["num"] * $orderInfo[$i]["money"];//金额
    		$totalJinE += $orderInfo[$i]["jinE"];
    	}
    	$totalJinE = round($totalJinE,2);
    	
    	/*
    	 * 获取user信息
    	 */
    	$this->assign("originJinE",$totalJinE);
    	$this->assign("userName",$dbUser->getAllUserInfo());
    	
    	//防止非法提交
    	$this->assign("myTest",
    			md5( $dbTmpOrder->getOriginArrayResult("goodsIDArray")
    					.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    					.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    					.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    					.$dbUser->getTmpOrderID()
    					.date("Y-m-d H")
    			)
    	);
    	
    	$this->display();
    }
    
    
    /**
     * 结算信息总览页面
     * note		和historyOver对应
     */
    public function closingOver()
    {
    	/*
    	 * 结算页面（前一个页面）的最终数据提交
    	*/
	    	/*
	    	 * //处理用户信息
	    	*/
    	//验证是否非法提交
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	$tmpMD5 = md5( $dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.$dbUser->getTmpOrderID()
    			.date("Y-m-d H")
    	);
    	if ($this->_post("myTest") != $tmpMD5)
    		$this->error("非法操作",U("Index/index"));
    	
    	//得到用户信息数据
    	$userData["userName"] = $this->_post("userName");
    	$userData["tel"] = $this->_post("tel");
    	$userData["address"] = $this->_post("address");
    	$userData["carAddress"] = $this->_post("carAddress");
    	$userData["carNo"] = $this->_post("carNo");
    	 
    	//处理用户信息
    	$tmp = $dbUser->getUserInfo($userData["userName"]);
    	if ($tmp === false)
    		$this->error("数据库查询失败，请重试",U("Index/goBack"));
    	if ($tmp === null)//用户不存在，创建新用户
    	{
    		$this->isFalse($dbUser->sign($userData),$dbUser->getErrorMsg(),"Index/goBack");
    	}
    	else//用户存在，更新用户信息
    	{
    		$this->isFalsePlus($dbUser->updateInfo($userData),$dbUser->getErrorMsg(),"Index/goBack");
    	}

    	/*
    	 * 处理付款信息
    	*/
    	//得到付款信息数据
    	$paymentData["customName"] = $this->_post("userName");
    	$paymentData["save"] = round($this->_post("save"),2);
    	$paymentData["xianJinShiShou"] = round($this->_post("xianJinShiShou"),2);
    	$paymentData["yinHangShiShou"] = round($this->_post("yinHangShiShou"),2);
    	 
    	//更新TmpOrder中的付款信息
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	$this->isFalsePlus($dbTmpOrder->updateTmpOrderWithPayment($paymentData),$dbTmpOrder->updateTmpOrderGetError(),"Index/goBack");
    	 
    	
    	
    	
    	
    	/*
    	 * 本页面的显示
    	 */
    	$orderInfo = null;
    	 
    	$dbGoods = D("Goods");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	
    	/*
    	 * 货物信息
    	*/
    	$tmp["id"] = $dbTmpOrder->getArray("goodsIDArray");
    	$tmp["num"] = $dbTmpOrder->getArray("goodsNumArray");
    	$tmp["money"] = $dbTmpOrder->getArray("goodsMoneyArray");
    	$tmp["size"] = $dbTmpOrder->getArray("goodsSizeArray");
    	$totalJinE = 0;
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
    	
    			$orderInfo[$i]["jinE"] = round($orderInfo[$i]["num"] * $orderInfo[$i]["money"],2);//金额
    			$totalJinE += $orderInfo[$i]["jinE"];
    			$totalNum += $orderInfo[$i]["num"];
    	}
    	$totalJinE = round($totalJinE,2);
    	//渲染list
    	if (count($orderInfo) == 0)
    	{
    		$orderInfo = null;
    	}
    	$this->assign("list",$orderInfo);
    	$this->assign("totalJinE",$totalJinE);
    	
    	/*
    	 * 付款信息
    	 */
    	$tmpOrderInfo = $dbTmpOrder->getTmpOrderInfo();
    	$yingShouJinE = round($totalJinE - $tmpOrderInfo["save"],2);
    	$benCiShiShou = round($tmpOrderInfo["xianJinShiShou"] + $tmpOrderInfo["yinHangShiShou"],2);
    	$this->assign("save",$tmpOrderInfo["save"]);
    	$this->assign("xianJinShiShou",$tmpOrderInfo["xianJinShiShou"]);
    	$this->assign("yinHangShiShou",$tmpOrderInfo["yinHangShiShou"]);
    	$this->assign("yingShouJinE",$yingShouJinE);
    	$this->assign("benCiShiShou",$benCiShiShou);
    	$benCiQianFuKuan = round($benCiShiShou - $yingShouJinE,2);
    	if ($benCiQianFuKuan == 0)
    		$benCiQianFuKuanInfo = "<strong class='text-info'>无  </strong>";
    	else if ($benCiQianFuKuan > 0)
    		$benCiQianFuKuanInfo = "<strong class='text-success'>多  ".$benCiQianFuKuan."</strong>";
    	else if ($benCiQianFuKuan < 0)
    		$benCiQianFuKuanInfo = "<strong class='text-warning'>少  ".(0 - $benCiQianFuKuan)."</strong>";
    	$this->assign("benCiQianFuKuan",$benCiQianFuKuanInfo);
    	
    	/*
    	 * 用户信息
    	 */
    	$customName = $dbTmpOrder->getTmpOrderCustomName();
    	if ( ($customName == null) || ($customName == null) )
    		$this->error("客户名称不能为空",U("Index/goBack"));
    	$dbUser->init($customName);
    	$tmpRe = $dbUser->getUserInfo($customName);
    	if ( ($tmpRe === false) || ($tmpRe === null) )
    		$this->error("查询用户失败，请重试",U("Index/goBack"));
    	$this->assign("customName",$customName);
    	$this->assign("tel",$tmpRe["tel"]);
    	$this->assign("address",$tmpRe["address"]);
    	$this->assign("carAddress",$tmpRe["carAddress"]);
    	$this->assign("carNo",$tmpRe["carNo"]);

    	//防止非法提交
    	$dbUser->init(session("userName"));//上面的$dbCustomUser更改了目标
    	$tmpMD5 = null;
    	$tmpMD5 = md5(
    			$tmpOrderInfo["xianJinShiShou"]
    			.$tmpOrderInfo["yinHangShiShou"]
    			.$tmpOrderInfo["save"]
    			."8"//printState
    			.$customName
    			.$dbUser->getTmpOrderID()
    			.$dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.date("Y-m-d H")
    			);
    	$this->assign("delayGoMD5",md5("DelayGo".$tmpMD5.date("Y-m-d H")));
    	$this->assign("goMD5",md5("GO".$tmpMD5.date("Y-m-d H")));
    	
    	$this->display();
    }
    
    /**
     * 立即发货页面
     */
    public function go()
    {
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$tmpOrderID = $dbUser->getTmpOrderID();
    	$dbTmpOrder->init($tmpOrderID);
    	
    	//检查是否是非法提交
    	$tmpData = $dbTmpOrder->getTmpOrderInfo();
    	$tmpMD5 = md5(
    			$tmpData["xianJinShiShou"]
    			.$tmpData["yinHangShiShou"]
    			.$tmpData["save"]
    			."8"//printState
    			.$tmpData["customName"]
    			.$dbUser->getTmpOrderID()
    			.$dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.date("Y-m-d H")
    	);
    	if ( $this->_get("no") != md5("GO".$tmpMD5.date("Y-m-d H")) )
    		$this->error("非法操作",U("Index/index"));
    	
    	$dbTmpOrder->startTrans();
    	$dbUser->startTrans();
    	if ( $dbTmpOrder->updatePrintState(1) && $dbUser->newTmpOrderID() )
    	{
    		$dbTmpOrder->commit();
    		$dbUser->commit();
    	}
    	else
    	{
    		$dbTmpOrder->rollback();
    		$dbUser->rollback();
    		$this->error("立即发货提交失败，请重试",U("Index/goBack"));
    	}
//     	$this->isFalse($dbTmpOrder->updatePrintState(1),"立即发货提交失败，请重试","Index/goBack");
//     	$this->isFalse($dbUser->newTmpOrderID(),"立即发货提交失败，请重试","Index/goBack");
    	
    	$tmpMD5 = null;
    	$tmpMD5 = md5(
    			$tmpData["xianJinShiShou"]
    			.$tmpData["yinHangShiShou"]
    			.$tmpData["save"]
    			."100"//printState
    			.$tmpData["customName"]
    			.$tmpOrderID
    			.$dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.date("Y-m-d H")
    	);
    	$this->assign("key",$tmpMD5);
    	
    	$this->display();
    }
    
    /**
     * 延迟发送页面
     */
    public function delayGo()
    {
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	
    	//检查是否是非法提交
    	$tmpData = $dbTmpOrder->getTmpOrderInfo();
    	$tmpMD5 = md5(
    			$tmpData["xianJinShiShou"]
    			.$tmpData["yinHangShiShou"]
    			.$tmpData["save"]
    			."8"//printState
    			.$tmpData["customName"]
    			.$dbUser->getTmpOrderID()
    			.$dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.date("Y-m-d H")
    	);
    	if ( $this->_get("no") != md5("DelayGo".$tmpMD5.date("Y-m-d H")) )
    		$this->error("非法操作",U("Index/index"));
    	
    	$dbTmpOrder->startTrans();
    	$dbUser->startTrans();
    	if ( $dbTmpOrder->updatePrintState(0) && $dbUser->newTmpOrderID() )
    	{
    		$dbTmpOrder->commit();
    		$dbUser->commit();
    	}
    	else
    	{
    		$dbTmpOrder->rollback();
    		$dbUser->rollback();
    		$this->error("延迟发货提交失败，请重试",U("Index/goBack"));
    	}
//     	$this->isFalse($dbTmpOrder->updatePrintState(0),"延迟发货提交失败，请重试","Index/goBack");
//     	$this->isFalse($dbUser->newTmpOrderID(),"延迟发货提交失败，请重试","Index/goBack");
    	
    	$this->display();
    }
    
    
    /**
     * ajax获得订单打印状态
    */
    public function ajaxGetTmpOrderInfo()
    {
    	/*
    	 * 验证是否是合法访问
    	 */
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	if ( ($this->_get("id") == "") || ($this->_get("id") == null) )
    		$tmpOrderID = $dbUser->getPreTmpOrderID();
    	else
    		$tmpOrderID = $this->_get("id");
    	$dbTmpOrder->init($tmpOrderID);
    	
    	//检查是否是非法提交
    	$tmpData = $dbTmpOrder->getTmpOrderInfo();
    	$tmpMD5 = md5(
    			$tmpData["xianJinShiShou"]
    			.$tmpData["yinHangShiShou"]
    			.$tmpData["save"]
    			."100"//printState
    			.$tmpData["customName"]
    			.$tmpOrderID
    			.$dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.date("Y-m-d H")
    	);
    	if ($this->_get("no") != $tmpMD5)
    	{
    		$this->error("非法访问",U("Index/index"));
    		return false;
    	}
    	
    	
    	if ( ($this->_get("id") == "") || ($this->_get("id") == null) )
    	{//正常打印订单(go页面)的状态查询
    		$dbUser = D("User");
    		$dbUser->init(session("userName"));
    		$dbTmpOrder = D("TmpOrder");
    		$dbTmpOrder->init($dbUser->getPreTmpOrderID());
    	}
    	else
    	{//重新打印已有订单（printState页面）的状态查询
    		$dbTmpOrder = D("TmpOrder");
    		$tmp= $dbTmpOrder->init($this->_get("id"));
    	}
    	$data = $dbTmpOrder->getTmpOrderInfo();
    	echo $data["printState"];
    }
    
    /**
     * 历史订单页面
     * @param	int $mode;模式，
     * 				0：正常历史记录页面（显示当天记录）；
     * 				1：按创建时间查询的区间查询显示页面
     * 				2：按打印时间查询的区间查询显示页面
     * 				3：按创建时间查询的多点查询显示页面
     * 				4：按打印时间查询的多点查询显示页面
     * 			array || string $startDate;日期数组||开始日期
     * 				对于mode为1、2时：
     * 					string;日期，如2014-12-3
     * 				对于mode为3、4时：
     * 					array;日期数组
     * 						array[i];日期，如2014-12-3（已处理过）
     * 			string $endDate;结束日期,如2014-12-3
     * 			bool $isNew;是不是新页面，默认为1.为0代表是从其他页面翻页过来的
    */
    public function history($mode = 0,$startDate = null,$endDate = null,$isNew = 1)
    {
    	import('ORG.Util.Page_co8bit');// 导入分页类
    	$page = null;
    	$recordNum = 20;//每页显示的记录数
    	$rollPage0 = 20;//页面上有多少个分页栏，在模式0下
    	$rollPageNEQ0 = 15;//页面上有多少个分页栏，在模式不为0下
    	
    	$dbTmpOrder = D("TmpOrder");
    	$dbGoods = D("Goods");
    	/*
    	 * 从数据库中选择
    	 */
    	$date1 = $startDate[0]."-".$startDate[1]."-".$startDate[2];
    	$date2 = $endDate[0]."-".$endDate[1]."-".$endDate[2];
    	if ($mode === 1)
    	{
    		$tmpOrderCount = $dbTmpOrder->where("printState='7' and createDate>='".$date1." 00:00:00' and createDate<='".$date2." 23:59:59'")->count();
    		$page = new Page($tmpOrderCount,$recordNum,$rollPageNEQ0);// 实例化分页类 传入总记录数和每页显示的记录数
    		
    		$undone = $dbTmpOrder->where("printState<>'7' and printState<>'8' 
    					and createDate>='".$date1." 00:00:00' and createDate<='".$date2." 23:59:59'")->order('createDate')->select();
    		$done = $dbTmpOrder->where("printState='7' and createDate>='".$date1." 00:00:00' and createDate<='".$date2." 23:59:59'")
    				->order('createDate')->limit($page->firstRow.','.$page->listRows)->select();
    	}
    	elseif ($mode === 2)
    	{
    		$tmpOrderCount = $dbTmpOrder->where("printState='7' and printDate>='".$date1." 00:00:00' and printDate<='".$date2." 23:59:59'")->count();
    		$page = new Page($tmpOrderCount,$recordNum,$rollPageNEQ0);// 实例化分页类 传入总记录数和每页显示的记录数
    		
    		$undone = $dbTmpOrder->where("printState<>'7' and printState<>'8'
    					and printDate>='".$date1." 00:00:00' and printDate<='".$date2." 23:59:59'")->order('printDate')->select();
    		$done = $dbTmpOrder->where("printState='7' and printDate>='".$date1." 00:00:00' and printDate<='".$date2." 23:59:59'")
    				->order('printDate')->limit($page->firstRow.','.$page->listRows)->select();
    	}
    	elseif ($mode === 3)
    	{
    		$str = "";
    		$str .= "(createDate>='".$startDate[0]." 00:00:00' and createDate<='".$startDate[0]." 23:59:59')";
    		for ($i = 1; $i < count($startDate); $i++)
    		{
    			$str .= " or (createDate>='".$startDate[$i]." 00:00:00' and createDate<='".$startDate[$i]." 23:59:59')";
    		}
    		
    		$tmpOrderCount = $dbTmpOrder->where("printState='7' and (".$str.")")->count();
    		$page = new Page($tmpOrderCount,$recordNum,$rollPageNEQ0);// 实例化分页类 传入总记录数和每页显示的记录数
    		
    		$undone = $dbTmpOrder->where("printState<>'7' and printState<>'8' and (".$str.")")->order('createDate')->select();
    		$done = $dbTmpOrder->where("printState='7' and (".$str.")")
    				->order('createDate')->limit($page->firstRow.','.$page->listRows)->select();
    		
    		if ($startDate === null)
    		{
    			$undone = null;
    			$done = null;
    		}
    	}
    	elseif ($mode === 4)
    	{
    		$str = "";
    		$str .= "(printDate>='".$startDate[0]." 00:00:00' and printDate<='".$startDate[0]." 23:59:59')";
    		for ($i = 1; $i < count($startDate); $i++)
    		{
    			$str .= " or (printDate>='".$startDate[$i]." 00:00:00' and printDate<='".$startDate[$i]." 23:59:59')";
    		}
    		
    		$tmpOrderCount = $dbTmpOrder->where("printState='7' and (".$str.")")->count();
    		$page = new Page($tmpOrderCount,$recordNum,$rollPageNEQ0);// 实例化分页类 传入总记录数和每页显示的记录数
    		
    		$undone = $dbTmpOrder->where("printState<>'7' and printState<>'8' and (".$str.")")->order('printDate')->select();
    		$done = $dbTmpOrder->where("printState='7' and (".$str.")")
    				->order('printDate')->limit($page->firstRow.','.$page->listRows)->select();
    		
    		if ($startDate === null)
    		{
    			$undone = null;
    			$done = null;
    		}
    	}
    	else
    	{
    		$tmpOrderCount = $dbTmpOrder->where("printState='7' 
    				and ((createDate>='".date("Y-m-d")." 00:00:00' and createDate<='".date("Y-m-d")." 23:59:59') 
    				or (printDate>='".date("Y-m-d")." 00:00:00' and printDate<='".date("Y-m-d")." 23:59:59'))")->count();
    		$page = new Page($tmpOrderCount,$recordNum,$rollPage0);// 实例化分页类 传入总记录数和每页显示的记录数
    		
    		$undone = $dbTmpOrder->where("printState<>'7' and printState<>'8'")->select();
    		$done = $dbTmpOrder->where("printState='7' 
    				and (printDate>='".date("Y-m-d")." 00:00:00' and printDate<='".date("Y-m-d")." 23:59:59')")
    				->order('createDate')->limit($page->firstRow.','.$page->listRows)->select();
    	}
    	//分页
    	if ($mode !== 0)//高级查询页面的分页
    	{
    		$page->setConfig("currentLabel","li class='active'");
    		$page->setConfig('otherLabel',"li onclick='changePage(this);'");
    		$page->setConfig("hasHref",false);
    	}
    	else//普通查询页面的分页
    	{
    		$page->setConfig("hasHref",true);
    		$page->setConfig("currentLabel","li class='active'");
    		$page->setConfig('otherLabel','li');
    	}
    	$pageShow = $page->show();//输出分页信息
    	$this->assign($pageShow);
    	$this->assign("offset",($pageShow["nowPage"] - 1) * $recordNum);//第多少页的偏移量
    	
    	if (!$undone)
    		$undone = null;
    	if (!$done)
    		$done = null;

    	/*
    	 * 未完成的订单信息
    	*/
    	for ($i = 0; $i < count($undone); $i++)
    	{
    		$tmpGoodsName = null;
    		$tmp = null;
    		$undoneList[$i]["id"] = $undone[$i]["id"];
    		$undoneList[$i]["customName"] = $undone[$i]["customName"];
    		$undoneList[$i]["createDate"] = $undone[$i]["createDate"];
    		$undoneList[$i]["printDate"] = $undone[$i]["printDate"];
    		
    		//打印状态
    		switch ($undone[$i]["printState"])
    		{
    			case 0:$undoneList[$i]["printState"] = "目前没有打印该订单";break;
				case 1:$undoneList[$i]["printState"] = "准备打印存根与发票联";break;
				case "2":$undoneList[$i]["printState"] = "存根与发票联已经下发给打印机";break;
				case "3":$undoneList[$i]["printState"] = "打印存根成功，准备打印发票联";break;
				case "4":$undoneList[$i]["printState"] = "发票联已经下发给打印机";break;
				case "5":$undoneList[$i]["printState"] = "打印存根与发票联成功，准备打印出库单";break;
				case "6":$undoneList[$i]["printState"] = "出货单已经下发给打印机";break;
				case "7":$undoneList[$i]["printState"] = "全部打印完成";break;
				case '101':$undoneList[$i]["printState"] = "重新打印存根与发票联";break;
				case '105':$undoneList[$i]["printState"] = "重新打印出货单";break;
				default:$undoneList[$i]["printState"] = "数据库出错，请重试";break;
    		}
    		
    		//md5
    		$undoneList[$i]["tkey"] = md5(
    				$undone[$i]["xianJinShiShou"]
    				.$undone[$i]["yinHangShiShou"]
    				.$undone[$i]["save"]
    				."100"//printState
    				.$undone[$i]["customName"]
    				.$undone[$i]["id"]
    				.$undone[$i]["goodsIDArray"]
    				.$undone[$i]["goodsNumArray"]
    				.$undone[$i]["goodsMoneyArray"]
    				.$undone[$i]["goodsSizeArray"]
    				.date("Y-m-d H:i")
    		);
    	
    	    //得到商品名称
    	   	$tmp = $dbTmpOrder->getArrayWithSelf($undone[$i]["goodsIDArray"]);
    		for ($j = 0; $j < count($tmp); $j++)
    	    {
    	   		$dbGoods->init($tmp[$j]);
    	    	$tmpGoodsName[$j] = $dbGoods->getGoodsName();
    	    }
    	    $undoneList[$i]["goodsName"] = $dbTmpOrder->transformWithSlef($tmpGoodsName,"；");
    	}
    	
    	/*
    	 * 已完成的订单信息
    	 */
    	for ($i = 0; $i < count($done); $i++)
    	{
    		$tmpGoodsName = null;
    		$tmp = null;
    		$doneList[$i]["id"] = $done[$i]["id"];
    		$doneList[$i]["customName"] = $done[$i]["customName"];
    		$doneList[$i]["createDate"] = $done[$i]["createDate"];
    		$doneList[$i]["printDate"] = $done[$i]["printDate"];
    		
    		//得到商品名称
    		$tmp = $dbTmpOrder->getArrayWithSelf($done[$i]["goodsIDArray"]);
    		for ($j = 0; $j < count($tmp); $j++)
    		{
    			$dbGoods->init($tmp[$j]);
    			$tmpGoodsName[$j] = $dbGoods->getGoodsName();
    		}
    		$doneList[$i]["goodsName"] = $dbTmpOrder->transformWithSlef($tmpGoodsName,",");
    	}
    	$this->assign("undoneList",$undoneList);
    	$this->assign("doneList",$doneList);
    	
    	$this->assign("mode",$mode);
    	$this->display("Order:history");
    }
    
    
    /**
     * 在历史界面的查看某一条单子的详细信息的界面
     * note		和closingOver对应
     */
    public function historyOver()
    {
    	$orderInfo = null;
    	
    	$dbGoods = D("Goods");
    	$dbTmpOrder = D("TmpOrder");
    	$id = $this->_get("no");
    	$dbTmpOrder->init($id);
    	 
    	/*
    	 * 货物信息
    	*/
    	$tmp["id"] = $dbTmpOrder->getArray("goodsIDArray");
    	$tmp["num"] = $dbTmpOrder->getArray("goodsNumArray");
    	$tmp["money"] = $dbTmpOrder->getArray("goodsMoneyArray");
    	$tmp["size"] = $dbTmpOrder->getArray("goodsSizeArray");
    	$totalJinE = 0;
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
    	 
    			$orderInfo[$i]["jinE"] = round($orderInfo[$i]["num"] * $orderInfo[$i]["money"],2);//金额
    					$totalJinE += $orderInfo[$i]["jinE"];
    					$totalNum += $orderInfo[$i]["num"];
    	}
    	
    	//渲染list
    			if (count($orderInfo) == 0)
    			{
    			$orderInfo = null;
    	}
    	$this->assign("list",$orderInfo);
    	$this->assign("totalJinE",round($totalJinE,2));
    	 
    	/*
    	* 付款信息
    	*/
    	$tmpOrderInfo = $dbTmpOrder->getTmpOrderInfo();
    	$yingShouJinE = $totalJinE - $tmpOrderInfo["save"];
    	$benCiShiShou = $tmpOrderInfo["xianJinShiShou"] + $tmpOrderInfo["yinHangShiShou"];
    	$this->assign("save",$tmpOrderInfo["save"]);
    	$this->assign("xianJinShiShou",$tmpOrderInfo["xianJinShiShou"]);
    	$this->assign("yinHangShiShou",$tmpOrderInfo["yinHangShiShou"]);
    	$this->assign("yingShouJinE",$yingShouJinE);
    	$this->assign("benCiShiShou",$benCiShiShou);
    	$benCiQianFuKuan = $benCiShiShou - $yingShouJinE;
    	if ($benCiQianFuKuan == 0)
    		$benCiQianFuKuanInfo = "<strong class='text-info'>无  </strong>";
    	else if ($benCiQianFuKuan > 0)
    		$benCiQianFuKuanInfo = "<strong class='text-success'>多  ".$benCiQianFuKuan."</strong>";
    	else if ($benCiQianFuKuan < 0)
    		$benCiQianFuKuanInfo = "<strong class='text-warning'>少  ".(0 - $benCiQianFuKuan)."</strong>";
    	$this->assign("benCiQianFuKuan",$benCiQianFuKuanInfo);
    						 
    	/*
    	* 用户信息
    	*/
    	$dbUser = D("User");
    	$customName = $dbTmpOrder->getTmpOrderCustomName();
    	$dbUser->init($customName);
    	$tmpRe = $dbUser->getUserInfo($customName);
    	if ( ($tmpRe === false) || ($tmpRe === null) )
    		$this->error("查询用户失败，请重试",U("Index/goBack"));
    	$this->assign("customName",$customName);
    	$this->assign("tel",$tmpRe["tel"]);
    	$this->assign("address",$tmpRe["address"]);
    	$this->assign("carAddress",$tmpRe["carAddress"]);
    	$this->assign("carNo",$tmpRe["carNo"]);
    	
    	$this->assign("id",$id);
    	
    	$tmpMD5 = null;
    	$tmpMD5 = md5(
    			$tmpOrderInfo["xianJinShiShou"]
    			.$tmpOrderInfo["yinHangShiShou"]
    			.$tmpOrderInfo["save"]
    			."?"//printState
    			.$customName
    			.$id
    			.$dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.date("Y-m-d H:i")
    	);
    	$this->assign("tkey",$tmpMD5);
    	
    	$this->assign("id",$id);
    	$this->display();
    }
    
    /**
     * 在历史页面，删除未完成的打印单
     */
    public function deleteTmpOrder()
    {
    	/*
    	 * 验证是否是合法访问
    	*/
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$tmpOrderID = $this->_get("no");
    	$dbTmpOrder->init($tmpOrderID);
    	
    	//检查是否是非法提交
    	$tmpData = $dbTmpOrder->getTmpOrderInfo();
    	$tmpMD5 = md5(
    			$tmpData["xianJinShiShou"]
    			.$tmpData["yinHangShiShou"]
    			.$tmpData["save"]
    			."100"//printState
    			.$tmpData["customName"]
    			.$tmpOrderID
    			.$dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.date("Y-m-d H:i")
    	);
    	if ($this->_get("t") != $tmpMD5)
    	{
    		$this->error("非法访问",U("Index/index"));
    		return false;
    	}
    	
    	
    	$condition["id"] = $this->_get("no");
    	$dbTmpOrder = D("TmpOrder");
    	$this->isFalsePlus($dbTmpOrder->where($condition)->delete(),"删除失败，请重试","Order/history");//返回0代表影响了0个，而不是删除了0个
    	redirect(U("Order/history"),0);
    }
    
    
    /**
     * 重新打印3联
     */
    public function repeatAllPrint()
    {
    	/*
    	 * 验证是否是合法访问
    	*/
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
   		$tmpOrderID = $this->_get("no");
    	$dbTmpOrder->init($tmpOrderID);
    	 
    	//检查是否是非法提交
    	$tmpData = $dbTmpOrder->getTmpOrderInfo();
    	$tmpMD5 = md5(
    			$tmpData["xianJinShiShou"]
    			.$tmpData["yinHangShiShou"]
    			.$tmpData["save"]
    			."?"//printState
    			.$tmpData["customName"]
    			.$tmpOrderID
    			.$dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.date("Y-m-d H:i")
    	);
    	if ($this->_get("t") != $tmpMD5)
    	{
    		$this->error("非法访问",U("Index/index"));
    		return false;
    	}
    	
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($this->_get("no"));
    	$this->isOk($dbTmpOrder->updatePrintState(1),"全部重新打印订单申请成功","Order/printState","重新打印请求失败，请重试","Index/goBack_2",array('no'=>$this->_get("no")));
    }
    
    /**
     * 重新打印第1联和第2联
    */
    public function repeatOnePrint()
    {
    	/*
    	 * 验证是否是合法访问
    	*/
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$tmpOrderID = $this->_get("no");
    	$dbTmpOrder->init($tmpOrderID);
    	
    	//检查是否是非法提交
    	$tmpData = $dbTmpOrder->getTmpOrderInfo();
    	$tmpMD5 = md5(
    			$tmpData["xianJinShiShou"]
    			.$tmpData["yinHangShiShou"]
    			.$tmpData["save"]
    			."?"//printState
    			.$tmpData["customName"]
    			.$tmpOrderID
    			.$dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.date("Y-m-d H:i")
    	);
    	if ($this->_get("t") != $tmpMD5)
    	{
    		$this->error("非法访问",U("Index/index"));
    		return false;
    	}
    	
    	
    	
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($this->_get("no"));
    	$this->isOk($dbTmpOrder->updatePrintState(101),"重新打印存根与发票联申请成功","Order/printState","重新打印请求失败，请重试","Index/goBack_2",array('no'=>$this->_get("no")));
    }
    
    /**
     * 重新打印第3联
    */
    public function repeatThreePrint()
    {
    	/*
    	 * 验证是否是合法访问
    	*/
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$tmpOrderID = $this->_get("no");
    	$dbTmpOrder->init($tmpOrderID);
    	
    	//检查是否是非法提交
    	$tmpData = $dbTmpOrder->getTmpOrderInfo();
    	$tmpMD5 = md5(
    			$tmpData["xianJinShiShou"]
    			.$tmpData["yinHangShiShou"]
    			.$tmpData["save"]
    			."?"//printState
    			.$tmpData["customName"]
    			.$tmpOrderID
    			.$dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.date("Y-m-d H:i")
    	);
    	if ($this->_get("t") != $tmpMD5)
    	{
    		$this->error("非法访问",U("Index/index"));
    		return false;
    	}
    	
    	
    	
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($this->_get("no"));
    	$this->isOk($dbTmpOrder->updatePrintState(105),"重新打印出货单申请成功","Order/printState","重新打印请求失败，请重试","Index/goBack_2",array('no'=>$this->_get("no")));
    }    
    
    /**
     * 重新打印的时候显示打印状态的页面
     */
    public function printState()
    {
    	/*
    	 * 添加md5
    	*/
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($this->_get("no"));
    	$tmpData = $dbTmpOrder->getTmpOrderInfo();
    	$tmpMD5 = md5(
    			$tmpData["xianJinShiShou"]
    			.$tmpData["yinHangShiShou"]
    			.$tmpData["save"]
    			."100"//printState
    			.$tmpData["customName"]
    			.$this->_get("no")
    			.$dbTmpOrder->getOriginArrayResult("goodsIDArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsNumArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsMoneyArray")
    			.$dbTmpOrder->getOriginArrayResult("goodsSizeArray")
    			.date("Y-m-d H")
    	);
    	
    	$this->assign("id",$this->_get("no"));
    	$this->assign("key",$tmpMD5);
    	$this->display();
    }
    
    public function advancedQuery()
    {
    	$this->display();
    }
    
    public function ajaxAdvancedQueryInterval()
    {
    	if (!isset($_POST["mode"]))//翻页进来
    	{
    		$isNew = 0;
	    }
	    else
	    	$isNew = 1;
	    
	    $mode = $this->_param("mode");
	    sscanf($this->_param("startDate"),"%d年%d月%d日,星期%s",$startDate[0],$startDate[1],$startDate[2],$startDate[3]);
	    sscanf($this->_param("endDate"),"%d年%d月%d日,星期%s",$endDate[0],$endDate[1],$endDate[2],$endDate[3]);
    	if ( (!checkIsDate($startDate)) || (!checkIsDate($endDate)) )
    	{
    		$this->error("日期输入有问题，请重试","Index/goBack");
    	}
    	
    	if ($mode == 1)
    	{
    		$this->history(1,$startDate,$endDate,$isNew);
    	}
    	elseif ($mode == 2)
    	{
    		$this->history(2,$startDate,$endDate,$isNew);
    	}
    	else
    		$this->error("非法操作");
    }
    
    public function ajaxAdvancedQueryMult()
    {
    	if (!isset($_POST["mode"]))//翻页进来
    	{
    		$isNew = 0;
    	}
    	else
    		$isNew = 1;
    	
    	$mode = $this->_param("mode");
    	$dateArray = explode(",",$this->_param("date"));
    	array_pop($dateArray);//弹掉最后一个空的项（因为输入末尾带一个多余的逗号）
    	if (!checkDateArray($dateArray))
    	{
    		$this->error("日期输入有问题，请重试","Index/goBack");
    	}
    	 
    	//把dateArray变成一个数组
    	$newDateArray = null;
    	for ($i = 0; $i < count($dateArray); $i++)
    	{
    		$tmp = null;
    		sscanf($dateArray[$i],"%d-%d-%d",$tmp[0],$tmp[1],$tmp[2]);
    		$newDateArray[$i] = $tmp[0]."-".$tmp[1]."-".$tmp[2];
    	}
    	 
    	if ($mode == 1)
    	{
    		$this->history(3,$newDateArray,$isNew);
    	}
    	elseif ($mode == 2)
    	{
    		$this->history(4,$newDateArray,$isNew);
    	}
    	else
    		$this->error("非法操作");
    }
}

?>