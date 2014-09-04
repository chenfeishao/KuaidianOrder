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
	
	
	
	
	
	 <!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/dataTable/css/jquery.dataTables.css">
	<!-- jQuery -->
	<script type="text/javascript" charset="utf8" src="__PUBLIC__/dataTable/js/jquery.js"></script>
	<!-- DataTables -->
	<script type="text/javascript" charset="utf8" src="__PUBLIC__/dataTable/js/jquery.dataTables.js"></script>
	<script>
		$(document).ready( function () {
		    $('#table_id').DataTable({
		    	"order": [],//定义没有初序（完全靠管理数组书序，不然会自动按第一列值排序）
		    	"pageLength": 10,
		    	"language": {
		    		"sProcessing": "处理中...",
		    		"sLengthMenu": "显示 _MENU_ 项结果",
		    		"sZeroRecords": "没有匹配结果",
		    		"sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
		    		"sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
		    		"sInfoFiltered": "(由 _MAX_ 项结果过滤)",
		    		"sInfoPostFix": "",
		    		"sSearch": "搜索:",
		    		"sUrl": "",
		    		"sEmptyTable": "表中数据为空",
		    		"sLoadingRecords": "载入中...",
		    		"sInfoThousands": ",",
		    		"oPaginate": {
			    		"sFirst": "首页",
			    		"sPrevious": "上页",
			    		"sNext": "下页",
			    		"sLast": "末页"
			    	},
		    		"oAria": {
			    		"sSortAscending": ": 以升序排列此列",
			    		"sSortDescending": ": 以降序排列此列"
			    	}
		    	}
		    });
		} );
	</script>
</head>

<body class="metro">

    <div class="container">
        <h1>
       		<a href="<?php echo U("Finance/summary");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	今日已完成订单<small class="on-right"><?php echo (session('userName')); ?>&nbsp<?php echo (session('userPower')); ?></small>
        </h1>
        <div class="tile-area no-padding clearfix">
        	<?php if($list == NULL ): ?><b>今日未完成任何订单</b>
			<?php else: ?>
	        	<table id="table_id" class="display">
				    <thead>
				        <tr>
				            <th class="span2">凭证编号</th>
				            <th class="span2">日期</th>
				            <th>类型</th>
				            <th>金额</th>
				            <th class="span4">备注</th>
				            <th >经手人</th>
				        </tr>
				    </thead>
				    <tbody>
			        	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					    		<td style="text-align:center;"><?php echo ($vo["id"]); ?></td>
					            <td style="text-align:center;"><?php echo ($vo["createDate"]); ?></td>
					            <td style="text-align:center;"><?php echo ($vo["mode"]); ?></td>
					            <td style="text-align:center;"><?php echo ($vo["money"]); ?></td>
					            <td style="text-align:center;"><?php echo ($vo["remark"]); ?></td>
					            <td style="text-align:center;"><?php echo ($vo["createUser"]); ?></td>
					    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				    </tbody>
				</table><?php endif; ?>
        </div>
    </div>

</body>
</html>