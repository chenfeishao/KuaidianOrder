<?php
require_once(LIB_PATH."commonModel.php");

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
	
	/**
	 * 得到商品规格信息
	 * @return	array sizeArray[i];一个size数组
	 */
	public function getGoodsSize()
	{
		return $this->getArray("size");
	}
	
	/**
	 * 检查商品id是否是数字，且是否在正确范围内
	* @param	string $id;id字段
	* @return	bool;
	* 				true:id合法
	* 				false:id不合法
	*/
	public function checkID($id)
	{
		$tmp = null;
		$tmp = $this->where("id=".$id)->select();
		$tmp = count($tmp);
		return ( isNum($id) && ($tmp == 1) );
	}
	
	/**
	* 检查规格是否是数字，且是否在正确范围内
	* @NOTE	当选择的商品id错误的时候，因为查不出来数据，所以$tmp为0，这里永远为false
	* @param	int $sizeNo;要验证的size编号
	* @return	bool;验证是否正确
	*/
	public function checkSize($sizeNo)
	{
		$tmp = $this->getGoodsSize();
		$tmp = count($tmp);
		return ( isNum($sizeNo) && ($sizeNo >=0) && ($sizeNo < $tmp) );//必须要有>=0,不然-1也小于$tmp=0时。
	}
}
?>