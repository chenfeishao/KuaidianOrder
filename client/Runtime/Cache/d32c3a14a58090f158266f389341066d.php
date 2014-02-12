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

<script src="__PUBLIC__/checkInput.js"></script>

</head>

<body class="metro">

    <div class="container">
        <h1>
            <a href="<?php echo U("Index/goBack");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	结算<small class="on-right">客户信息</small>
        </h1>
        <div class="tile-area no-padding clearfix">
            <div class="grid">
                <div class="tile-group three">
                    <div class="row">
                        
                    </div>
                </div>
                <div class="tile-group three">
                    <div class="row">
                        
                    </div>
                    
                </div>
                <div class="row">
	        		<div class="stepper rounded" data-role="stepper" data-steps="4" data-start="3"></div>
	        	</div>
            </div>
        </div>
    </div>

</body>
</html>