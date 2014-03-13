<?php
require_once(LIB_PATH."commonAction.php");

class IndexAction extends myAction
{

    protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
    }

    public function index()
    {
    	redirect(U("User/login"),0);
        $this->display();
    }
    
    public function main()
    {
    	if (!$this->checkPower("serverPower",session("SeverUserPower")))
    		$this->error("非法访问",U("Index/index"));
    	
    	$this->display();
    }
    
}

?>