<?php
include(LIB_PATH."OrderOP.php");

class TmpOrderModel extends OrderOP {

	// 自动验证设置
	protected $_validate = array(
			array('id','require','商品选择不正确，请重新选择'),
			array('num','require','数量不能为空'),
			array('size','require','规格不能为空'),
			array('money','require','单价不能为空'),
			array('id','number','商品选择不正确，请重新选择！'),
			array('num','/^-?\d+(\.\d+)?$/','数量必须为数字！',0,'regex'),//默认情况下用正则进行验证
			array('size','/^-?\d+(\.\d+)?$/','规格必须为数字！',0,'regex'),
			array('money','/^-?\d+(\.\d+)?$/','单价必须为数字！',0,'regex'),
	);
	
	
	/*
	 * 初始化临时订单的ID，即用户账户内存的tmpOrderID
	*/
	public function init($id)
	{
		$this->id = $id;
	}
	
}
?>