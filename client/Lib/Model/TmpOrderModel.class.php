<?php
include(LIB_PATH."OrderOP.php");

class TmpOrderModel extends OrderOP {

	// 自动验证设置
	protected $_validate = array(
			array('num','require','数量不能为空'),
			array('size','require','规格不能为空'),
			array('money','require','单价不能为空'),
			array('num','/^-?\d+(\.\d+)?$/','数量必须为数字！',0,'regex'),//默认情况下用正则进行验证
			array('size','/^-?\d+(\.\d+)?$/','规格必须为数字！',0,'regex'),
			array('money','/^-?\d+(\.\d+)?$/','单价必须为数字！',0,'regex'),
	);
	
	public function init($id)
	/*
	 * 初始化订单ID
	 */
	{
		$this->id = $id;
	}
	
}
?>