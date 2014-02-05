<?php

class UserAction extends Action
{
	private function isLogin()//判断是否已经登陆
	{
		if (session('?userName'))//如果用户已经存在
		{
			return true;
		}
		else
			return false;
	}
	
	private function alreadyLogin()//已经登录过了，跳转到用户首页
	{
		if ($this->isLogin())
			redirect(U('Index/index'),0);
	}

    public function login()
    {
    	$this->alreadyLogin();
    	$this->display();
    }
    
    public function toLogin()//判断登录是否成功
    {
    	$dbUser = D("User");
    	$dbUser->init($this->_post('userName'));
    	if($result = $dbUser->login($this->_post('userPassword')))
    	{
    		//设置session
    		session('userName',$result[0]['userName']);
    		switch ($result[0]['userPower'])
    		{
    			case "root": $userPower = "根账户";break;
    			case "admin": $userPower = "管理员";break;
    			case "yyy": $userPower = "营业员";break;
    			case "zs": $userPower = "钻石账户";break;
    			case "bj": $userPower = "铂金账户";break;
    			case "j": $userPower = "金账户";break;
    			default: $userPower = "普通账户";break;
    		}
    		session('userPower',$userPower);
    		cookie('userName',$result[0]['userName']);
    		
    		$this->success('登陆成功',U('Index/index'));
    	}
    	else
    	{
    		$this->error('登录失败');
    	}
    }
    
    public function logout()//安全退出
    {
    	//判断session是否存在
    	if (!session('?userName'))
    	{
    		$this->error('非法登录',U('Index/index'));
    	}
    
    	//删除session
    	session('userName',null);
    	session('userPower',null);
    	cookie('userName',null);
    
    	//再次判断session是否存在
    	if ( (session('?userName')) || (session('?userPower')) )
    		$this->error('退出失败');
    	else
    		$this->success('退出成功',U('Index/index'));////////////////////////////////////////////////////////
    }
    
    public function index()
    {
    	//判断是否登录
    	if (!$this->isLogin())
    		redirect(U('User/login'),0);
    	
    	//已经登录了
        redirect(U('Index/index'),0);
    }
    
}

?>