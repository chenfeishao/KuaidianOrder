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
    
    public function toAdd()
    {
    	$db = M("Goods");
    	$this->isFalse($db->create(),$db->getError(),"Goods/add");
    	dump($db->high);
    	$this->display();
    }
    
}

?>