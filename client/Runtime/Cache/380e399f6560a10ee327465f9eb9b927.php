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
    <script src="__PUBLIC__/metro/js/metro/metro-live-tile.js"></script>

    <!-- Local JavaScript -->
    <script src="__PUBLIC__/metro/js/docs.js"></script>
    <title>EasyOrder</title>

</head>

<body class="metro">

    <div class="container">
        <h1>
            <a href="<?php echo U("Index/index");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	 结算
        </h1>
		<div class="grid">
			<?php if($list == NULL ): ?><b>购物车是空的</b>
   			<?php else: ?>
	            <table class="table hovered">
	                <thead>
		                <tr>
		                	<th class="text-left">序号</th>
		                    <th class="text-left">商品名称</th>
		                    <th class="text-left">数量</th>
		                    <th class="text-left">单价</th>
		                    <th class="text-left">规格</th>
		                    <th class="text-left">金额</th>
		                </tr>
	                </thead>
					
	                <tbody>
	                	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					    		<td style="width: 50px;"><?php echo ($i); ?></td>
					    		<td><?php echo ($vo["goodsName"]); ?></td>
					    		<td class="right span2">
					    			<div class="input-control text" data-role="input-control">
	                                    <input name="num" type="number" tabindex="1" value="<?php echo ($vo["num"]); ?>">
	                                    <button type="button" class="btn-clear"></button>
	                                </div>
					    		</td>
					    		<td class="right span2">
					    			<div class="input-control text" data-role="input-control">
	                                    <input name="money" type="number" tabindex="1" value="<?php echo ($vo["money"]); ?>">
	                                    <button type="button" class="btn-clear"></button>
	                                </div>
								</td>
					    		<td class="right span4">
					    			<div class="input-control text" data-role="input-control">
	                                    <input name="size" type="number" tabindex="1" value="<?php echo ($vo["size"]); ?>">
	                                    <button type="button" class="btn-clear"></button>
	                                </div>
					    		</td>
					    		<td class="right span2">
					    			<div class="input-control text" data-role="input-control">
	                                    <input name="jine" type="number" tabindex="1" value="<?php echo ($vo["jine"]); ?>">
	                                    <button type="button" class="btn-clear"></button>
	                                </div>
								</td>
					    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	                </tbody>
	
	                <tfoot>
	                	<th class="text-left">序号</th>
	                	<th class="text-left">商品名称</th>
	                    <th class="text-left">数量</th>
	                    <th class="text-left">单价</th>
	                    <th class="text-left">规格</th>
	                    <th class="text-left">金额</th>
	                </tfoot>
	            </table><?php endif; ?>
           	<div class="row">
	        </div>
		</div>
    </div>

</body>
</html>