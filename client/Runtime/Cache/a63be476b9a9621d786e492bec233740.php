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

<script src="http://easyorder.sinaapp.com/Public/metro/js/metro.min.js"></script>
<script>
$(function(){
    var cal = $("#cal-events").calendar({
        multiSelect: true,
        getDates: function(data){
            var r = "", out = $("#calendar-output").html("");
            $.each(data, function(i, d){
                r += d + "<br />";
            });
            out.html(r);
        }
    });
})

function intervalButton(m)
{
	url = "<?php echo U("Order/ajaxAdvancedQueryInterval");?>";
	$.post(url,
			{
				mode:m,
				startDate:document.getElementById("startDate").value,
		  		endDate:document.getElementById("endDate").value
			},
			function(data,status)
			{
				$("#intervalBlock").attr("style","display: none;");
				$("#resultBlock").attr("style","display:block;").html(data);
			}
		);
}
</script>
</head>

<body class="metro">

    <div class="container">
        <h1>
       		<a href="<?php echo U("Order/history");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	历史记录高级查询<small class="on-right"><?php echo (session('userName')); ?>&nbsp<?php echo (session('userPower')); ?></small>
        </h1>
        <div class="grid">
           <div class="accordion span12 place-left margin10" data-role="accordion">
              <div class="accordion-frame">
                  <a class="heading bg-cyan fg-white" href="#"><h3 class="fg-white"><i class="icon-calendar"></i>区间查询</h3></a>
                  <div id="intervalBlock" style="display: block;" class="content">
					<div class="center-text">从
	                   <div id="start" class="span3 input-control text" data-role="datepicker" data-week-start="1" 
	                  		data-locale="zhCN" data-format="yyyy年m月d日,星期dddd" data-effect="slide">
                           <input id="startDate" type="text">
                           <button class="btn-date"></button>
                       </div>
					  	到
					  <div class="span3 input-control text" data-role="datepicker" data-week-start="1" 
	                  		data-locale="zhCN" data-format="yyyy年m月d日,星期dddd" data-effect="slide">
                           <input id="endDate" type="text">
                           <button class="btn-date"></button>
                       </div>
                       <div class="span2 button large primary" onclick="intervalButton(1);">查询</div>
                       <div class="span3 button large primary" onclick="intervalButton(2);">查询(按打印时间)</div>
                 	</div>
                 </div>
              </div>
              <div class="accordion-frame">
                  <a class="heading bg-cyan fg-white collapsed" href="#"><h3 class="fg-white"><i class="icon-location"></i>多点查询</h3></a>
                  <div style="display: none;" class="content">
                        <div class="row">
                            <div class="span5">
                                <div class="calendar" id="cal-events"></div>
                            </div>
                            <div class="span6">
                                <div class="row">
                                	<h3 class="no-margin">已选择的日期</h3>
                                	<div id="calendar-output"></div>
                                </div>
                                <div class="row">
                                	<button class="span6 large primary">查询</button>
                                </div>
                            </div>
                        </div>
                  </div>
              </div>
              <div class="accordion-frame">
                  <a class="heading bg-cyan fg-white" href="#"><h3 class="fg-white"><i class="icon-printer"></i>结果</h3></a>
                  <div id="resultBlock" style="display: none;" class="content">
                  	
                  </div>
              </div>
          </div>
        </div>
    </div>

</body>
</html>