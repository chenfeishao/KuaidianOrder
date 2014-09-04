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
           	今日销售汇总<small class="on-right"><?php echo date("Y-m-d");;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo (session('userName')); ?>&nbsp<?php echo (session('userPower')); ?></small>
        </h1>
        <div class="tile-area no-padding clearfix">
            <div class="grid">
            	<div class="panel">
					<div class="panel-header bg-green fg-white">
						营业额
					</div>
					<div class="panel-content grid fluid">
						<div class="row">
							<div class="span3">
			                    <label><font color=black>出货总价：<?php echo ($totalMoney); ?></font></h5>
			                </div>
			                <div class="span3">
			                    <h4><font color=black>现金实收：<?php echo ($xianJinShiShou); ?></font></h4>
			                </div>
			                <div class="span3">
			                    <h4><font color=black>银行实收：<?php echo ($yinHangShiShou); ?></font></h4>
			                </div>
			                <div class="span3">
			                    <label><font color=black>总实收：<?php echo ($xianJinShiShou+$yinHangShiShou); ?></font></h5>
			                </div>
			            </div>
					</div>
				</div>
            	<div class="panel">
					<div class="panel-header bg-indigo fg-white">
						汇总表<small>&nbsp;出货总数：<?php echo ($totalNum); ?></small>
					</div>
				<div class="panel-content grid fluid">
					<?php if($list == NULL ): ?><b>今日没有完成任何订单</b>
   					<?php else: ?>
                		<table class="table hovered">
			                <thead>
				                <tr>
				                	<th class="text-center">序号</th>
				                	<th class="text-center">商品编号</th>
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
				                	<th class="text-center">商品编号</th>
				                    <th class="text-center">商品名称</th>
				                    <th class="text-center">规格</th>
				                    <th class="text-center">销售总数量</th>
				                    <th class="text-center">平均单价</th>
				                    <th class="text-center">销售总金额</th>
				                </tr>
			                </tfoot>
			            </table><?php endif; ?>
			       </div>
            </div>
            <div class="panel">
				<div class="panel-header bg-magenta fg-white">
					往来
				</div>
				<div class="panel-content grid fluid">
					<table id="table_id"  class="table hovered">
					    <thead>
					        <tr>
					            <th class="span2">凭证编号</th>
					            <th>往来账户</th>
					            <th class="span3">日期</th>
					            <th>类型</th>
					            <th>金额</th>
					            <th class="span4">备注</th>
					            <th >经手人</th>
					        </tr>
					    </thead>
					    <tbody>
				        	<?php if(is_array($contactsList)): $i = 0; $__LIST__ = $contactsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
						    		<td style="text-align:center;"><?php echo ($vo["id"]); ?></td>
						    		<td style="text-align:center;"><?php echo ($vo["userName"]); ?></td>
						            <td style="text-align:center;"><?php echo ($vo["createDate"]); ?></td>
						            <td style="text-align:center;"><?php echo ($vo["mode"]); ?></td>
						            <td style="text-align:center;"><?php echo ($vo["money"]); ?></td>
						            <td style="text-align:center;"><?php echo ($vo["remark"]); ?></td>
						            <td style="text-align:center;"><?php echo ($vo["createUser"]); ?></td>
						    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					    </tbody>
					</table>
				</div>
			</div>
            <div class="panel">
				<div class="panel-header bg-lightBlue fg-white">
					更多
				</div>
				<div class="panel-content grid fluid">
					<div class="row">
                        <button class="span6 command-button warning" onclick="window.location = '<?php echo U('Order/history');?>'">
                        	 <h2 >
                        	 <i class="icon-comments-5 on-left"></i>
							查看今日订单</h2>
						</button>
                        <button class="span6 command-button inverse" onclick="window.location = '<?php echo U('Finance/downloadReport');?>'">
                        	 <h2>
                        	 <i class="icon-download-2 on-left"></i>
							下载今日汇总报表</h2>
						</button>
		            </div>
				</div>
			</div>
        </div>
    </div>

</body>
</html>