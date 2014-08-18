<?php
require_once(LIB_PATH."commonAction.php");

class GoodsAction extends myAction
{

    protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
        
        if (!$this->checkPower("serverPower",session("serverUserPower")))
        	$this->error("非法访问",U("Index/index"));
    }

    public function add()
    {
        $this->display();
    }
    
    public function watch()
    {
    	/*
    	 * 得到所有商品信息
    	 */
    	$dbGoods = D("Goods");
    	$goodsInfo = $dbGoods->order("oem")->select();
    	$preShowInfo = NULL;
    	for ($i = 0; $i < count($goodsInfo); $i++)
    	{
    		if (strpos($goodsInfo[$i]["highWide"],"half"))//如果有half大小的，就把名字抹去
    		{
    			$preShowInfo[$i]["name"] = "";
    		}
    		else
    		{
    			$preShowInfo[$i]["name"] = $goodsInfo[$i]["name"];
    		}
//     		$preShowInfo[$i]["id"] = AES_CBC(0, $goodsInfo[$i]["id"]);
    		$preShowInfo[$i]["id"] = $goodsInfo[$i]["id"];
    		$tmp = "";
    		switch ($goodsInfo[$i]["style"])
    		{
    			//图片瓷片
    			case 1:
    				$preShowInfo[$i]["className"] = $goodsInfo[$i]["highWide"]." image"." bg-".$goodsInfo[$i]["bgColor"];
    				$preShowInfo[$i]["content"] = "tile-content";
    				$preShowInfo[$i]["brand"] = "brand"." bg-".$goodsInfo[$i]["brandColor"];
    				$preShowInfo[$i]["image"] = $goodsInfo[$i]["image"];
    				break;
    			
    			//图标瓷片
    			case 2:
    				$preShowInfo[$i]["className"] = $goodsInfo[$i]["highWide"]." bg-".$goodsInfo[$i]["bgColor"];
    				$preShowInfo[$i]["content"] = "tile-content icon";
    				$preShowInfo[$i]["brand"] = "brand"." bg-".$goodsInfo[$i]["brandColor"];
    				$preShowInfo[$i]["image"] = $goodsInfo[$i]["image"];
    				break;
    					
    			//普通瓷片
    			default:
    			case 3:
    				$preShowInfo[$i]["className"] = $goodsInfo[$i]["highWide"]." bg-".$goodsInfo[$i]["bgColor"];
    				$preShowInfo[$i]["content"] = "tile-content icon";
    				$preShowInfo[$i]["brand"] = "brand"." bg-".$goodsInfo[$i]["brandColor"];
    				$preShowInfo[$i]["image"] = null;
    				break;
    				
    			//介绍瓷片
    			//TODO: 没做完，这里：email-image
    			case 4:
    				$preShowInfo[$i]["className"] = $goodsInfo[$i]["highWide"]." bg-".$goodsInfo[$i]["bgColor"];
    				$preShowInfo[$i]["content"] =  "tile-content email";
    				$preShowInfo[$i]["brand"] = "brand"." bg-".$goodsInfo[$i]["brandColor"];
    			break;
    		}
    		$preShowInfo[$i]["oem"] = $goodsInfo[$i]["oem"];
    	}
    	$showInfo = $preShowInfo;//因为购物车要用$showInfo
    	
    	/*
    	 * 分类
    	 */
    	//得到所有厂商类别
    	$allInfo = null;
    	$tmpOemArray = $dbGoods->group("oem")->select();
    	for ($i = 0; $i < count($tmpOemArray); $i++)
    	{
    		$allInfo[$i]["tabName"] = $tmpOemArray[$i]["oem"];
    	}
    	
    	//根据厂商类别分类
    	$st = 0;
    	for ($i = 0; $i < count($allInfo); $i++)
    	{
    		for ($j = $st; $j < count($preShowInfo); $j++)
    		{
    			if ($preShowInfo[$j]["oem"] == $allInfo[$i]["tabName"])
    			{
    				$allInfo[$i]["goods"][$j - $st] = $preShowInfo[$j];
    			}
    			else
    			{
	    			$st = $j;
	    			break;
    			}
    		}
    	}
    	
    	//检查有没有超过$blockSize个商品的标签组
    	$i = 0;
    	$arrayCount = count($allInfo);
    	while ($i < $arrayCount)
    	{
    		if (count($allInfo[$i]["goods"]) > BLOCKSIZE)
    		{
    			$tmpChunk = array_chunk($allInfo[$i]["goods"],BLOCKSIZE);
    			for ($j = 0; $j < count($tmpChunk); $j++)
    			{
    				$tmp = null;
    				$tmp[0]["tabName"] = $allInfo[$i]["tabName"]."-".($j+1);
    				$tmp[0]["goods"] = $tmpChunk[$j];
    				$allInfo = array_merge($allInfo,$tmp);
    			}
    			array_splice($allInfo,$i,1);
    		}
    		$arrayCount = count($allInfo);
    		$i++;
    	}
    	
    	
    	$this->assign("list",$allInfo);
    	
    	$this->assign("productList",$goodsInfo);//快速选择列表
        $this->display();
    }
    
    public function edit()
    {
    	$dbGoods = D("Goods");
    	$dbGoods->init($this->_get("id"));
    	
    	$this->assign($dbGoods->getInfo());
    	
    	$this->display();
    }
    
    public function toEdit()
    {
    	//获得表单数据
    	$db = D("Goods");
    	$this->isFalse($db->create(),$db->getError(),"Goods/edit",array("id"=>$this->_get("id")));
    	$db->high = $this->_post("high");
    	$db->wide = $this->_post("wide");
    	 
    	$tag = $db->addOrEditGoodsFromForm($db->data(),1,$this->_get(id));
    	if ( ($tag === false) || ($tag === null) )
    	{
    		$this->isFalse(false,"修改商品失败，请重试","Goods/edit",array("id"=>$this->_get("id")));
    	}
    	elseif ($tag === -1)
    	{
    		$this->isFalse(false,"库存数据格式错误，请更正","Goods/edit",array("id"=>$this->_get("id")));
    	}
    	else
    	{
    		$this->success("商品修改成功",U("Goods/watch"));
    	}
    }
    
    public function toAdd()
    {
    	//获得表单数据
    	$db = D("Goods");
    	$this->isFalse($db->create(),$db->getError(),"Goods/add");
    	$db->high = $this->_post("high");
    	$db->wide = $this->_post("wide");
    	
    	$tag = $db->addOrEditGoodsFromForm($db->data(),0);
		if ( ($tag === false) || ($tag === null) )
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
    }
    
}

?>