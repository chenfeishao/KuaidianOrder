<?php
include(LIB_PATH."commonAction.php");
class GoodsAction extends myAction
{

    protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
    }

    public function add()
    {
        $this->display();
    }
    
    public function edit()
    {
    	$this->display();
    }
    
    public function toAdd()
    {
    	//获得表单数据
    	$db = D("Goods");
    	$this->isFalse($db->create(),$db->getError(),"Goods/add");
    	$db->high = $this->_post("high");
    	$db->wide = $this->_post("wide");
    	
    	$tag = $db->addGoodsFromForm($db->data());
		if ($tag === false)
		{
			$this->isFalse(false,"添加商品失败，请重试","Goods/add");
		}
		elseif ($tag === -1)
		{
			$this->isFalse(false,"库存数据格式错误，请更正","Goods/add");
		}
		else
		{
			$this->success("商品添加成功",U("Goods/add"));
		}
		
    	$this->display();
    }
    
}

?>