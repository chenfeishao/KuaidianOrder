<?php
require_once(LIB_PATH."OrderOP.php");

class TmpOrderModel extends OrderOP {

	// 自动验证设置
	protected $_validate = array(
			array('id','require','商品选择不正确，请重新选择',1),
			array('num','require','数量不能为空',1),
			array('size','require','规格不能为空'),
			array('money','require','单价不能为空',1),
			array('id','checkID','商品选择不正确，请重新选择！',1,'callback'),
			array('num','/^-?\d+(\.\d+)?$/','数量必须为数字！',1,'regex'),
			array('size,id','checkSize','规格选择不正确！',1,'callback'),
			array('money','/^-?\d+(\.\d+)?$/','单价必须为数字！',1,'regex'),
	);
	
	/*
	 * 检查所选商品id是否是数字，且是否在正确范围内
	* @param	string $data;表单数据中的id字段
	* @return	bool;验证是否正确
	*/
	protected function checkID($data)
	{
		$dbGoods = D("Goods");
		return $dbGoods->checkID($data);
	}
	
	/*
	 * 检查规格是否是数字，且是否在正确范围内
	 * @NOTE	当选择的商品id错误的时候，因为查不出来数据，所以$tmp为0，这里永远为false
	 * @param	array $data;表单数据.eg:$data["id"]
	 * @return	bool;验证是否正确
	 */
	protected function checkSize($data)
	{
		$dbGoods = D("Goods");
		$dbGoods->init($data["id"]);
		$tmp = $dbGoods->getGoodsSize();
		$tmp = count($tmp);
		return ( isNum($data["size"]) && ($data["size"] >=0) && ($data["size"] < $tmp) );//必须要有>=0,不然-1也小于$tmp=0时。
	}
	
	/*
	 * 初始化临时订单的ID，即用户账户内存的tmpOrderID
	*/
	public function init($id)
	{
		$this->id = $id;
	}
	
}
?>