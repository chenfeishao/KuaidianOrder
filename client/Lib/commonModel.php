<?php
require_once(CONF_PATH."MyConfigINI.php");

class ModelBaseOP extends Model
{
	/*
	 * 数据索引id
	 * Note:	需要在继承类中初始化
	 */
	protected $id = null;
	
	/*
	 * 得到原始内容
	 * @param	string $arrayName;要获取的原始内容的字段名称
	 * @return	是否成功
	 */
	public function getOriginArray($arrayName)
	{
		$tmp = _ORIGIN_PREFIX.$arrayName;
		$result = NULL;
		$result = $this->where("id=".$this->id)->select();
		if (!$result)
			return false;
		$this->$tmp = $result[0][$arrayName];
		return true;
	}
	
	/*
	 * 得到一个数组解析后的内容
	 * @param string $arrayName;要解析的原始内容数组的名称(字段名称)
	 * @return array a[i] = 一项
	 */
	public function getArray($arrayName)
	{
		$this->getOriginArray($arrayName);
		$tmp = _ORIGIN_PREFIX.$arrayName;
		$tmp = explode(_SPECAL_BREAK_FLAG, $this->$tmp);
		array_pop($tmp);
		return $tmp;
	}
	
	/*
	 * 序列化数组，即给数组添加中断标记并转换成字符串。（不跟数据库通信）
	* @param	array $data;需要转换的原始数据
	* @return	string;转换完成后的字符串
	*/
	public function transformSpecalBreakTag($data)
	{
		$re = "";
		for ($i = 0; $i < count($data); $i++)
		{
			$re .= $data[$i]._SPECAL_BREAK_FLAG;
		}
		return $re;
	}
	
	/*
	 * 序列化数组，并更新数据库内容
	* @param	string $name;要更新的数据在数据库中的字段名
	* 			array $data;需要转换的原始数据
	* @return	bool；更新是否成功
	*/
	public function serializeAndUpdate($name,$data)
	{
		$condition["id"] = $this->id;
		$condition[$name] = $this->transformSpecalBreakTag($data);
		if ($this->save($condition) === false)//save更新成功后返回的是影响的记录数。会返回0
			return false;
		else
			return true;
	}
	
	/*
	 * 给原始数组后面追加内容。（不跟数据库通信）
	 * @param	string $arrayName;要追加的数组名称(字段名称)
	 * 			string $value;要追加的值
	 * @return	string；追加完成后的string
	 */
	public function appenOne($arrayName,$value)
	{
		$this->getOriginArray($arrayName);
		$tmp = _ORIGIN_PREFIX.$arrayName;
		return $this->$tmp .= $value._SPECAL_BREAK_FLAG;
	}
	
	/*
	 * 删除name字段的第No个数据（与数据库通信）
	 * @param	string $name;字段名称
	 * 			int $No;第多少个数据
	 * @return	bool;删除是否成功
	 */
	public function deleteOne($name,$No)
	{
		$data = $this->getArray($name);
		array_splice($data,$No,1);
		return $this->serializeAndUpdate($name,$data);
	}
}
?>