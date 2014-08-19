<?php
class FinanceModel extends Model {

	/**
	 * 创建一条记录
	 * @param  $id；用户表的主键
	 * @param  $money；金额
	 * @param  $remark；备注
	 * @param  $mode；模式，0是应收款，1是应付款
	 * @return	bool;是否成功
	 */
	public function newFinance($id,$money,$remark,$mode)
	{
		return $this->add(array("userID"=>$id,"money"=>$money,"remark"=>$remark,"mode"=>$mode,"createDate"=>date("Y-m-d H:i:s")));
	}
}
?>