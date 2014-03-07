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
    
}

?>