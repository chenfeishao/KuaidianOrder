<?php
class myAction extends Action
{
	protected function isOk($ok,$trueStr,$trueU,$falseStr,$falseU = 0,$trueUParam = 0,$falseUParam = 0,$time = -1)
	/*
	 * 判读是否成功执行操作，并支持成功跳转和失败跳转
	 * @param	bool $ok;判断量
	 * 			string $trueStr;为真提示符
	 * 			string $trueU;为真跳转U操作参数,若没有跳转操作则传0
	 * 			string $falseStr;为假提示符
	 * 			string $falseU为假跳转U操作参数,若没有跳转操作则传0
	 * 			string $trueUParam;为真跳转地址的get参数
	 * 			string $falseUParam;为假跳转地址的get参数
	 * 			int $time;跳转时间（-1为默认）
	 * @note 	1.立即跳转需要传入U函数参数
	 * 			2.原地跳转需传参数时，需要传入跳转地址
	 */
	{
		//$this->assign('waitSecond',135);
	
		if ($time == -1)//默认跳转时间
		{
			if ($ok)
			{
				if ($trueU === 0)
				{
					$this->success($trueStr);
				}
				else
				{
					if ($trueUParam === 0)
						$this->success($trueStr,U($trueU));
					else
						$this->success($trueStr,U($trueU,$trueUParam));
				}
			}
			else
			{
				if ($falseU === 0)
					$this->error($falseStr);
				else
				{
					if ($falseUParam === 0)
						$this->error($falseStr,U($falseU));
					else
						$this->error($falseStr,U($falseU,$falseUParam));
				}
			}
		}
		else
		if ($time == 0)//立即跳转
		{
			if ($ok)
			{
				if ($trueUParam === 0)
					redirect(U($trueU),0);
				else
					redirect(U($trueU,$trueUParam),0);
			}
			else
			{
				if ($falseUParam === 0)
					redirect(U($falseU),0);
				else
					redirect(U($falseU,$falseUParam),0);
			}
		}
		else//延时跳转
		{
			$this->assign('waitSecond',$time);
			if ($ok)
			{
				if ($trueU === 0)
				{
					$this->success($trueStr);
				}
				else
				{
					if ($trueUParam === 0)
						$this->success($trueStr,U($trueU));
					else
						$this->success($trueStr,U($trueU,$trueUParam));
				}
			}
			else
			{
				if ($falseU === 0)
					$this->error($falseStr);
				else
				{
					if ($falseUParam === 0)
						$this->error($falseStr,U($falseU));
					else
						$this->error($falseStr,U($falseU,$falseUParam));
				}
			}
		}
	}
	
	protected function isFalse($ok,$falseStr,$falseU = 0,$param = 0,$time = -1)
	/*
	 * 当$ok为false时进行跳转
	 * @param	boolean $ok;判断量
	 * 			string $falseStr;$ok为假时的提示符
	 * 			string $falseU;$ok为假时的跳转U操作参数
	 * 			string $param;跳转地址附带的get参数
	 * 			int $time;跳转时间
	 */
	{
		if ($time == -1)//默认跳转时间
		{
			if (!$ok)
			{
				if ($falseU === 0)
					$this->error($falseStr);
				else if ($param === 0)
					$this->error($falseStr,U($falseU));
				else
					$this->error($falseStr,U($falseU,$param));
			}
		}
		else//延时跳转
		{
			if (!$ok)
			{
				$this->assign('waitSecond',$time);
				if ($falseU === 0)
					$this->error($falseStr);
				else if ($param === 0)
					$this->error($falseStr,U($falseU));
				else
					$this->error($falseStr,U($falseU,$param));
			}
		}
	}
	
	/*
	 * 当$ok为false时进行跳转。这里$ok要严格为false才可。否则都认为是true
	* @param	boolean $ok;判断量
	* 			string $falseStr;$ok为假时的提示符
	* 			string $falseU;$ok为假时的跳转U操作参数
	* 			string $param;跳转地址附带的get参数
	* 			int $time;跳转时间
	* @Note		这里$ok要严格为false才可。否则都认为是true
	*/
	protected function isFalsePlus($ok,$falseStr,$falseU = 0,$param = 0,$time = -1)
	{
		if ($time == -1)//默认跳转时间
		{
			if ($ok === false)
			{
				if ($falseU === 0)
					$this->error($falseStr);
				else if ($param === 0)
					$this->error($falseStr,U($falseU));
				else
					$this->error($falseStr,U($falseU,$param));
			}
		}
		else//延时跳转
		{
			if ($ok === false)
			{
				$this->assign('waitSecond',$time);
				if ($falseU === 0)
					$this->error($falseStr);
				else if ($param === 0)
					$this->error($falseStr,U($falseU));
				else
					$this->error($falseStr,U($falseU,$param));
			}
		}
	}
	
	/*
	 * 权限检测
	 * @param	string $action;要检测的规则
	 * 			string $userPower;要检测的power
	 * @return	bool;是否通过检测
	 */
	protected function checkPower($action,$userPower)
	{
		switch($action)
		{
			case "isLogin"://是否登录
				{
					if ( ($userPower == "") || ($userPower == null) )
						return false;
					break;
				}
			case "canEditOrderMoney"://能不能修改订单钱数
				{
					if ( ($userPower != "根账户") && ($userPower != "管理员") && ($userPower != "营业员") )
						return false;
					break;
				}
			case "canWatchHistory"://能不能查看历史订单
				{
					if ( ($userPower != "根账户") && ($userPower != "管理员") && ($userPower != "营业员") )
						return false;
					break;
				}
			case "financePower"://财务管理权限
				{
					if ( ($userPower != "根账户") && ($userPower != "管理员") && ($userPower != "营业员") )
						return false;
					break;
				}
			case "serverPower"://后台管理权限
				{
					if ( ($userPower != "根账户") && ($userPower != "管理员") )
						return false;
					break;
				}
			default:return false;break;
		}
		return true;
	}
}
?>