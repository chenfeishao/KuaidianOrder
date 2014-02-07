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
    <script src="__PUBLIC__/metro/js/start-screen.js"></script>
    <title>EasyOrder</title>

<script src="__PUBLIC__/checkInput.js"></script>
</head>

<body class="metro">

    <div class="container">
        <h1>
            <a href="<?php echo U("Index/index");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
            	添加商品
        </h1>
        <div class="tile-area no-padding clearfix">
            <div class="grid">
                <div class="tile-group three">
                    <div class="row">
                        <form id="form" method="post" action="<?php echo U("Order/toOneOrder");?>">
                            <fieldset>
                                <label><font color=black>数量</font></label>
                                <div class="input-control text" data-role="input-control">
                                    <input name="num" type="text" autofocus=""">
                                    <button type="button" class="btn-clear" tabindex="1"></button>
                                </div>
                                <label><font color=black>单价</font></label>
                                <div class="input-control text" data-role="input-control">
                                    <input name="money" type="text">
                                    <button type="button" class="btn-clear" tabindex="2"></button>
                                </div>
                                <label><font color=black>规格</font></label>
                                <div class="input-control text" data-role="input-control">
                                    <input name="size" type="text">
                                    <button type="button" class="btn-clear" tabindex="3"></button>
                                </div>
                                <input value="提交" type="submit">
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="tile-group three">
                </div>
            </div>
        </div>
    </div>

</body>
</html>