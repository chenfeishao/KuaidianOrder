<?php
require_once(LIB_PATH."commonModel.php");

class GoodsModel extends ModelBaseOP
{

	// 自动验证设置
	protected $_validate = array(
			array('name','require','商品名称不能为空'),
// 			array('warehouse','require','库存不能为空'),
// 			array('class','require','类别不能为空'),
			array('size','require','规格不能为空'),
			array('oem','require','供货商不能为空'),
			array('style',array(1,4),'瓷片样式不在可选范围内！',0,'between'),
			array('high',array(1,5),'瓷片高度不在可选范围内！',0,'between'),
			array('wide',array(1,5),'瓷片宽度不在可选范围内！',0,'between'),
			array('bgColor',array("","black","white","lime","green","emerald","teal","cyan","cobalt","indigo","violet","pink","magenta","crimson","red","orange","amber","yellow","brown","olive","steel","mauve","taupe","gray","dark","darker","transparent","darkBrown","darkCrimson","darkMagenta","darkIndigo","darkCyan","darkCobalt","darkTeal","darkEmerald","darkGreen","darkOrange","darkRed","darkPink","darkViolet","darkBlue","lightBlue","lightTeal","lightOlive","lightOrange","lightPink","lightRed","lightGreen")
						,'瓷片颜色不在可选范围内！',0,'in'),
			array('brandColor',array("","black","white","lime","green","emerald","teal","cyan","cobalt","indigo","violet","pink","magenta","crimson","red","orange","amber","yellow","brown","olive","steel","mauve","taupe","gray","dark","darker","transparent","darkBrown","darkCrimson","darkMagenta","darkIndigo","darkCyan","darkCobalt","darkTeal","darkEmerald","darkGreen","darkOrange","darkRed","darkPink","darkViolet","darkBlue","lightBlue","lightTeal","lightOlive","lightOrange","lightPink","lightRed","lightGreen")
						,'条带颜色不在可选范围内！',0,'in'),
	);
	
	//自动完成
	protected $_auto = array (
	);
	
	private $goodsID = "";
	
	public function init($id)//传入userName
	{
		$this->goodsID = $id;
	}
	
	/*
	 * 得到解析后的商品信息（用于修改页面的显示）
	 */
	public function getInfo()
	{
		$condition["id"] = $this->goodsID;
		$tmpRe = $this->where($condition)->select();
		$re = $tmpRe[0];
		$tmpRe = null;
		
		$re["warehouse"] = str_replace(_SPECAL_BREAK_FLAG,"；",$re["warehouse"]);
		$re["size"] = str_replace(_SPECAL_BREAK_FLAG,"；",$re["size"]);
		return $re;
	}
	
	public function getGoodsName()
	{
		$condition['id'] = $this->goodsID;
		$tmp = $this->where($condition)->select();
		return $tmp[0]["name"];
	}
	
	/*
	 * 依据表单添加商品
	 * @param	$data;表单的数据
	 * 			int $mode;
	 * 				0为添加新商品
	 * 				1为修改商品
	 * 			int id;当为修改模式的时候，传入修改商品的id
	 * @return	int:
	 * 				true:添加成功
	 * 				false:添加到数据库时数据非法或者查询错误
	 * 				正数:如果是自增主键则返回主键值，否则返回1
	 * 				-1:库存数据格式错误
	 */
	public function addOrEditGoodsFromForm($data,$mode = 0,$id = 0)
	{
		//处理表单数据格式
		//拼音首字母
		$data["pyname"] = getPinYinFirstChar($data["name"]);
		
		//indexNum
		if ($data["indexNum"] == "")
		{
			$data["indexNum"] = 999;
		}
		//warehouse
		if ($data["warehouse"] != "默认仓库：")//用户有输入则处理
		{
			//检查warehouse是不是按格式，且每一个都是数字
			$tmp = explode("；",$data["warehouse"]);
			array_pop($tmp);//把最后一个弹出
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
			
			//把原来的东西写入数据库
			$tmpWarehouse = explode("；",$data["warehouse"]);
			array_pop($tmpWarehouse);//把最后一个弹出
			$data["warehouse"] = $this->transformSpecalBreakTag($tmpWarehouse);
		}
		else
		{
			$data["warehouse"] = "默认仓库：0@#$%^&*";
		}

		//size
		$tmpSize = explode("；",$data["size"]);
		array_pop($tmpSize);//把最后一个弹出
		$data["size"] = $this->transformSpecalBreakTag($tmpSize);
		
		//high and wide
		$highWide = "tile";
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
		
		/*
		 * color
		 */
		$colorArray = array("black","white","lime","green","emerald","teal","cyan","cobalt","indigo","violet","pink","magenta","crimson","red","orange","amber","yellow","brown","olive","steel","mauve","taupe","gray","dark","darker","transparent","darkBrown","darkCrimson","darkMagenta","darkIndigo","darkCyan","darkCobalt","darkTeal","darkEmerald","darkGreen","darkOrange","darkRed","darkPink","darkViolet","darkBlue","lightBlue","lightTeal","lightOlive","lightOrange","lightPink","lightRed","lightGreen");
		//bgColor
		if ( ($data["bgColor"] == "") || ($data["bgColor"] == null) )
		{
			$data["bgColor"] = $colorArray[rand(0,46)];
		}
		//brandColor
		if ( ($data["brandColor"] == "") || ($data["brandColor"] == null) )
		{
			if ($data["style"] == 1)//当它为图片瓷片时，两个颜色不同
			{
				$data["brandColor"] = $colorArray[rand(0,46)];
			}
			else
			{
				$data["brandColor"] = $data["bgColor"];
			}
		}
		
		if ($mode === 0)
			return $this->add($data);
		else
		{
			$data["id"] = $id;
			return $this->save($data);
		}
	}
}
?>