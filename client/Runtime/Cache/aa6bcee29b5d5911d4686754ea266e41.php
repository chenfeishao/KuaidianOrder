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
    <title>EasyOrder</title>

<script>
var t
function timedCount()
{
	url = "<?php echo U("Order/ajaxGetTmpOrderInfo");?>?no=<?php echo ($id); ?>";
	$.post(url,
		{
		},
		function(data,status)
		{
			//alert(data);
			switch (data)
			{
			case "0":$("#printState").html("目前没有打印该订单");break;
			case "1":$("#printState").html("准备打印存根与发票联");break;
			case "2":$("#printState").html("存根与发票联已经下发给打印机");break;
			case "3":$("#printState").html("打印存根成功，准备打印发票联");break;
			case "4":$("#printState").html("发票联已经下发给打印机");break;
			case "5":$("#printState").html("打印存根与发票联成功，准备打印出库单");break;
			case "6":$("#printState").html("出货单已经下发给打印机");break;
			case "7":
				$("#allText").html('\
						<i class="icon-checkmark"></i>\
						<span id="printState">全部打印完成</span>\
						');break;
			case '101':$("#printState").html("准备打印存根与发票联");break;
			case '105':$("#printState").html("准备打印出货单");break;
			default:$("#printState").html("数据库出错，请重试");break;
			}
		}
	);
	t=setTimeout("timedCount()",1000)
}
</script>
</head>

<body class="metro" onload="timedCount()">

    <div class="container">
        <h1>
            <a href="<?php echo U("Index/goBack_2");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	结果
        </h1>
        
      		<div class="panel">
				<div class="panel-header bg-indigo fg-white">
					重新打印
				</div>
				<div class="panel-content grid fluid">
					<div class="row">
						<h1 id="allText" class="text-center">
							<img src="__PUBLIC__/images/loading.gif"></img>
							<span id="printState">正在检查打印机队列</span>
						</h1>
					</div>
					<div class="row">
						<h1 class="text-center"><a href="<?php echo U("Index/index");?>"><i class="icon-windows"></i></a></h1>
					</div>
				</div>
			</div>
       		
        	<div class="row">
	        	<div class="stepper rounded" data-role="stepper" data-steps="5" data-start="5"></div>
	        </div>
    </div>
</body>
</html>