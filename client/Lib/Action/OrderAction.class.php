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
    
    public function toOneOrder()
    {
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$db = D("TmpOrder");
    	$this->isFalse($db->create(),$db->getError(),"Order/inputPanel","id=".$this->_post('id'));
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
    	$this->isFalse($db->create(),$db->getError(),"Order/inputPanelIn","id=".$this->_post('id'));
    	$db->init($dbUser->getTmpOrderID());
    	if ($db->insert($this->_post("id"),$this->_post("num"),$this->_post("size"),$this->_post("money")))
    		redirect(U("Index/noDisplay"),0);
    	else
    		$this->error("订单提交失败，请重试",U("Order/inputPanelIn","id=".$this->_post('id')));
    }
}

?>