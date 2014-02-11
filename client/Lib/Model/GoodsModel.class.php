<?php
include(LIB_PATH."commonModel.php");

class GoodsModel extends ModelBaseOP
{
	// 自动验证设置
	protected $_validate = array(
	);
	
	public function init($id)//传入goodsID
	{
		$this->id = $id;
	}
	
	public function getGoodsName()
	{
		$condition['id'] = $this->id;
		$tmp = $this->where($condition)->select();
		return $tmp[0]["name"];
	}
	
	public function getAllGoodsInfo()
	{
		return $this->select();
	}
	
	/*
	 * 得到商品规格信息
	 * @return	array sizeArray[i];一个size数组
	 */
	public function getGoodsSize()
	{
		return $this->getArray("size");
	}
}
?>