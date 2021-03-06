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
    <title>快点订餐系统——后台</title>

</head>

<body class="metro">

    <div class="container">
        <h1>
            <a href="<?php echo U("Goods/watch");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
            	修改商品
        </h1>
        <div class="tile-area no-padding clearfix">
            <div class="grid">
            	<form id="form" method="post" action="<?php echo U("Goods/toEdit");?>?id=<?php echo ($id); ?>">
                	<div class="tile-group three">
                    	<div class="row">
                           	<legend>商品信息</legend>
                               <label><font color=black>商品名称*</font></label>
                               <div class="input-control text" data-role="input-control">
                                   <input name="name" type="text" autofocus="" value="<?php echo ($name); ?>">
                                   <button type="button" class="btn-clear" tabindex="1"></button>
                                </div>
                                <label><font color=black>库存<small class="on-right"><br>格式：仓库1：10，12；仓库2：20，23；<br>代表仓库1有规格1的10件，规格2的12件，仓库2有规格1的20件，规格2的23件。为了输入方便，请用中文（全角）打冒号、逗号与分号。</small></font></label>
                                <div class="input-control text" data-role="input-control">
                                   <input name="warehouse" type="text" value="<?php echo ($warehouse); ?>">
                                   <button type="button" class="btn-clear" tabindex="2"></button>
                                </div>
                                <label><font color=black>类别</font></label>
                                <div class="input-control text" data-role="input-control">
                                   <input name="class" type="text" value="<?php echo ($class); ?>">
                                   <button type="button" class="btn-clear" tabindex="3"></button>
                                </div>
                                <label><font color=black>规格*<small class="on-right"><br>格式：10斤；20只；<br>代表有2个规格，10斤与20只。为了输入方便，请用中文（全字符）打分号。<br><b>如果没有规格，请不要更改“默认”。（已经预先填入）</b></small></font></label>
                                <div class="input-control text" data-role="input-control">
                                   <input name="size" type="text" value="<?php echo ($size); ?>" onclick='if ($(this).val()=="默认") $(this).val("");'>
                                   <button type="button" class="btn-clear" tabindex="3"></button>
                                </div>
                                <label><font color=black>供货商*<small class="on-right"><br>一个产品一个厂商，如果多个厂商生产同一个产品，请新增一个商品。</small></font></label>
                                <div class="input-control text" data-role="input-control">
                                   <input name="oem" type="text" value="<?php echo ($oem); ?>">
                                   <button type="button" class="btn-clear" tabindex="3"></button>
                                </div>
                        		<div class="row">
                					<input value="提交" type="submit">
                				</div>
                    	</div>
	                </div>
	                <div class="tile-group three">
	                	<div class="row">
		                	<legend>瓷片样式</legend>
		                    <label><font color=black>样式*</font></label>
		                    <div class="input-control select">
								<select name="style">
									<option value="3">普通瓷片</option>
									<option value="1">图片瓷片</option>
									<option value="2">图标瓷片</option>
									<option value="4">介绍瓷片</option>
								</select>
							</div>
		                    <label><font color=black>瓷片高度*</font></label>
		                    <div class="input-control select">
								<select name="high">
									<option value="2">1</option>
									<option value="3">2</option>
									<option value="4">3</option>
									<option value="5">4</option>
								</select>
							</div>
							<label><font color=black>瓷片宽度*</font></label>
		                    <div class="input-control select">
								<select name="wide">
									<option value="2">1</option>
									<option value="3">2</option>
									<option value="4">3</option>
									<option value="5">4</option>
								</select>
							</div>
		                    <label><font color=black>图片</font></label>
		                    <div class="input-control text" data-role="input-control">
		                        <input name="image" type="text" value="<?php echo ($image); ?>">
		                        <button type="button" class="btn-clear" tabindex="3"></button>
		                    </div>
		                    <label><font color=black>瓷片颜色<small class="on-right">填写颜色的英文名称，留空则生成随机颜色。颜色名称参考请<a href="__PUBLIC__/colorWatch.html">点击这里</a></small></font></label>
		                    <div class="input-control text" data-role="input-control">
		                        <input name="bgColor" list="colorDataList" type="text" value="<?php echo ($bgColor); ?>" onclick='$(this).val("");'>
		                        <button type="button" class="btn-clear" tabindex="3"></button>
		                    </div>
		                    <label><font color=black>条带颜色<small class="on-right">填写颜色的英文名称，留空则生成随机颜色。颜色名称参考请<a href="__PUBLIC__/colorWatch.html">点击这里</a>。<br>对于“普通瓷片”样式，建议和瓷片颜色相同</small></font></label>
		                    <div class="input-control text" data-role="input-control">
		                        <input name="brandColor" list="colorDataList" type="text" value="<?php echo ($brandColor); ?>" onclick='$(this).val("");'>
		                        <button type="button" class="btn-clear" tabindex="3"></button>
		                    </div>
		                    <label><font color=black>开始界面中的显示顺序<small class="on-right">一个正整数，数字越小，显示越靠前</small></font></label>
		                    <div class="input-control text" data-role="input-control">
		                        <input name="indexNum" type="number" value="<?php echo ($indexNum); ?>">
		                        <button type="button" class="btn-clear" tabindex="3"></button>
		                    </div>
		                    <label><font color=black>提示信息<small class="on-right">只针对“介绍瓷片”，其他样式此选项无效</small></font></label>
		                    <div class="input-control text" data-role="input-control">
		                        <input name="remark" type="text" value="<?php echo ($remark); ?>">
		                        <button type="button" class="btn-clear" tabindex="3"></button>
		                    </div>
		            	</div>
	                </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>