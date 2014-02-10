<?php

class IndexAction extends Action
{

    protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
    }

    public function index()
    {
    	//显示所有商品
    	$db = D("Goods");
    	$goodsInfo = $db->getAllGoodsInfo();
    	$showInfo = NULL;
    	for ($i = 0; $i < count($goodsInfo); $i++)
    	{
    		if (strpos($goodsInfo[$i]["highWide"],"half"))//如果有half大小的，就把名字抹去
    		{
    			$showInfo[$i]["name"] = "";
    		}
    		else
    		{
    			$showInfo[$i]["name"] = $goodsInfo[$i]["name"];
    		}
    		$showInfo[$i]["id"] = $goodsInfo[$i]["id"];
    		$tmp = "";
    		switch ($goodsInfo[$i]["style"])
    		{
    			//图片瓷片
    			case 1:
    				$showInfo[$i]["className"] = $goodsInfo[$i]["highWide"]." image"." bg-".$goodsInfo[$i]["bgColor"];
    				$showInfo[$i]["content"] = "tile-content";
    				$showInfo[$i]["brand"] = "brand"." bg-".$goodsInfo[$i]["brandColor"];
    				$showInfo[$i]["image"] = $goodsInfo[$i]["image"];
    				break;
    			
    			//图标瓷片
    			case 2:
    				$showInfo[$i]["className"] = $goodsInfo[$i]["highWide"]." bg-".$goodsInfo[$i]["bgColor"];
    				$showInfo[$i]["content"] = "tile-content icon";
    				$showInfo[$i]["brand"] = "brand"." bg-".$goodsInfo[$i]["brandColor"];
    				$showInfo[$i]["image"] = $goodsInfo[$i]["image"];
    				break;
    					
    			//普通瓷片
    			default:
    			case 3:
    				$showInfo[$i]["className"] = $goodsInfo[$i]["highWide"]." bg-".$goodsInfo[$i]["bgColor"];
    				$showInfo[$i]["content"] = "tile-content icon";
    				$showInfo[$i]["brand"] = "brand"." bg-".$goodsInfo[$i]["brandColor"];
    				$showInfo[$i]["image"] = null;
    				break;
    				
    			//介绍瓷片
    			//TODO: 没做完，这里：email-image
    			case 4:
    				$showInfo[$i]["className"] = $goodsInfo[$i]["highWide"]." bg-".$goodsInfo[$i]["bgColor"];
    				$showInfo[$i]["content"] =  "tile-content email";
    				$showInfo[$i]["brand"] = "brand"." bg-".$goodsInfo[$i]["brandColor"];
    			break;
    		}
    	}
    	$this->assign("goods",$showInfo);//先渲染这里，因为下面要改动$showInfo，所以必须要在这里渲染
    	
    	//显示购物车
    	$select = null;
    	
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$db = D("TmpOrder");
    	$db->init($dbUser->getTmpOrderID());
    	$idArray = $db->getIDArray();
    	for ($i = 0; $i < count($idArray); $i++)
    		for ($j = 0; $j < count($idArray); $j++)
	    	{
	    		
	    	}
    	
    	$this->assign("select",$select);
    	
    	
    	$this->assign("productList",$goodsInfo);//快速选择列表
        $this->display();
    }
    
    public function noDisplay()
    {
    	$this->display();
    }
    
}

?>