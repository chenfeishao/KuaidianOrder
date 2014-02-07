<?php
include(LIB_PATH."commonAction.php");

class OrderAction extends myAction
{

    public function inputPanel()
    {
    	$db = D("Goods");
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
    
}

?>