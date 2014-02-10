<?php
include(CONF_PATH."MyConfigINI.php");
function show_db_errorxx(){
	exit('系统访问量大，请稍等添加数据');
}



/*
 * 判断是否是数字（带小数点的）
 * @param	string $str;要判断的字符串
 * @author	co8bit<me@co8bit.com>
 */
function isNumWithPoint($str)
{
	return preg_match("/^-?\d+(\.\d+)?$/",$str);
}



/*
 * 判断是否是数字（不带小数点的）
* @param	string $str;要判断的字符串
* @author	co8bit<me@co8bit.com>
*/
function isNum($str)
{
	return preg_match("/^[0-9]*$/",$str);
}



/*
 * 给数组添加中断标记并转换成字符串
 * @param	array $data;原始数据
 * @return	string;转换完成后的字符串
 */
function transformWithSpecalBreakTag($data)
{
	$re = "";
	for ($i = 0; $i < count($data); $i++)
	{
		$re .= _SPECAL_BREAK_FLAG.$data[$i];
	}
	return $re;
}

?>