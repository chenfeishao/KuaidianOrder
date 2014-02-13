<?php
require_once(LIB_PATH."OrderOP.php");

class TmpOrderModel extends OrderOP {

	/*
	 * updateTmpOrder方法出错返回的消息
	 */
	protected $updateTmpOrderError = "";
	
	/*
	 * 初始化临时订单的ID，即用户账户内存的tmpOrderID
	*/
	public function init($id)
	{
		$this->id = $id;
	}

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
	 * 自动验证用
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
	 * 自动验证用
	 * 检查规格是否是数字，且是否在正确范围内
	 * @NOTE	当选择的商品id错误的时候，因为查不出来数据，所以$tmp为0，这里永远为false
	 * @param	array $data;表单数据.eg:$data["id"]
	 * @return	bool;验证是否正确
	 */
	protected function checkSize($data)
	{
		$dbGoods = D("Goods");
		$dbGoods->init($data["id"]);
		return $dbGoods->checkSize($data["size"]);
	}
	
	/*
	 * 从结算页面创建最终数据
	 * @param	array[i] $originData;_post方法传来的数据
	 * @return	bool 是否更新成功
	 * @NOTE	如果返回false，用updateTmpOrderGetError方法获得错误消息
	 */
	public function updateTmpOrder($originData)
	{
		$tag = 1;//状态信息
		$msg = "";//是哪一个字段出现问题了。eg：规格
		$i = 1;//因为for外面要用，所以要先定义
		
		$idArray = $this->getArray("goodsIDArray");
		//验证数据
		for ($i = 0; $i < (count($originData) / 3); $i++)//表单上的值是从1开始的
		{
			//得到数据
			$numName = "num".($i+1);
			$sizeName = "size".($i+1);
			$moneyName = "money".($i+1);
			$data['num'][$i] = $originData[$numName];
			$data['size'][$i] = $originData[$sizeName];
			$data['money'][$i] = $originData[$moneyName];
			
			//验证
			if ( ($data['num'][$i] == "") || ($data['num'][$i] == null) )
			{
				$tag = -1;
				$msg = "数量";
				break;
			}
			if ( ($data['size'][$i] == "") || ($data['size'][$i] == null) )
			{
				$tag = -2;
				$msg = "规格";
				break;
			}
			if ( ($data['money'][$i] == "") || ($data['money'][$i] == null) )
			{
				$tag = -3;
				$msg = "单价";
				break;
			}
			if (!isNumWithPoint($data['num'][$i]))
			{
				$tag = -4;
				$msg = "数量";
				break;
			}
			if (!isNumWithPoint($data['money'][$i]))
			{
				$tag = -5;
				$msg = "单价";
				break;
			}
			$dbGoods = D("Goods");
			$dbGoods->init($idArray[$i]);
			if (!$dbGoods->checkSize($data['size'][$i]))
			{
				$tag = -6;
				$msg = "规格";
				break;
			}
		}

		//更新数据
		if ($tag > 0)
		{
			if ( $this->serializeAndUpdate("goodsNumArray",$data['num']) &&
				 $this->serializeAndUpdate("goodsSizeArray",$data['size']) &&
				 $this->serializeAndUpdate("goodsMoneyArray",$data['money'])
				)
			{
				$tag = true;
			}
			else
			{
				$this->updateTmpOrderError = "数据库更新失败，请重试";
				$tag = false;
			}
			return $tag;
		}
		else//设置错误信息
		{
			if ($tag >= -3)
				$this->updateTmpOrderError = "第".($i+1)."个商品".$msg."不能为空";
			else if ($tag >= -5)
				$this->updateTmpOrderError = "第".($i+1)."个商品".$msg."不是数字，请重新输入";
			else if ($tag == -6)
				$this->updateTmpOrderError = "第".($i+1)."个商品".$msg."选择出错，请重新选择";
			return false;
		}
	}
	
	/*
	 * 获得出错的updateTmpOrder方法返回的消息
	 * @return updateTmpOrder方法出错返回的消息
	 */
	public function updateTmpOrderGetError()
	{
		return $this->updateTmpOrderError;
	}
	
	/*
	 * 删除购物车内的一条商品
	 * @param	int $No;要删除的商品在tmpOrder中的位置，即序号i
	 * @return	bool；删除是否成功
	 */
	public function deleteFromTmpOrder($No)
	{
		return ( ($this->deleteOne("goodsIDArray",$No)) &&
				 ($this->deleteOne("goodsNumArray",$No)) &&
				 ($this->deleteOne("goodsSizeArray",$No)) &&
				 ($this->deleteOne("goodsMoneyArray",$No))
				);
	}
	
}
?>