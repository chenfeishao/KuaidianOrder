<?php
include(LIB_PATH."OrderOP.php");

class OrderModel extends OrderOP {

	// 自动验证设置
	protected $_validate = array(
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