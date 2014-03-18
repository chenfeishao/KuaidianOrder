<?php
require_once(LIB_PATH."commonModel.php");


/**
 * 订单基础操作类
 * @NOTE：	继承前，要初始化ModelBaseOP中的$id，即数据索引;
 */
Class OrderOP extends ModelBaseOP
{	

	/**
	 * 新建一个临时订单
	 */
	public function addTmpOrder($goodsID,$num,$size,$money)
	{
		$data["id"] = $this->id;
		$data["goodsIDArray"] = $this->appenOne("goodsIDArray",$goodsID);
		$data["goodsNumArray"] = $this->appenOne("goodsNumArray",$num);
		$data["goodsSizeArray"] = $this->appenOne("goodsSizeArray",$size);
		$data["goodsMoneyArray"] = $this->appenOne("goodsMoneyArray",$money);
		return $this->save($data);
	}
	
}
?>