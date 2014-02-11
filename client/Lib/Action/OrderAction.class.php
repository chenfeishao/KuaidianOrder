<?php
include(LIB_PATH."commonAction.php");

class OrderAction extends myAction
{

    public function inputPanel()//点击开始界面中的图标后进入的界面
    {
    	$db = D("Goods");
    	if (!isNum($this->_get("id")))
    	{
    		$this->error("商品选择不正确，请重新选择",U("Index/index"));
    	}
    	$db->init($this->_get("id"));
    	
    	$this->assign("id",$this->_get("id"));
    	$this->assign("goodsName",$db->getGoodsName());
    	$this->display();
    }
    
    public function inputPanelIn()//在开始界面上弹出来的界面
    {
    	$db = D("Goods");
    	/*
    	 * 如果传的是name的话  废弃
    	*
    	if ( (($this->_get("id") == "") || ($this->_get("id") == null)) ||
    			(($this->_get("name") != "") || ($this->_get("name") != null)) )//通过name传过来的值
    	{
    
    	}
    	*/
    	if (!isNum($this->_get("id")))
    	{
    		$this->error("商品选择不正确，请重新选择",U("Index/noDisplay"));
    	}
    	$db->init($this->_get("id"));
    	 
    	$this->assign("id",$this->_get("id"));
    	$this->assign("goodsName",$db->getGoodsName());
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
    	$db = D("TmpOrder");
    	$db->init($dbUser->getTmpOrderID());
    	$idArray = $db->getIDArray();
    	$numArray = $db->getNumArray();
    	$moneyArray = $db->getMoneyArray();
    	$sizeArray = $db->getSizeArray();
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
    	$db = D("TmpOrder");
    	$this->isFalse($db->create(),$db->getError(),"Index/goBack");
    	$db->init($dbUser->getTmpOrderID());
    	if ($db->insert($this->_post("id"),$this->_post("num"),$this->_post("size"),$this->_post("money")))
    		redirect(U("Index/index"),0);
    	else
    		$this->error("订单提交失败，请重试",U("Order/inputPanel","id=".$this->_post('id')));
    }
    
    public function toOneOrderIn()
    {
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$db = D("TmpOrder");
    	$this->isFalse($db->create(),$db->getError(),"Index/goBack");
    	$db->init($dbUser->getTmpOrderID());
    	if ($db->insert($this->_post("id"),$this->_post("num"),$this->_post("size"),$this->_post("money")))
    		redirect(U("Index/noDisplay"),0);
    	else
    		$this->error("订单提交失败，请重试",U("Order/inputPanelIn","id=".$this->_post('id')));
    }
    
    public function closing()
    {
    	$dbGoods = D("Goods");
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$db = D("TmpOrder");
    	$db->init($dbUser->getTmpOrderID());
    	$tmp["id"] = $db->getIDArray();
    	$tmp["num"] = $db->getNumArray();
    	$tmp["money"] = $db->getMoneyArray();
    	$tmp["size"] = $db->getSizeArray();
    	for ($i = 0; $i < count($tmp["id"]); $i++)
    	{
    		$dbGoods->init($tmp["id"][$i]);
    		$orderInfo[$i]["goodsName"] = $dbGoods->getGoodsName();
    		$orderInfo[$i]["id"] = $tmp["id"]["$i"];
    		$orderInfo[$i]["num"] = $tmp["num"]["$i"];
    		$orderInfo[$i]["money"] = $tmp["money"]["$i"];
    		$orderInfo[$i]["size"] = $tmp["size"]["$i"];
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