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
}
?>