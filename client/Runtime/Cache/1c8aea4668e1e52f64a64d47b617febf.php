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

<script src="__PUBLIC__/checkInput.js"></script>
 
</head>

<body class="metro">

    <div class="container">
        <h1>
       		<a href="<?php echo U("Finance/index");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	今日销售汇总<small class="on-right"><?php echo (session('userName')); ?>&nbsp<?php echo (session('userPower')); ?></small>
        </h1>
        <div class="tile-area no-padding clearfix">
            <div class="grid">
            	<div class="panel">
					<div class="panel-header bg-indigo fg-white">
						汇总
					</div>
				<div class="panel-content grid fluid">
					<?php if($list == NULL ): ?><b>今日没有完成任何订单</b>
   					<?php else: ?>
                		<table class="table hovered">
			                <thead>
				                <tr>
				                	<th class="text-center">序号</th>
				                	<th class="text-center">商品序号</th>
				                    <th class="text-center">商品名称</th>
				                    <th class="text-center">规格</th>
				                    <th class="text-center">销售总数量</th>
				                    <th class="text-center">平均单价</th>
				                    <th class="text-center">销售总金额</th>
				                </tr>
			                </thead>
							
			                <tbody class="grid fluid text-center">
			                	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							    		<td style="width: 50px;"><?php echo ($i); ?>
							    		</td>
							    		<td class="right span1"><?php echo ($vo["id"]); ?></td>
							    		<td class="span2"><?php echo ($vo["name"]); ?></td>
							    		<td class="span2"><?php echo ($vo["size"]); ?></td>
							    		<td class="right span2"><?php echo ($vo["num"]); ?></td>
							    		<td class="right span2"><?php echo ($vo["price"]); ?></td>
							    		<td class="right span2"><?php echo ($vo["totalPrice"]); ?></td>
							    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			                </tbody>
			
			                <tfoot>
			                	 <tr>
				                	<th class="text-center">序号</th>
				                	<th class="text-center">商品序号</th>
				                    <th class="text-center">商品名称</th>
				                    <th class="text-center">规格</th>
				                    <th class="text-center">销售总数量</th>
				                    <th class="text-center">平均单价</th>
				                    <th class="text-center">销售总金额</th>
				                </tr>
			                </tfoot>
			            </table>
			            <div class="row">
			           		<div class="span1"></div>
				       			<div class="notice span5 marker-on-top fg-white text-center">
				                    <h2 id="totalNum">总数量：<strong><?php echo ($totalNum); ?></strong></h2>
				                </div>
			                <div class="span1"></div>
				                <div class="notice span5 marker-on-top bg-red fg-white text-center">
				                    <h2 id="totalJine">总金额：<strong><?php echo ($totalMoney); ?></strong></h2>
				                </div>
			       		</div><?php endif; ?>
			       </div>
            </div>
        </div>
    </div>

</body>
</html>