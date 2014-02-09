<?php
class GoodsModel extends Model {

	// 自动验证设置
	protected $_validate = array(
	);
	
	private $goodsID = "";
	
	public function init($id)//传入userName
	{
		$this->goodsID = $id;
	}
	
	public function getGoodsName()
	{
		$condition['id'] = $this->goodsID;
		return $this->where($condition)->select()[0]["name"];
	}
	
	public function getAllGoodsName()
	{
		return $this->select();
	}
}
?>