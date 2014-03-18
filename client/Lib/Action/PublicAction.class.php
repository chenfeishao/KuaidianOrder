<?php
class PublicAction extends Action
{
	/**
	 * 验证码显示
	 */
	Public function verify(){
		import('ORG.Util.Image');
		Image::buildImageVerify(6,5);
	}
}
?>