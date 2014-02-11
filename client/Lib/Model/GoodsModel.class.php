<?php
class GoodsModel extends Model {

	// 自动验证设置
	protected $_validate = array(
	);
	
	private $goodsID = "";
	
	public function init($id)//传入goodsID
	{
		$this->goodsID = $id;
	}
	
	public function getGoodsName()
	{
		$condition['id'] = $this->goodsID;
		$tmp = $this->where($condition)->select();
		return $tmp[0]["name"];
	}
	
	public function getAllGoodsInfo()
	{
		return $this->select();
	}
}
?>