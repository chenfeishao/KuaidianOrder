<?php
require_once(LIB_PATH."commonAction.php");

class FinanceAction extends myAction
{

    protected function _initialize()
    {
    	if (!$this->checkPower("financePower",session("userPower")))
    		$this->error("非法访问",U("Index/index"));
    	
    	//https开启了，且当前不是https访问，则强制跳转
    	if ( (_ISHTTPS === true) && ($this->_server["HTTPS"] <> "on") )
    		header("Location:https://".__SELF__);
    }

    public function index()
    {
        $this->display();
    }
    
    /**
     * 应收款页面
     */
    public function ar()
    {
    	
    	$this->display();
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
    	
    	for ($i = 0; $i < count($done); $i++)
    	{
    		
    	}
    	$this->display();
    }
}

?>