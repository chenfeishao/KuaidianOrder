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
       		<a href="<?php echo U("Index/index");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	今日销售汇总<small class="on-right"><?php echo (session('userName')); ?>&nbsp<?php echo (session('userPower')); ?></small>
        </h1>
        <div class="tile-area no-padding clearfix">
            <div class="grid">
            	<div class="panel">
					<div class="panel-header bg-indigo fg-white">
						汇总
					</div>
				<div class="panel-content grid fluid">
					<?php if($undoneList == NULL ): ?><b>没有完成的订单</b>
   					<?php else: ?>
                		<table class="table hovered">
			                <thead>
				                <tr>
				                	<th class="text-center">序号</th>
				                    <th class="text-center">商品</th>
				                    <th class="text-center">数量</th>
				                    <th class="text-center">平均单价</th>
				                    <th class="text-center">销售总金额</th>
				                </tr>
			                </thead>
							
			                <tbody class="grid fluid text-center">
			                	<?php if(is_array($undoneList)): $i = 0; $__LIST__ = $undoneList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							    		<td style="width: 50px;"><?php echo ($i); ?>
							    		</td>
							    		<td class="span2"><?php echo ($vo["customName"]); ?></td>
							    		<td class="right span5"><?php echo ($vo["goodsName"]); ?></td>
							    		<td class="right span1"><?php echo ($vo["createDate"]); ?></td>
							    		<td class="right span1"><?php echo ($vo["printDate"]); ?></td>
							    		<td class="right span1"><?php echo ($vo["printState"]); ?></td>
										<td style="width: 100px;">
											<a href="<?php echo U("Order/historyOver");?>?no=<?php echo ($vo["id"]); ?>" class="button success">查看</a>
											<div onclick="goDelete('<?php echo U("Order/deleteTmpOrder");?>?no=<?php echo ($vo["id"]); ?>&t=<?php echo ($vo["tkey"]); ?>');" class="button warning">删除</div>
										</td>
							    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			                </tbody>
			
			                <tfoot>
			                	 <tr>
				                	<th class="text-center">序号</th>
				                    <th class="text-center">商品</th>
				                    <th class="text-center">数量</th>
				                    <th class="text-center">平均单价</th>
				                    <th class="text-center">销售总金额</th>
				                </tr>
			                </tfoot>
			            </table><?php endif; ?>
			       </div>
            </div>
        </div>
    </div>

</body>
</html>