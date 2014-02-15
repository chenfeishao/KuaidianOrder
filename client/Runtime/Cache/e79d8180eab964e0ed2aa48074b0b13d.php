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
            <a href="<?php echo U("Index/goBack");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	结算<small class="on-right">确认</small>
        </h1>
        
      		<div class="panel">
				<div class="panel-header bg-indigo fg-white">
					商品信息
					<small class="place-right">应收货款:<?php echo ($totalJinE); ?></small>
				</div>
				<div class="panel-content grid fluid">
					<table class="table hovered">
		                <thead>
			                <tr>
			                	<th class="text-left">序号</th>
			                    <th class="text-left">商品名称</th>
			                    <th class="text-left">规格</th>
			                    <th class="text-left">数量</th>
			                    <th class="text-left">单价</th>
			                    <th class="text-left">金额</th>
			                </tr>
		                </thead>
						
		                <tbody>
		                	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
						    		<td style="width: 50px;"><?php echo ($i); ?></td>
						    		<td class="span4"><?php echo ($vo["goodsName"]); ?></td>
						    		<td class="right span3"><?php echo ($vo["size"]); ?></td>
						    		<td class="right span2"><?php echo ($vo["num"]); ?></td>
						    		<td class="right span2"><?php echo ($vo["money"]); ?></td>
						    		<td class="right span2"><?php echo ($vo["jinE"]); ?></td>
						    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		                </tbody>
		            </table>
				</div>
			</div>
       		<div class="panel">
				<div class="panel-header bg-lightBlue fg-white">
					客户信息<small><?php echo ($customName); ?></small>
					<small class="place-right">电话：<?php echo ($tel); ?></small>
				</div>
				<div class="panel-content grid fluid">
					<div class="row">
		                <div class="span4">
		                    <label><font color=black>地址：<?php echo ($address); ?></font></label>
		                </div>
		                <div class="span5">
		                    <label><font color=black>停车位置：<?php echo ($carAddress); ?></font></label>
		                </div>
		                <div class="span3">
		                    <label><font color=black>车号：<?php echo ($carNo); ?></font></label>
		                </div>
		            </div>
				</div>
			</div>
			<div class="panel">
				<div class="panel-header bg-darkRed fg-white">
					付款信息<small>优惠金额：<?php echo ($save); ?></small>
					<small class="place-right">优惠后应收金额：<?php echo ($yingShouJinE); ?></small>
				</div>
				<div class="panel-content grid fluid">
					<div class="row">
		                <div class="span3">
		                    <label><font color=black>现金实收：<?php echo ($xianJinShiShou); ?></font></label>
		                </div>
		                <div class="span3">
		                    <label><font color=black>银行实收：<?php echo ($yinHangShiShou); ?></font></label>
		                </div>
		                <div class="span3">
		                    <h5><font color=black>本次实收：<?php echo ($benCiShiShou); ?></font></h5>
		                </div>
		                <div class="span3">
		                    <h5><font color=black>客户本次欠付款：<?php echo ($benCiQianFuKuan); ?></font></h5>
		                </div>
		            </div>
				</div>
			</div>
			<div class="panel">
				<div class="panel-header bg-green fg-white">
					出货选择
				</div>
				<div class="panel-content grid fluid">
					<div class="row">
						<button class="span6 shortcut primary" onclick="window.location = '<?php echo U('Goods/add');?>'" data-click="transform">
                            <h2>延迟发货</h2>
                        </button>
                        <button class="span6 shortcut primary" onclick="window.location = '<?php echo U('Goods/edit');?>'" data-click="transform">
                            <h2>立即发货</h2>
                        </button>
		            </div>
				</div>
			</div>
        	<div class="row">
	        	<div class="stepper rounded" data-role="stepper" data-steps="5" data-start="4"></div>
	        </div>
    </div>
</body>
</html>