<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	 
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
            	首页<small class="on-right"><?php echo (session('userName')); ?>&nbsp<?php echo (session('userPower')); ?></small>
            	<small class="element place-right"><a href="<?php echo U("User/logout");?>">退出</a></small>
        </h1>
        <div class="tile-area no-padding clearfix">
            <div class="grid">
                <div class="tile-group seven">
                    <div class="row">
                        <button class="shortcut primary" onclick="window.location = '<?php echo U('Goods/add');?>'" data-click="transform">
                            <h2>添加商品</h2>
                        </button>
                        <button class="shortcut primary" onclick="window.location = '<?php echo U('Goods/edit');?>'" data-click="transform">
                            <h2>修改商品</h2>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>