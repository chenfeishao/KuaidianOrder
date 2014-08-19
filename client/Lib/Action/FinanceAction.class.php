<?php
require_once(LIB_PATH."commonAction.php");

class FinanceAction extends myAction
{
	/**
	 * 标签表的字母列表
	 */
	private $zimuTabList = array("A","B","C","D","E","F","G","H","I","J","K","M","L","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","其他");

    protected function _initialize()
    {
    	if (!$this->checkPower("financePower",session("userPower")))
    		$this->error("非法访问",U("Index/index"));
    	
    	//https开启了，且当前不是https访问，则强制跳转
    	if ( (_ISHTTPS === true) && ($this->_server["HTTPS"] <> "on") )
    		header("Location:https://".__SELF__);
    	
    	//初始化
    	for ($i = "A"; $i <= "Z"; $i++)
    		$zimuTabList	 = $i;
    }

    public function index()
    {
        $this->display();
    }
    
    /**
     * 应收款选账户页面
     */
    public function ar()
    {
    	$userList	=		D("User")->order("userName")->select();
    	
    	/*
    	 * $classInfo数组结构：
    	 * 			$classInfo[拼音开头字母][account][i][name] = a; 代表某个字母后面的第i个名字值是a
    	 * 			$classInfo[拼音开头字母][account][i][class] = "..."; 代表某个字母后面的第i个名字要显示的样式（css的class）是...
    	 * 			$classInfo[拼音开头字母][count]	  = 1;代表该拼音字母开头下有count个名字
    	 * 			$classInfo[拼音开头字母][tabName] = a;这个标签的标签名（类名），NOTE：是大写的
    	 */
    	for ($i = 0; $i < count($userList); $i++)
    	{
    		//准备格子的显示
    		$ch = null;
    		$ch = strtoupper($userList[$i]["userPinYin"][0]);
    		if (!isset($classInfo[$ch]["count"]))
    		{
    			$classInfo[$ch]["count"] =	0;
    			$classInfo[$ch]["tabName"] = $ch;
    		}
    		$classInfo[$ch]["account"][$classInfo[$ch]["count"]]["name"] = $userList[$i]["userName"];
    		if ($classInfo[$ch]["count"] % 2 == 0)
    			$classInfo[$ch]["account"][$classInfo[$ch]["count"]]["class"] = "shortcut primary";
    		else
    			$classInfo[$ch]["account"][$classInfo[$ch]["count"]]["class"] = "shortcut success";
    		$classInfo[$ch]["count"]++;
    		
    		//准备快速选择列表
    		$accountList[$i]["pyname"] =  $userList[$i]["userPinYin"];
    		$accountList[$i]["name"] =  $userList[$i]["userName"];
    	}
    	ksort($accountList);
    	ksort($classInfo);
//     	dump($classInfo);
    	
    	
    	//检查有没有超过$blockSize个商品的标签组，如果有超过的就划分为多个标签组
    	$i = 0;
    	foreach ($classInfo as $key=>$value)
    	{
    			if ($classInfo[$key]["count"] > BLOCKSIZE)
    			{
    				$tmpChunk = array_chunk($classInfo[$key]["account"],BLOCKSIZE);
    				for ($j = 0; $j < count($tmpChunk); $j++)
    				{
    					$tmp = null;
    					$tmp[$key.($j+1)]["tabName"] = $classInfo[$key]["tabName"]."-".($j+1);
    					$tmp[$key.($j+1)]["account"] = $tmpChunk[$j];
    					$classInfo = array_merge($classInfo,$tmp);//把新的拆分成2个后添加入原数组$classInfo
    				}
    				array_splice($classInfo,$i,1);//删除掉原来的那个分组（已经被拆分了）
    			}
    			$i++;
    	}
    	ksort($classInfo);
    	
    	$this->assign("list",$classInfo);
    	$this->assign("accountList",$accountList);
    	$this->display();
    }
    
    /**
     * 应收款填表页面
     */
    public function ardo()
    {
    	$id	=		$this->_get("id");
    	empty($id) && $this->error("非法操作",U("Finance/index"));
    	$this->assign("id",$id);
    	session("ardoID",$id);
    	$this->display();
    }
    
