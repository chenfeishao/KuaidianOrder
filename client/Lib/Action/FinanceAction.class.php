<?php
require_once(LIB_PATH."commonAction.php");

class FinanceAction extends myAction
{

    protected function _initialize()
    {
    	if (!$this->checkPower("financePower",session("userPower")))
    		$this->error("非法访问",U("Index/index"));
    }

    public function index()
    {
        $this->display();
    }
    
}

?>