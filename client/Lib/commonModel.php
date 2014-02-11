<?php
include(CONF_PATH."MyConfigINI.php");

class ModelBaseOP extends Model
{
	/*
	 * 数据索引id
	 * Note:	需要在继承类中初始化
	 */
	protected $id = "";
	
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
		$st = 0;
		$count = 0;
		$contentLen = strlen($this->$tmp);
		$this->$arrayName = "";
		while ($st < $contentLen)
		{
			$breakPoint = strpos($this->$tmp,_SPECAL_BREAK_FLAG,$st);
			if (!$breakPoint)//到字符串最后内容了
			{
				break;
			}
			$this->$arrayName[$count] = substr($this->$tmp,$st,$breakPoint - $st);
			$count++;
			$st = $breakPoint + strlen(_SPECAL_BREAK_FLAG);
		}
	
		return $this->$arrayName;
	}
	
	/*
	 * 序列化数组，即给数组添加中断标记并转换成字符串。
	* @param	array $data;原始数据
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
}
?>