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
           	历次汇总<small>当前账户：<?php echo (session('userName')); ?></small>
        </h1>
        <div class="tile-area no-padding clearfix">
	        	<div class="row">
                      <form id="form" method="post" action="<?php echo U("Finance/summary");?>">
                          <fieldset>
                          	<label><font color=black>选择要查看汇总表的日期</font></label>
                              <div id="date" class="input-control text" data-role="datepicker" data-week-start="1" 
			                  		data-locale="zhCN"  data-format="yyyy-m-d"  data-effect="slide">
		                           <input id="date" type="text" name="date" value="<?php echo ($dateDisplay); ?>">
		                           <button class="btn-date"></button>
		                       </div>
                              <input value="查询" type="submit">
                              <br><br><br><br><br><br><br><br><br>
                          </fieldset>
                      </form>
                  </div>
        </div>
    </div>

</body>
</html>