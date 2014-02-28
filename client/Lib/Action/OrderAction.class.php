<?php
require_once(LIB_PATH."commonAction.php");

class OrderAction extends myAction
{
	protected function _initialize()
	{
		 if (!$this->checkPower("canEditOrderMoney",session("userPower")))
		 	$this->error("非法访问",U("Index/index"));
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
    	/*
    	 * 如果传的是name的话  废弃
    	*
    	if ( (($this->_get("id") == "") || ($this->_get("id") == null)) ||
    			(($this->_get("name") != "") || ($this->_get("name") != null)) )//通过name传过来的值
    	{
    
    	}
    	*/
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
    	$db = D("Goods");
    	if (!isNum($this->_get("id")))
    	{
    		$this->error("商品选择不正确，请重新选择",U("Index/index"));
    	}
    	$db->init($this->_get("id"));
    	$this->assign("goodsName",$db->getGoodsName());//$db在下面用了，所以要在这里渲染
    	
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
    
    /*
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
    
    /*
     * 结算页面中删除一条商品的TmpOrder记录
     */
    public function closingDelete()
    {
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	
    	$this->isFalse($dbTmpOrder->deleteFromTmpOrder($this->_get("no") - 1),"删除的商品不在购物车内","Order/closing");
    	redirect(U("Order/closing"),0);
    }
    
    /*
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
    	$dbGoods = D("Goods");
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
    	$totalJinE = round($totalJinE);
    	
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
    
    
    /*
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
    	$dbUser = D("User");
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
    	$paymentData["save"] = $this->_post("save");
    	$paymentData["xianJinShiShou"] = $this->_post("xianJinShiShou");
    	$paymentData["yinHangShiShou"] = $this->_post("yinHangShiShou");
    	 
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
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
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
    	
    			$orderInfo[$i]["jinE"] = $orderInfo[$i]["num"] * $orderInfo[$i]["money"];//金额
    			$totalJinE += $orderInfo[$i]["jinE"];
    			$totalNum += $orderInfo[$i]["num"];
    	}
    	 
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
    	$customName = $dbTmpOrder->getTmpOrderCustomName();
    	if ( ($customName == null) || ($customName == null) )
    		$this->error("客户名称不能为空",U("Index/goBack"));
    	$dbCustomUser = D("User");
    	$dbCustomUser->init($customName);
    	$tmpRe = $dbCustomUser->getUserInfo($customName);
    	if ( ($tmpRe === false) || ($tmpRe === null) )
    		$this->error("查询用户失败，请重试",U("Index/goBack"));
    	$this->assign("customName",$customName);
    	$this->assign("tel",$tmpRe["tel"]);
    	$this->assign("address",$tmpRe["address"]);
    	$this->assign("carAddress",$tmpRe["carAddress"]);
    	$this->assign("carNo",$tmpRe["carNo"]);

    	//防止非法提交
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
    
    /*
     * 立即发货页面
     */
    public function go()
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
    	if ( $this->_get("no") != md5("GO".$tmpMD5.date("Y-m-d H")) )
    		$this->error("非法操作",U("Index/index"));
    	
    	$this->isFalse($dbTmpOrder->updatePrintState(1),"立即发货提交失败，请重试","Index/goBack");
    	$this->isFalse($dbUser->newTmpOrderID(),"立即发货提交失败，请重试","Index/goBack");
    	$this->display();
    }
    
    /*
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
    	
    	$this->isFalse($dbTmpOrder->updatePrintState(0),"延迟发货提交失败，请重试","Index/goBack");
    	$this->isFalse($dbUser->newTmpOrderID(),"延迟发货提交失败，请重试","Index/goBack");
    	$this->display();
    }
    
    /*
     * ajax获得订单打印状态
     */
    public function ajaxGetTmpOrderInfo()
    {
    	if ( ($this->_get("no") == "") || ($this->_get("no") == null) )
    	{//正常打印订单的状态查询
	    	$dbUser = D("User");
	    	$dbUser->init(session("userName"));
	    	$dbTmpOrder = D("TmpOrder");
	    	$dbTmpOrder->init($dbUser->getPreTmpOrderID());
    	}
	    else
	    {//重新打印已有订单的状态查询
	    	$dbTmpOrder = D("TmpOrder");
	    	$tmp= $dbTmpOrder->init($this->_get("no"));
	    }
    	$data = $dbTmpOrder->getTmpOrderInfo();
    	echo $data["printState"];
    }
    
