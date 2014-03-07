<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	
	 
    <link href="__PUBLIC__/metro/css/metro-bootstrap.css" rel="stylesheet">
    <link href="__PUBLIC__/metro/css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="__PUBLIC__/metro/css/docs.css" rel="stylesheet">
    <link href="__PUBLIC__/metro/js/prettify/prettify.css" rel="stylesheet">

    <!-- Load JavaScript Libraries -->
    <script src="__PUBLIC__/metro/js/jquery/jquery.min.js"></script>
    <script src="__PUBLIC__/metro/js/jquery/jquery.widget.min.js"></script>
    <script src="__PUBLIC__/metro/js/jquery/jquery.mousewheel.js"></script>
    <script src="__PUBLIC__/metro/js/prettify/prettify.js"></script>

    <!-- Metro UI CSS JavaScript plugins -->
    <script src="__PUBLIC__/metro/js/load-metro.js"></script>

    <!-- Local JavaScript -->
    <script src="__PUBLIC__/metro/js/docs.js"></script>
    <title>快点订单系统</title>

<script>
function change(k)
{
	var numName = "myInputN" + k;
	var moneyName = "myInputM" + k;
	var jineName = "jine" + k;
	num = document.getElementById(numName).value;
	money = document.getElementById(moneyName).value;
	if (num == "") num = 0;
	if (money == "") money = 0;
	document.getElementById(jineName).value = num*money;
	
	totalID = <?php echo ($totalID); ?>;
	var totalNum = 0;
	var totalJine = 0;
	for (var i = 1; i <= totalID; i++)
	{
		var numName = "myInputN" + i;
		var jineName = "jine" + i;
		totalNum += Number(document.getElementById(numName).value);
		totalJine += Math.round(Number(document.getElementById(jineName).value));
	}
	document.getElementById("totalNum").innerHTML = '总数量：<strong>' + totalNum + '</strong>';
	document.getElementById("totalJine").innerHTML = '总金额：<strong>' + totalJine + '</strong>';
}
function clearInfo(k)
{
	var numName = "myInputN" + k;
	var moneyName = "myInputM" + k;
	var jineName = "jine" + k;
	document.getElementById(numName).value = "";
	document.getElementById(moneyName).value = "";
	document.getElementById(jineName).value = "0";
}
function goDelete(url)
{
	if(confirm('要删除该商品吗?'))
		location = url;
}
</script>
</head>

<body class="metro">

    <div class="container">
        <h1>
            <a href="<?php echo U("Index/index");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	 购物车<small>经办人：<?php echo (session('userName')); ?></small>
        </h1>
		<div class="grid">
			<?php if($list == NULL ): ?><b>购物车是空的</b>
   			<?php else: ?>
   				<form id="form" method="post" action="<?php echo U("Order/closingInfo");?>">
		            <table class="table hovered">
		                <thead>
			                <tr>
			                	<th class="text-left">序号</th>
			                    <th class="text-left">商品名称</th>
			                    <th class="text-left">规格</th>
			                    <th class="text-left">数量</th>
			                    <th class="text-left">单价</th>
			                    <th class="text-left">金额</th>
			                    <th class="text-left">操作</th>
			                </tr>
		                </thead>
						
		                <tbody>
		                	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
						    		<td style="width: 50px;"><?php echo ($i); ?>
						    		</td>
						    		<td class="span4"><?php echo ($vo["goodsName"]); ?></td>
						    		<td class="right span3">
						    			<div class="input-control select">
											<select name="size<?php echo ($i); ?>" tabindex="999" onchange="clearInfo(<?php echo ($i); ?>)">
												<?php if(is_array($vo['goodsInfoSize'])): foreach($vo['goodsInfoSize'] as $key=>$sub): if($vo["size"] == $key): ?><option selected="" value="<?php echo ($key); ?>"><?php echo ($sub); ?></option>
												        <?php else: ?><option value="<?php echo ($key); ?>"><?php echo ($sub); ?></option><?php endif; endforeach; endif; ?>
											</select>
										</div>
						    		</td>
						    		<td class="right span2">
						    			<div class="input-control text success-state" data-role="input-control">
		                                    <input id="myInputN<?php echo ($i); ?>" name="num<?php echo ($i); ?>" autofocus="" type="number" tabindex="1" value="<?php echo ($vo["num"]); ?>" oninput="change(<?php echo ($i); ?>);">
		                                    <button type="button" class="btn-clear"></button>
		                                </div>
						    		</td>
						    		<td class="right span2">
						    			<div class="input-control text error-state" data-role="input-control">
		                                    <input id="myInputM<?php echo ($i); ?>" name="money<?php echo ($i); ?>" type="number" tabindex="1" value="<?php echo ($vo["money"]); ?>" oninput="change(<?php echo ($i); ?>);">
		                                    <button type="button" class="btn-clear"></button>
		                                </div>
									</td>
						    		<td class="right span2">
						    			<div class="input-control text info-state" data-role="input-control">
		                                    <input id="jine<?php echo ($i); ?>" name="jine" type="number" tabindex="1" value="<?php echo ($vo["jine"]); ?>" disabled="">
		                                    <button type="button" class="btn-clear"></button>
		                                </div>
									</td>
									<td style="width: 70px;">
										<div onclick="goDelete('<?php echo U("Order/closingDelete");?>?no=<?php echo ($i); ?>&t=<?php echo ($vo["tkey"]); ?>');" class="button warning">删除</div>
									</td>
						    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		                </tbody>
		
		                <tfoot>
		                	<th class="text-left">序号</th>
		                    <th class="text-left">商品名称</th>
		                    <th class="text-left">规格 </th>
		                    <th class="text-left">数量</th>
		                    <th class="text-left">单价</th>
		                    <th class="text-left">金额</th>
		                    <th class="text-left">操作</th>
		                </tfoot>
		            </table>
		           	<div class="row">
		           		<div class="span1"></div>
		       			<div class="notice span6 marker-on-top fg-white text-center">
		                    <h2 id="totalNum">总数量：<strong><?php echo ($totalNum); ?></strong></h2>
		                </div>
		                <div class="span1"></div>
		                <button class="notice span6 marker-on-top bg-red fg-white text-center">
		                    <h2 id="totalJine">总金额：<strong><?php echo ($totalJine); ?></strong></h2>
		                </button>
			        </div>
			        <input name="myTest" value="<?php echo ($myTest); ?>" type="hidden">
				</form><?php endif; ?>
	        <div class="row">
	        	<div class="stepper rounded" data-role="stepper" data-steps="5" data-start="2"></div>
	        </div>
		</div>
    </div>

</body>
</html>