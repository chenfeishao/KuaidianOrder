<?php
class GoodsModel extends Model {

	// 自动验证设置
	protected $_validate = array(
			array('name','require','商品名称不能为空'),
			array('warehouse','require','库存不能为空'),
			array('class','require','类别不能为空'),
			array('size','require','规格不能为空'),
			array('oem','require','供货商不能为空'),
			array('bgColor','require','瓷片颜色不能为空'),
			array('brandColor','require','条带颜色不能为空'),
			array('style',array(1,4),'瓷片样式不在可选范围内！',0,'between'),
			array('high',array(1,5),'瓷片高度不在可选范围内！',0,'between'),
			array('wide',array(1,5),'瓷片宽度不在可选范围内！',0,'between'),
			array('bgColor',array("black","white","lime","green","emerald","teal","cyan","cobalt","indigo","violet","pink","magenta","crimson","red","orange","amber","yellow","brown","olive","steel","mauve","taupe","gray","dark","darker","transparent","darkBrown","darkCrimson","darkMagenta","darkIndigo","darkCyan","darkCobalt","darkTeal","darkEmerald","darkGreen","darkOrange","darkRed","darkPink","darkViolet","darkBlue","lightBlue","lightTeal","lightOlive","lightOrange","lightPink","lightRed","lightGreen")
						,'瓷片颜色不在可选范围内！',0,'in'),
			array('brandColor',array("black","white","lime","green","emerald","teal","cyan","cobalt","indigo","violet","pink","magenta","crimson","red","orange","amber","yellow","brown","olive","steel","mauve","taupe","gray","dark","darker","transparent","darkBrown","darkCrimson","darkMagenta","darkIndigo","darkCyan","darkCobalt","darkTeal","darkEmerald","darkGreen","darkOrange","darkRed","darkPink","darkViolet","darkBlue","lightBlue","lightTeal","lightOlive","lightOrange","lightPink","lightRed","lightGreen")
						,'条带颜色不在可选范围内！',0,'in'),
	);
	
	private $goodsID = "";
	
	private function getfirstchar($s0)
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
	
	private function getPinYinFirstChar($zh)
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
				$ret .= chr(ord($this->getfirstchar($s2)) + 32);
			}else{
				$ret .= $s1;
			}
		}
		return $ret;
	}
	
	public function init($id)//传入userName
	{
		$this->goodsID = $id;
	}
	
	public function getGoodsName()
	{
		$condition['id'] = $this->goodsID;
		return $this->where($condition)->select()[0]["name"];
	}
	
	/*
	 * 依据表单添加商品
	 * @param	$data;表单的数据
	 * @return	int:
	 * 				true:添加成功
	 * 				false:添加到数据库时数据非法或者查询错误
	 * 				正数:如果是自增主键则返回主键值，否则返回1
	 * 				-1:库存数据格式错误
	 */
	public function addGoodsFromForm($data)
	{
		//处理表单数据格式
		//拼音首字母
		$data["pyname"] = $this->getPinYinFirstChar($data["name"]);
		
		//indexNum
		if ($data["indexNum"] == "")
		{
			$data["indexNum"] = 999;
		}
		//warehouse
		$tmp = explode("；",$data["warehouse"]);
		for ($i = 0; $i < count($tmp); $i++)
		{
			$tmp[$i] = explode("：",$tmp[$i]);
			$tmp[$i][1] = explode("，",$tmp[$i][1]);
			for ($j = 0; $j < count($tmp[$i][1]); $j++)
			{
				if (!isNumWithPoint($tmp[$i][1][$j]))
				{
					return -1;
				}
			}
		}
		$data["warehouse"] = transformWithSpecalBreakTag(explode("；",$data["warehouse"]));

		//size
		$data["size"] = transformWithSpecalBreakTag(explode("；",$data["size"]));
		
		//high and wide
		$highWide = "title";
		switch ($data["wide"])
		{
			case 1:$highWide .= " half";break;
			default:
			case 2:break;
			case 3:$highWide .= " double";break;
			case 4:$highWide .= " triple";break;
			case 5:$highWide .= " quadro";break;
		}
		switch ($data["high"])
		{
			case 1:$highWide .= " half-vertical";break;
			default:
			case 2:break;
			case 3:$highWide .= " double-vertical";break;
			case 4:$highWide .= " triple-vertical";break;
			case 5:$highWide .= " quadro-vertical";break;
		}
		$data["highWide"] = $highWide;
		
		return $this->add($data);
	}
}
?>