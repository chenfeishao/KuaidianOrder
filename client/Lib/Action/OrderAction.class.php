<?php
include(LIB_PATH."commonAction.php");

class OrderAction extends myAction
{

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
    	for ($i = 0; $i < count($tmp["id"]); $i++)
    	{
    		$dbGoods->init($tmp["id"][$i]);
    		$orderInfo[$i]["goodsName"] = $dbGoods->getGoodsName();
    		$orderInfo[$i]["id"] = $tmp["id"]["$i"];
    		$orderInfo[$i]["num"] = $tmp["num"]["$i"];
    		$orderInfo[$i]["money"] = $tmp["money"]["$i"];
    		
    		//渲染规格
    		$this->assign("goodsInfoSize".$i,$dbGoods->getGoodsSize());//渲染下拉列表
//     		$orderInfo[$i]["size"] = $tmp["size"]["$i"];
    		
    		$orderInfo[$i]["jine"] = $orderInfo[$i]["num"] * $orderInfo[$i]["money"];//金额
    	}
    	
    	//渲染list
    	if (count($orderInfo) == 0)
    	{
    		$orderInfo = null;
    	}
    	$this->assign("list",$orderInfo);
    	
    	$this->display();
    }
}

?>