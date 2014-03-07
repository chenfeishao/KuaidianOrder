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
function goDelete(url)
{
	if(confirm('要删除该交易吗?'))
		location = url;
}
</script>
</head>

<body class="metro" onload="timedCount()">

    <div class="container">
        <h1>
            <a href="<?php echo U("Index/index");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	历史记录
        </h1>
        
      		<div class="panel" data-role="panel">
				<div class="panel-header bg-indigo fg-white">
					未完成订单
				</div>
				<div style="display: block;" class="panel-content">
					<?php if($undoneList == NULL ): ?><b>没有未完成的订单</b>
   					<?php else: ?>
						<table class="table hovered">
			                <thead>
				                <tr>
				                	<th class="text-center">序号</th>
				                    <th class="text-center">顾客名称</th>
				                    <th class="text-center">打印时间</th>
				                    <th class="text-center">商品</th>
				                    <th class="text-center">状态</th>
				                    <th class="text-center">操作</th>
				                </tr>
			                </thead>
							
			                <tbody class="grid fluid text-center">
			                	<?php if(is_array($undoneList)): $i = 0; $__LIST__ = $undoneList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							    		<td style="width: 50px;"><?php echo ($i); ?>
							    		</td>
							    		<td class="span2"><?php echo ($vo["customName"]); ?></td>
							    		<td class="right span2"><?php echo ($vo["createDate"]); ?>
							    		</td>
							    		<td class="right span5"><?php echo ($vo["goodsName"]); ?>
							    		</td>
							    		<td class="right span1"><?php echo ($vo["printState"]); ?>
							    		</td>
										<td style="width: 100px;">
											<a href="<?php echo U("Order/historyOver");?>?no=<?php echo ($vo["id"]); ?>" class="button success">查看</a>
											<div onclick="goDelete('<?php echo U("Order/deleteTmpOrder");?>?no=<?php echo ($vo["id"]); ?>&t=<?php echo ($vo["tkey"]); ?>');" class="button warning">删除</div>
										</td>
							    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			                </tbody>
			
			                <tfoot>
			                	<th class="text-center">序号</th>
			                    <th class="text-center">顾客名称</th>
			                    <th class="text-center">打印时间</th>
			                    <th class="text-center">商品</th>
			                    <th class="text-center">状态</th>
			                    <th class="text-center">操作</th>
			                </tfoot>
			            </table><?php endif; ?>
				</div>
			</div>
			<div class="panel">
				<div class="panel-header bg-green fg-white">
					已完成订单
				</div>
				<div class="panel-content grid fluid">
					<?php if($doneList == NULL ): ?><b>没有已完成的订单</b>
   					<?php else: ?>
						<table class="table hovered">
			                <thead>
				                <tr>
				                	<th class="text-center">序号</th>
				                    <th class="text-center">顾客名称</th>
				                    <th class="text-center">打印时间</th>
				                    <th class="text-center">商品</th>
				                    <th class="text-center">操作</th>
				                </tr>
			                </thead>
							
			                <tbody class="grid fluid text-center">
			                	<?php if(is_array($doneList)): $i = 0; $__LIST__ = $doneList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							    		<td style="width: 50px;"><?php echo ($i); ?>
							    		</td>
							    		<td class="span2"><?php echo ($vo["customName"]); ?></td>
							    		<td class="right span2"><?php echo ($vo["createDate"]); ?>
							    		</td>
							    		<td class="right span5"><?php echo ($vo["goodsName"]); ?>
							    		</td>
										<td style="width: 70px;">
											<a href="<?php echo U("Order/historyOver");?>?no=<?php echo ($vo["id"]); ?>" class="button warning">查看</a>
										</td>
							    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			                </tbody>
			
			                <tfoot>
			                	<th class="text-center">序号</th>
			                    <th class="text-center">顾客名称</th>
			                    <th class="text-center">打印时间</th>
			                    <th class="text-center">商品</th>
			                    <th class="text-center">操作</th>
			                </tfoot>
			            </table><?php endif; ?>
				</div>
			</div>
       		
    </div>
</body>
</html>