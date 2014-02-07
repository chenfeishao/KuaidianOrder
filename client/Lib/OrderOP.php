<?php
include(CONF_PATH."MyConfigINI.php");

Class OrderOP extends Model
{	
	protected $id = "";
	protected $originGoodsIDArray = "";
	protected $originGoodsNumArray = "";
	protected $originGoodsSizeArray = "";
	protected $originGoodsMoneyArray = "";
	protected $goodsIDArray = "";
	protected $goodsNumArray = "";
	protected $goodsSizeArray = "";
	protected $goodsMoneyArray = "";	
	
	public function getOriginArray()
	/*
	 * 得到原始内容
	 * @return 是否成功
	 */
	{
		$result = NULL;
		$result = $this->where("id=".$this->id)->select();
		if (!$result)
			return false;
		$this->originGoodsIDArray = $result[0]["goodsIDArray"];
		$this->originGoodsNumArray = $result[0]["goodsNumArray"];
		$this->originGoodsSizeArray = $result[0]["goodsSizeArray"];
		$this->originGoodsMoneyArray = $result[0]["goodsMoneyArray"];
		return true;
	}
	
	public function getContentOne($orignArray)
	/*
	 * 得到一个数组解析后的内容
	 * @param array $orignArray;要解析的原始内容数组
	 * @return array; a[i] = 一项
	 */
	{
		$st = 0;
		$count = 0;
		$contentLen = strlen($orignArray);
		while ($st < $contentLen)
		{
			$breakPoint = strpos($orignArray,_SPECAL_BREAK_FLAG,$st);
			if ($breakPoint == 0)//开头
			{
				$st = $breakPoint + strlen(_SPECAL_BREAK_FLAG);
				continue;
			}
			if (!$breakPoint)//到字符串最后一个内容了
			{
				$this->content[$count] = substr($orignArray,$st);
				break;
			}
			$this->content[$count] = substr($orignArray,$st,$breakPoint - $st);
			$count++;
			$st = $breakPoint + strlen(_SPECAL_BREAK_FLAG);
		}
	
		return $this->content;
	}
	
	public function getContent()
	{
		$this->getOriginArray();
		
		$this->goodsIDArray = $this->getContentOne($this->originGoodsIDArray);
		$this->goodsNumArray = $this->getContentOne($this->originGoodsNumArray);
		$this->goodsSizeArray = $this->getContentOne($this->originGoodsSizeArray);
		$this->goodsMoneyArray = $this->getContentOne($this->originGoodsMoneyArray);
	}
	
	public function insert($goodsID,$num,$size,$money)
	{
		$this->getOriginArray();
		
		$this->originGoodsIDArray .= _SPECAL_BREAK_FLAG . $goodsID;
		$this->originGoodsNumArray .= _SPECAL_BREAK_FLAG . $num;
		$this->originGoodsSizeArray .= _SPECAL_BREAK_FLAG . $size;
		$this->originGoodsMoneyArray .= _SPECAL_BREAK_FLAG . $money;
		$data["id"] = $this->id;
		$data["goodsIDArray"] = $this->originGoodsIDArray;
		$data["goodsNumArray"] = $this->originGoodsNumArray;
		$data["goodsSizeArray"] = $this->originGoodsSizeArray;
		$data["goodsMoneyArray"] = $this->originGoodsMoneyArray;
		return $this->save($data);
	}
}
?>