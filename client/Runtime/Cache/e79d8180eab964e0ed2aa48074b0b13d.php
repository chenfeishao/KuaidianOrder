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

</head>

<body class="metro">

    <div class="container">
        <h1>
            <a href="<?php echo U("Index/goBack");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	结算<small class="on-right">总览</small>
        </h1>
        
      	<form id="form" method="post" action="<?php echo U("Goods/toAdd");?>">
       		<div class="panel">
				<div class="panel-header bg-lightBlue fg-white">
					客户信息<h5 class="fg-white place-right">客户的登录用户名为手机号，登录密码为手机号</h5>
				</div>
				<div class="panel-content grid fluid">
					<div class="row">
						<div class="span3">
							<label><font color=black>客户名称*</font></label>
		                   	<div class="input-control text" data-role="input-control">
		                       <input id="userName" name="name" type="text" tabindex="1" autofocus="" list="userNameList" oninput="getInfo(event);">
		                       <button type="button" class="btn-clear"></button>
		                    </div>
		                </div>
		                <div class="span3">
		                    <label><font color=black>电话*</font></label>
		                    <div class="input-control text" data-role="input-control">
		                       <input id="tel" name="tel" type="number" tabindex="2">
		                       <button type="button" class="btn-clear"></button>
		                    </div>
		                </div>
		                <div class="span6">
		                    <label><font color=black>地址</font></label>
		                    <div class="input-control text" data-role="input-control">
		                       <input id="address" name="address" type="text" tabindex="3">
		                       <button type="button" class="btn-clear"></button>
		                    </div>
		                </div>
		            </div>
		            <div class="row">
		                <div class="span6">
		                    <label><font color=black>停车位置</font></label>
		                    <div class="input-control text" data-role="input-control">
		                       <input id="carAddress" name="carAddress" type="text" tabindex="4">
		                       <button type="button" class="btn-clear"></button>
		                    </div>
		                </div>
		                <div class="span6">
		                    <label><font color=black>车号</font></label>
		                    <div class="input-control text" data-role="input-control">
		                       <input id="carNo" name="carNo" type="text" tabindex="5">
		                       <button type="button" class="btn-clear"></button>
		                    </div>
		                </div>
		            </div>
				</div>
			</div>
			<div class="panel">
				<div class="panel-header bg-darkRed fg-white">
					付款信息<span class="place-right">账单原应收金额:<?php echo ($originJinE); ?></span>
				</div>
				<div class="panel-content grid fluid">
					<div class="row">
		                <div class="span3">
		                    <label><font color=black>优惠金额</font></label>
		                    <div class="input-control text" data-role="input-control">
		                       <input id="save" name="save" type="number" value="0" onclick="$(this).val('');" tabindex="9" oninput="change()">
		                       <button type="button" class="btn-clear"></button>
		                    </div>
		                </div>
		                <div class="span3">
		                    <label><font color=black>应收金额</font></label>
		                    <div class="input-control text" data-role="input-control">
		                       <input id="yingShou" name="yingShou" type="text" value="<?php echo ($originJinE); ?>" disabled="">
		                       <button type="button" class="btn-clear"></button>
		                    </div>
		                </div>
		                <div class="span3">
		                    <label><font color=black>现金实收</font></label>
		                    <div class="input-control text" data-role="input-control">
		                       <input id="xianJinShiShou" name="xianJinShiShou" type="number" tabindex="7" oninput="change()">
		                       <button type="button" class="btn-clear"></button>
		                    </div>
		                </div>
		                <div class="span3">
		                    <label><font color=black>银行实收</font></label>
		                    <div class="input-control text" data-role="input-control">
		                       <input id="yinHangShiShou" name="yinHangShiShou" type="number" tabindex="8" oninput="change()">
		                       <button type="button" class="btn-clear"></button>
		                    </div>
		                </div>
		            </div>
		            <div class="row">
		                <div class="span3">
		                    <h5><font color=black>客户历史欠付款情况总计：<br></font></h5>
		                    <span id="history" class="place-right"><?php echo ($originHistory); ?></span>
		                </div>
		                <div class="span2">
		                    <h5><font color=black>客户本次欠付款：</font></h5>
		                </div>
		                <div class="span3">
		                    <h1 id="benCiQianFuKuan" class="text-warning place-right">0</h1>
		                </div>
		                <div class="span2">
		                    <h5><font color=black>本次实收：</font></h5>
		                </div>
		                <div class="span2">
		                	<h1 id="shiShou" class="text-alert place-right">1000000</h1>
		                </div>
		            </div>
				</div>
			</div>
			<div class="panel">
				<div class="panel-header bg-mauve fg-white">
					出货选择
				</div>
				<div class="panel-content grid fluid">
					<div class="row">
						<button class="span6 shortcut primary" onclick="window.location = '<?php echo U('Goods/add');?>'" data-click="transform">
                            <h2>延迟发货</h2>
                        </button>
                        <button class="span6 shortcut primary" onclick="window.location = '<?php echo U('Goods/edit');?>'" data-click="transform">
                            <h2>立即发货</h2>
                        </button>
		            </div>
				</div>
			</div>
        	<div class="row">
	        	<div class="stepper rounded" data-role="stepper" data-steps="5" data-start="4"></div>
	        </div>
		</form>
    </div>
<datalist id="userNameList">
    <?php if(is_array($userName)): foreach($userName as $key=>$vo): ?><option label="<?php echo ($vo["userPinYin"]); echo ($vo["userName"]); ?>" value="<?php echo ($vo["userName"]); ?>"/><?php endforeach; endif; ?>
</datalist>
</body>
</html>