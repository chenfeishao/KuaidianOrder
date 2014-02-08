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
			if (!isNumWithPoint($tmp[$i][1]))
			{
				return -1;
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