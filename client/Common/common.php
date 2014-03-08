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
 * 检查数组内容是否是日期
* @param	array;输入数组
* 				array[0];年
* 				array[1];月
* 				array[2];日
* 				array[3];星期，两位英文缩写
* @return	bool;日期格式是否正确
* @NOTE：目前不检查星期是否正确
*/
function checkIsDate($dateArray)
{
	//是否是数字
	if ( (!isNum($dateArray[0])) || (!isNum($dateArray[1])) || (!isNum($dateArray[2])) )
		return false;
	if (       ($dateArray[0] >= 1900) && ($dateArray[0] <= 3000) 
			&& ($dateArray[1] >= 1) && ($dateArray[1] <= 12)
			&& ($dateArray[2] >= 1) && ($dateArray[2] <= 31)
		)
		return true;
	return false;
}


/*
 * 检查数组内容是否是一组日期
 * @param	array;输入数组
 * 				array[i];一个日期，如：2014-03-21
 * @return	bool;日期格式是否正确
 */
function checkDateArray($dateArray)
{
	for ($i = 0; $i < count($dateArray); $i++)
	{
		$tmp = null;
		sscanf($dateArray[$i],"%d-%d-%d",$tmp[0],$tmp[1],$tmp[2]);
		if (!checkIsDate($tmp))
			return false;
	}
	return true;
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

/*
 * 得到一个汉字的拼音
* @param	char $s0;要转换的中文汉字
* @return	char;中文汉字的拼音首字母
*/
function getfirstchar($s0)
{
	$fchar = ord($s0{0});
	if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
	$s1 = iconv("UTF-8","gb2312", $s0);
	$s2 = iconv("gb2312","UTF-8", $s1);
	if($s2 == $s0){$s = $s1;}else{$s = $s0;}
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
	if($asc >= -20319 and $asc <= -20284) return "A";
	if($asc >= -20283 and $asc <= -19776) return "B";
	if($asc >= -19775 and $asc <= -19219) return "C";
	if($asc >= -19218 and $asc <= -18711) return "D";
	if($asc >= -18710 and $asc <= -18527) return "E";
	if($asc >= -18526 and $asc <= -18240) return "F";
	if($asc >= -18239 and $asc <= -17923) return "G";
	if($asc >= -17922 and $asc <= -17418) return "H";
	if($asc >= -17417 and $asc <= -16475) return "J";
	if($asc >= -16474 and $asc <= -16213) return "K";
	if($asc >= -16212 and $asc <= -15641) return "L";
	if($asc >= -15640 and $asc <= -15166) return "M";
	if($asc >= -15165 and $asc <= -14923) return "N";
	if($asc >= -14922 and $asc <= -14915) return "O";
	if($asc >= -14914 and $asc <= -14631) return "P";
	if($asc >= -14630 and $asc <= -14150) return "Q";
	if($asc >= -14149 and $asc <= -14091) return "R";
	if($asc >= -14090 and $asc <= -13319) return "S";
	if($asc >= -13318 and $asc <= -12839) return "T";
	if($asc >= -12838 and $asc <= -12557) return "W";
	if($asc >= -12556 and $asc <= -11848) return "X";
	if($asc >= -11847 and $asc <= -11056) return "Y";
	if($asc >= -11055 and $asc <= -10247) return "Z";
	return null;
}

/*
 * 得到一串汉字的拼音
 * @param	string $zh;要转换的中文汉字
 * @return	string;中文汉字的拼音首字母
 */
function getPinYinFirstChar($zh)
{
	$ret = "";
	$s1 = iconv("UTF-8","gb2312", $zh);
	$s2 = iconv("gb2312","UTF-8", $s1);
	if($s2 == $zh){$zh = $s1;}
	for($i = 0; $i < strlen($zh); $i++){
		$s1 = substr($zh,$i,1);
		$p = ord($s1);
		if($p > 160){
			$s2 = substr($zh,$i++,2);
			$ret .= chr(ord(getfirstchar($s2)) + 32);
		}else{
			$ret .= $s1;
		}
	}
	return $ret;
}

/*
 * AES算法的CBC模式的加密算法
 * @param	$mode;0加密模式，1解密模式
 * 			$cleartext;待加密的字符串
 * @reurn	string;加密后的字符串
 */
function AES_CBC($mode,$text)
{
	if ($mode === 0)//加密
	{
		$cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, ''); #128位 = 16字节 iv必须16字节
		if (mcrypt_generic_init($cipher, _KEY128, _IV) != -1)
		{
			//如果$cleartext不是128位也就是16字节的倍数，补充NULL字符满足这个条件，返回的结果的长度一样
			$cipherText = mcrypt_generic($cipher,$text);
			mcrypt_generic_deinit($cipher);
		
			//很明显，结果也是16字节的倍数.1个字节用两位16进制表示，所以下面输出的是32的倍数位16进制的字符串
			return bin2hex($cipherText);
		}
	}
	else//解密
	{
		/* Open the cipher */
		$cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		/* Initialize encryption module for decryption */
		if (mcrypt_generic_init($cipher, _KEY128, _IV) != -1)
		{
			/* Decrypt encrypted string */
			$decrypted = mdecrypt_generic($cipher,hex2bin($text));
			/* Terminate decryption handle and close module */
			mcrypt_generic_deinit($cipher);
			mcrypt_module_close($cipher);
			
			/* Show string */
			return $decrypted;
		}
	}
}

class RSA
{
	/**
	 * 公钥加密
	 *
	 * @param string 明文
	 * @param string 证书文件（.crt）
	 * @return string 密文（base64编码）
	 */
	function publickey_encodeing($sourcestr, $fileName)
	{
		$key_content = file_get_contents($fileName);
		$pubkeyid    = openssl_get_publickey($key_content);
	
		if (openssl_public_encrypt($sourcestr, $crypttext, $pubkeyid))
		{
			return base64_encode("".$crypttext);
		}
	}
	
	/**
	 * 私钥解密
	 *
	 * @param string 密文（二进制格式且base64编码）
	 * @param string 密钥文件（.pem / .key）
	 * @param string 密文是否来源于JS的RSA加密
	 * @return string 明文
	 */
	function privatekey_decodeing($crypttext, $fileName, $fromjs = FALSE)
	{
		$key_content = file_get_contents($fileName);
		$prikeyid    = openssl_get_privatekey($key_content);
		$crypttext   = base64_decode($crypttext);
		$padding = $fromjs ? OPENSSL_NO_PADDING : OPENSSL_PKCS1_PADDING;
		if (openssl_private_decrypt($crypttext, $sourcestr, $prikeyid, $padding))
		{
			return $fromjs ? rtrim(strrev($sourcestr), "/0") : "".$sourcestr;
		}
		return ;
	}
}


?>