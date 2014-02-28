<?php
require_once(LIB_PATH."commonAction.php");

class IndexAction extends myAction
{

    protected function _initialize()
    {
    	
    }

    public function index()
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
    	$blockSize=30;//3*x
    	$i = 0;
    	$arrayCount = count($allInfo);
    	while ($i < $arrayCount)
    	{
    		if (count($allInfo[$i]["goods"]) > $blockSize)
    		{
    			$tmpChunk = array_chunk($allInfo[$i]["goods"],$blockSize);
    			for ($j = 0; $j < count($tmpChunk); $j++)
    			{
    				$tmp = null;
    				$tmp[0]["tabName"] = $allInfo[$i]["tabName"]."-".($j+1);
    				$tmp[0]["goods"] = $tmpChunk[$j];
    				$allInfo = array_merge($allInfo,$tmp);
    			}
    		}
    		$arrayCount = count($allInfo);
    		$i++;
    	}
    	
    	
    	$this->assign("list",$allInfo);
    	
    	/*
    	 * 显示购物车
    	 */
    	$select = null;
    	
    	$dbUser = D("User");
    	$dbUser->init(session("userName"));
    	$dbTmpOrder = D("TmpOrder");
    	$dbTmpOrder->init($dbUser->getTmpOrderID());
    	$idArray = $dbTmpOrder->getArray("goodsIDArray");
    	$numArray = $dbTmpOrder->getArray("goodsNumArray");
    	for ($i = 0; $i < count($idArray); $i++)
    	{
	    	for ($j = 0; $j < count($showInfo); $j++)
	    	{
		    	if ($idArray[$i] == $showInfo[$j]["id"])
		    	{
		    		$select[$i] = $showInfo[$j];
		    		$select[$i]["num"] = $numArray[$i];
		    		break;
		    	}
	    	}
    	}
    	$this->assign("select",$select);
    	
    	
    	$this->assign("productList",$goodsInfo);//快速选择列表
        $this->display();
    }
    
    public function noDisplay()
    {
    	$this->display();
    }
    
    public function goBack()
    {
    	$this->display();
    }
}

?>