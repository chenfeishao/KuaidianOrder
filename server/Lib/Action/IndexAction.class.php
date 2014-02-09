<?php

class IndexAction extends Action
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
    	$this->display();
    }
    
}

?>