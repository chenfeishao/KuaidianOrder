<?php
class PublicAction extends Action{
	Public function verify(){
		import('ORG.Util.Image');
		Image::buildImageVerify(6,5);
	}
}
?>