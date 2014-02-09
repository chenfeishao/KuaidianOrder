<?php

class IndexAction extends Action
{

    protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
    }

    public function index()
    {
    	$db = D("Goods");
    	$this->assign("productList",$db->getAllGoodsName());
        $this->display();
    }
    
}

?>