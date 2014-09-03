<?php if (!defined('THINK_PATH')) exit(); if(($mode == 0)): ?><!DOCTYPE html>
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
    <title>快点订单系统</title><?php endif; ?>
<script>
function goDelete(url)
{
	if(confirm('要删除该交易吗?'))
		location = url;
}
</script>
<?php if(($mode == 0)): ?></head>

<body class="metro" onload="timedCount()">

    <div class="container">
        <h1>
            <a href="<?php echo U("Index/index");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	历史记录
           	<a class="place-right" href="<?php echo U("Order/advancedQuery");?>"><i class="icon-search fg-darker smaller"></i></a>
        </h1><?php endif; ?>
      		<div class="panel" data-role="panel">
				<div class="panel-header bg-indigo fg-white">
					<?php if(($mode == 0)): ?>未完成的订单(所有日期)
					<?php else: ?>当天未完成的订单<?php endif; ?>
				</div>
				<div style="display: block;" class="panel-content">
					<?php if($undoneList == NULL ): ?><b>没有未完成的订单</b>
   					<?php else: ?>
						<table class="table hovered">
			                <thead>
				                <tr>
				                	<th class="text-center">序号</th>
				                	<th class="text-center">订单编号</th>
				                    <th class="text-center">顾客名称</th>
				                    <th class="text-center">商品</th>
				                    <th class="text-center">创建时间</th>
				                    <th class="text-center">打印时间</th>
				                    <th class="text-center">状态</th>
				                    <th class="text-center">操作</th>
				                </tr>
			                </thead>
							
			                <tbody class="grid fluid text-center">
			                	<?php if(is_array($undoneList)): $i = 0; $__LIST__ = $undoneList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							    		<td style="width: 50px;"><?php echo ($i); ?>
							    		</td>
							    		<td class="span2"><?php echo ($vo["id"]); ?></td>
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
			            </table><?php endif; ?>
				</div>
			</div>
			<div class="panel">
				<div class="panel-header bg-green fg-white">
					<?php if(($mode == 0)): ?>今天已完成订单
					<?php else: ?>当天完成的订单<?php endif; ?>
				</div>
				<div class="panel-content grid fluid">
					<?php if($doneList == NULL ): ?><b>没有已完成的订单</b><?php endif; ?>
   					<?php if($doneList != NULL ): ?><table class="table hovered">
			                <thead>
				                <tr>
				                	<th class="text-center">序号</th>
				                	<th class="text-center">订单编号</th>
				                    <th class="text-center">顾客名称</th>
				                    <th class="text-center">商品</th>
				                    <th class="text-center">创建时间</th>
				                    <th class="text-center">打印时间</th>
				                    <th class="text-center">操作</th>
				                </tr>
			                </thead>
							
			                <tbody class="grid fluid text-center">
			                	<?php if(is_array($doneList)): $i = 0; $__LIST__ = $doneList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							    		<td style="width: 50px;"><?php echo ($i+$offset); ?></td>
							    		<td class="span2"><?php echo ($vo["id"]); ?></td>
							    		<td class="span2"><?php echo ($vo["customName"]); ?></td>
							    		<td class="right span5"><?php echo ($vo["goodsName"]); ?></td>
							    		<td class="right span1"><?php echo ($vo["createDate"]); ?></td>
							    		<td class="right span1"><?php echo ($vo["printDate"]); ?></td>
										<td style="width: 70px;">
											<a href="<?php echo U("Order/historyOver");?>?no=<?php echo ($vo["id"]); ?>" class="button warning">查看</a>
										</td>
							    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			                </tbody>
			
			                <tfoot>
			                	<th class="text-center">序号</th>
			                	<th class="text-center">订单编号</th>
			                    <th class="text-center">顾客名称</th>
			                    <th class="text-center">商品</th>
			                    <th class="text-center">创建时间</th>
			                    <th class="text-center">打印时间</th>
			                    <th class="text-center">操作</th>
			                </tfoot>
			            </table>
			            <div class="pagination">
                           <ul>
                               	<?php
 if ($prePage != '') { if ($mode == 0) { echo "<li class='first'><a href='".$theFirst."'><i class='icon-first-2'></i></a></li>"; echo "<li class='prev'><a href='".$prePage."'><i class='icon-previous'></i></a></li>"; } else { echo "<li class='first' onclick='changePage(this);' id='".$theFirst."'><a><i class='icon-first-2'></i></a></li>"; echo "<li class='prev' onclick='changePage(this);' id='".$prePage."'><a><i class='icon-previous'></i></a></li>"; } } else { if ($mode == 0) { echo "<li class='first disabled'><a href='".$theFirst."'><i class='icon-first-2'></i></a></li>"; echo "<li class='prev disabled'><a href='".$prePage."'><i class='icon-previous'></i></a></li>"; } else { echo "<li class='first disabled' id='".$theFirst."' onclick='changePage(this);'><a><i class='icon-first-2'></i></a></li>"; echo "<li class='prev disabled' id='".$prePage."' onclick='changePage(this);'><a><i class='icon-previous'></i></a></li>"; } } ?>
                               	<?php echo ($linkPage); ?>
                               	<?php
 if ($nextPage != '') { echo "<li class='spaces'><a>...</a></li>"; if ($mode == 0) { echo "<li><a href='".$theEnd."'>".$totalPages."</a>"; echo "<li class='next'><a href='".$nextPage."'><i class='icon-next'></i></a></li>"; echo "<li class='last'><a href='".$theEnd."'><i class='icon-last-2'></i></a></li>"; } else { echo "<li id='".$theEnd."' onclick='changePage(this);'><a>".$totalPages."</a>"; echo "<li class='next' id='".$nextPage."' onclick='changePage(this);'><a><i class='icon-next'></i></a></li>"; echo "<li class='last' id='".$theEnd."' onclick='changePage(this);'><a><i class='icon-last-2'></i></a></li>"; } } else { if ($mode == 0) { echo "<li class='next disabled'><a href='".$nextPage."'><i class='icon-next'></i></a></li>"; echo "<li class='last disabled'><a href='".$theEnd."'><i class='icon-last-2'></i></a></li>"; } else { echo "<li class='next disabled' id='".$nextPage."' onclick='changePage(this);'><a><i class='icon-next'></i></a></li>"; echo "<li class='last disabled' id='".$theEnd."' onclick='changePage(this);'><a><i class='icon-last-2'></i></a></li>"; } } ?>
                         </ul>
                       </div><?php endif; ?>
				</div>
			</div>
<?php if(($mode == 0)): ?></div>
</body>
</html><?php endif; ?>

<?php ?>