<?php
require_once(LIB_PATH."commonAction.php");

class UserAction extends myAction
{
	protected function _initialize()
	{
		//https开启了，且当前不是https访问，则强制跳转
		if ( (_ISHTTPS === true) && ($this->_server["HTTPS"] <> "on") )
			header("Location:https://".__SELF__);
	}
	
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
    	if ( session('verify') != md5($this->_post('yzm')) )
    	{
    		$this->error('验证码错误！',U("Index/index"));
    	}
    	
    	$dbUser = D("User");
    	$dbUser->init($this->_post('userName'));
    	if ( $result = $dbUser->login($this->_post('userPassword')) )
    	{
    		//设置session
    		session('userName',$result['userName']);
    		switch ($result['userPower'])
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
    		cookie('userName',$result['userName']);
    		
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
    
    /**
     * ajax得到用户信息的页面
     */
    public function ajaxGetUserInfo()
    {
    	$dbUser = D("User");
    	if ( ($this->_post("name") == null) || ($this->_post("name") == "") )
    		$re = false;
    	else
    		$re = $dbUser->getUserInfo($this->_post("name"));
    	if ( ($re == false) || ($re == null) )//查询错误返回false;查询结果为空返回null;查询成功返回查询的结果集（二维索引数组）
    	{
    		echo "错误的用户名";
    	}
    	else
    	{
    		echo $re["tel"]._AJAX_BREAK_TAG.$re["address"]._AJAX_BREAK_TAG.$re["carAddress"]
    						._AJAX_BREAK_TAG.$re["carNo"]._AJAX_BREAK_TAG.$re["money"];
    	}
    }
    
}

?>