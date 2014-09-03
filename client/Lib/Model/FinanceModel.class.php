<?php
class FinanceModel extends Model {

	/**
	 * 创建一条记录
	 * @param  $id；给哪一个账户创建这条记录。用户表的主键
	 * @param  $money；金额
	 * @param  $remark；备注
	 * @param  $mode；模式，0是应收款，1是应付款，2是费用
	 * 	@param	$createUser;经手人
	 * @param	$dateInfo;日期信息，默认为创建的当前时刻；如果被传入日期，则是传入的日期
	 * @param	$byOrderID;是由哪个订单产生的，如果不是由订单产生则为0，否则为订单编号
	 * @return	bool;是否成功
	 */
	public function newFinance($id,$money,$remark,$mode,$createUser,$dateInfo = NULL,$byOrderID = -1)
	{
		if ($dateInfo === NULL)
			$dateInfo = date("Y-m-d H:i:s");
		return $this->add(array("userID"=>$id,"money"=>$money,"remark"=>$remark,"mode"=>$mode,"createUser"=>$createUser,"createDate"=>$dateInfo,"byOrderID"=>$byOrderID));
	}
}
?>