    /**
     * 处理应收款
     */
    public function toar()
    {
    	$id	=		session("ardoID");
    	$money	=	$this->_post("money");
    	$remark	=	$this->_post("remark");
    	empty($id) && $this->error("非法操作",U("Finance/index"));
    	empty($money) && $this->error("请填写金额",U("Finance/ardo",array("id"=>$id)));
    	
    	//TODO:检测id是否存在合法
    	
    	D("Finance")->startTrans();
    	if ( D("Finance")->newFinance($id,$money,$remark,0) )
    	{
    		if (D("User")->updateMoney(1,$id,$money))
    			D("Finance")->commit();
    	}
    	else
    	{
    		D("Finance")->rollback();
    		$this->error("应收款创建失败，请重试",U("Index/goBack_2"));
    	}
    	$this->success("应收款创建成功",U("Finance/index"));
    }
    
    /**
     * 应付款页面
     */
    public function ap()
    {
    	$this->display();
    }
    
    /**
     * 费用页面
     */
    public function cost()
    {
    	$this->display();
    }
    
    /**
     * 财务查询页面
     */
    public function query()
    {
    	$this->display();
    }
    
    /**
     * 今日销售汇总页面
     */
    public function summary()
    {
    	$dbTmpOrder = D("TmpOrder");
    	$dbGoods = D("Goods");
    	
    	//取出今日销售订单；NOTE：只按printDate时间，不按createDate时间
    	$done = $dbTmpOrder->where("printState='7' and (printDate>='".date("Y-m-d")." 00:00:00' and printDate<='".date("Y-m-d")." 23:59:59')")
    				->order('createDate')->select();
    	
    	$total = null;
    	$maxID = -1;
    	$maxSize = -1;
    	for ($i = 0; $i < count($done); $i++)
    	{
    		//得到商品ID进行统计
    		$IDArray = null;
    		$numArray = null;
    		$moneyArray = null;
    		$IDArray = $dbTmpOrder->getArrayWithSelf($done[$i]["goodsIDArray"]);
    		$numArray = $dbTmpOrder->getArrayWithSelf($done[$i]["goodsNumArray"]);
    		$moneyArray = $dbTmpOrder->getArrayWithSelf($done[$i]["goodsMoneyArray"]);
    		$sizeArray = $dbTmpOrder->getArrayWithSelf($done[$i]["goodsSizeArray"]);
    		for ($j = 0; $j < count($IDArray); $j++)
    		{
    			$total[$IDArray[$j]][$sizeArray[$j]]["num"] += $numArray[$j];
    			$total[$IDArray[$j]][$sizeArray[$j]]["money"] += ($numArray[$j] * $moneyArray[$j]);
    			if ($IDArray[$j] > $maxID)
    				$maxID = $IDArray[$j];
    			if ($sizeArray[$j] > $maxSize)
    				$maxSize = $sizeArray[$j];
    		}
    	}
    	
    	$outputList = null;
    	$totalMoney = 0;
    	$totalNum = 0;
    	$k = 0;
    	for ($i = 0; $i <= $maxID; $i++)
    		for ($j = 0; $j <= $maxSize; $j++)
	    	{
	    		if ( !( ($total[$i][$j]["num"] == 0) || ($total[$i][$j]["num"] == null) ) )
	    		{
		    		$dbGoods->init($i);
		    		$outputList[$k]["name"] = $dbGoods->getGoodsName();
		    		$tmp = null;
		    		$tmp =  $dbGoods->getGoodsSize();
		    		$outputList[$k]["size"] = $tmp[$j];
		    		$outputList[$k]["id"] = $i;
		    		$outputList[$k]["num"] = $total[$i][$j]["num"];
		    		$outputList[$k]["totalPrice"] = $total[$i][$j]["money"];
		    		$outputList[$k]["price"] = round($outputList[$k]["totalPrice"] / $outputList[$k]["num"],2);
		    		
		    		$totalMoney += $outputList[$k]["totalPrice"];
		    		$totalNum += $outputList[$k]["num"];
		    		$k++;
	    		}
	    	}
	   $this->assign("totalMoney",$totalMoney);
	   $this->assign("totalNum",$totalNum);
	   $this->assign("list",$outputList);
	   $this->display();
    }
}

?>