    /*
     * 历史订单页面
    */
    public function history()
    {
    	$dbTmpOrder = D("TmpOrder");
    	$dbGoods = D("Goods");
    	/*
    	 * 未完成的订单信息
    	*/
    	$undone = $dbTmpOrder->where("printState<>'7' and printState<>'8'")->select();
    	for ($i = 0; $i < count($undone); $i++)
    	{
    		$tmpGoodsName = null;
    		$tmp = null;
    		$undoneList[$i]["id"] = $undone[$i]["id"];
    		$undoneList[$i]["customName"] = $undone[$i]["customName"];
    		$undoneList[$i]["createDate"] = $undone[$i]["createDate"];
    		
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
    	$done = $dbTmpOrder->where("printState='7'")->select();
    	for ($i = 0; $i < count($done); $i++)
    	{
    		$tmpGoodsName = null;
    		$tmp = null;
    		$doneList[$i]["id"] = $done[$i]["id"];
    		$doneList[$i]["customName"] = $done[$i]["customName"];
    		$doneList[$i]["createDate"] = $done[$i]["createDate"];
    		
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
    	$this->display();
    }
    
    /*
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
    	 
    			$orderInfo[$i]["jinE"] = $orderInfo[$i]["num"] * $orderInfo[$i]["money"];//金额
    					$totalJinE += $orderInfo[$i]["jinE"];
    					$totalNum += $orderInfo[$i]["num"];
    	}
    	
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
    	$customName = $dbTmpOrder->getTmpOrderCustomName();
    	$dbCustomUser = D("User");
    	$dbCustomUser->init($customName);
    	$tmpRe = $dbCustomUser->getUserInfo($customName);
    	if ( ($tmpRe === false) || ($tmpRe === null) )
    		$this->error("查询用户失败，请重试",U("Index/goBack"));
    	$this->assign("customName",$customName);
    	$this->assign("tel",$tmpRe["tel"]);
    	$this->assign("address",$tmpRe["address"]);
    	$this->assign("carAddress",$tmpRe["carAddress"]);
    	$this->assign("carNo",$tmpRe["carNo"]);
    	
    	$this->assign("id",$id);
    	$this->display();
    }
    
    /*
     * 在历史页面，删除未完成的打印单
     */
    public function deleteTmpOrder()
    {
    	$condition["id"] = $this->_get("no");
    	$dbTmpOrder = D("TmpOrder");
    	$this->isFalsePlus($dbTmpOrder->where($condition)->delete(),"删除失败，请重试","Order/history");//返回0代表影响了0个，而不是删除了0个
    	redirect(U("Order/history"),0);
    }
    
    /*
     * 重新打印3联
     */
    public function repeatAllPrint()
    {
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($this->_get("no"));
    	$this->isOk($dbTmpOrder->updatePrintState(1),"全部重新打印订单申请成功","Order/printState","重新打印请求失败，请重试","Index/goBack_2",array('no'=>$this->_get("no")));
    }
    
    /*
     * 重新打印第1联和第2联
    */
    public function repeatOnePrint()
    {
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($this->_get("no"));
    	$this->isOk($dbTmpOrder->updatePrintState(101),"重新打印存根与发票联申请成功","Order/printState","重新打印请求失败，请重试","Index/goBack_2",array('no'=>$this->_get("no")));
    }
    
    /*
     * 重新打印第3联
    */
    public function repeatThreePrint()
    {
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($this->_get("no"));
    	$this->isOk($dbTmpOrder->updatePrintState(105),"重新打印出货单申请成功","Order/printState","重新打印请求失败，请重试","Index/goBack_2",array('no'=>$this->_get("no")));
    }    
    
    public function printState()
    {
    	$this->assign("id",$this->_get("no"));
    	$this->display();
    }
}

?>