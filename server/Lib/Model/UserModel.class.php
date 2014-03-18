<?php
class UserModel extends Model {

	// 自动验证设置
	protected $_validate = array(
			array('userName', 'require', '用户名不能为空！'),
			array('userName','','用户名已经存在！',0,'unique',Model::MODEL_BOTH), //验证name字段是否唯一
			array('userPassword', 'require', '密码不能为空！', 0),
			array('userPassword2', 'require', '请输入第二遍密码', 0),
			array('userPassword','userPassword2','两次输入的密码不一样',0,'confirm',Model::MODEL_BOTH), // 验证确认密码是否和密码一致
	);
	
	private $userName = "";
	private $tmpOrderID = "";
	
	public function init($userName)//传入userName
	{
		$this->userName = $userName;
		$condition['userName'] = $this->userName;
		$result = $this->where($condition)->select();
		$this->tmpOrderID = $result[0]["tmpOrderID"];
	}
	
	public function getTmpOrderID()
	{
		return $this->tmpOrderID;
	}
	
	/**
	 * 判断用户名和密码是否能登录
	 * @param string $userPassword 用户密码
	 * @return 数据库返回的结果集，数组大小应为1
	 */
	public function login($userPassword)
	{
		$condition['userName'] = $this->userName;
		$condition['userPassword'] = $userPassword;
		$tmp = $this->where($condition)->select();
		if ( ($tmp[0]["userPower"] == "root")
				|| ($tmp[0]["userPower"] == "admin")
			)
			return $tmp[0];
		else
			return false;
	}
}
?>