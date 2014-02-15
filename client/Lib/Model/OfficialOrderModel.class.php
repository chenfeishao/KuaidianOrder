<?php
require_once(LIB_PATH."OrderOP.php");

class OfficialOrderModel extends OrderOP {

	// 自动验证设置
	protected $_validate = array(
	);
	
	/*
	 * 新建一条订单
	 * @param	array $data; 除了创建时间字段外的其他字段的数据
	 * @return	bool;创建是否成功
	 */
	public function newOrder($data)
	{
		/*
		 * 预处理数据
		 */
		$data["createDate"] = date("Y-m-d H:i:s");
		array_splice($data,0,1);//删除tmpOrder中的ID
		return $this->add($data);
	}
}
?>