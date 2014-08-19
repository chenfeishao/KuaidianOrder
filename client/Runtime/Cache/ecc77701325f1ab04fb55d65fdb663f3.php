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

<script src="__PUBLIC__/metro/js/start-screen.js"></script>
<script>
var tag = 0;
function onKeyDownDo(e)
{
	var input;
	
	if(window.event) // IE
    {
    	input = e.keyCode;
    }
	else if (e.which) // Netscape/Firefox/Opera
    {
		input = e.which;
    }
	
	if ( (e.which == 13) && (tag == 1) )
	{
		url = "<?php echo U("Finance/ardo");?>" + "?id=" + $("#"+$("#quickSelect").val()).attr("src");
		
		tag = 0;
		$("#quickSelect").val("");
		
		window.location = url;
	}
	else if ( (e.which == 13) && (tag == 0) )
		tag++;
}
</script>
</head>
<body class="metro">
<div class="tile-area tile-area-dark">
    <h1 class="tile-area-title fg-white">
    	<a href="<?php echo U("Finance/index");?>"><i class="icon-arrow-left-3 fg-white smaller"></i></a>
    	选择账户
    </h1>

    

    <div class="tile-group two">
        <div class="tile-group-title">开始</div>

		<div class="tile double ribbed-amber">
            <div class="input-control text span3 place-left margin10" style="margin-left: 10px">
                <input autofocus="" id="quickSelect" name="quick" type="text" list="product" onclick='$(this).val("");' onkeydown="onKeyDownDo(event)">
	        </div>
	        <div class="brand">
	            <div class="label"><h3 class="no-margin fg-white"><span class="icon-search"></span><span class="place-right">快速选择</span></h3></div>
	        </div>
        </div>
    </div> <!-- End group -->

    <div class="tile-group eleven">
        <div class="tile-group-title">用户</div>
        
        <div class="tab-control" data-effect="fade" data-role="tab-control">
		    <ul class="tabs">
		        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="#___<?php echo ($i); ?>"><?php echo ($vo["tabName"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		    </ul>
		
		   	<div class="frames">
		    	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="frame" id="___<?php echo ($i); ?>">
			         	<?php if(is_array($vo['account'])): foreach($vo['account'] as $key=>$sub): if(($i) == "value"): ?>value<?php endif; ?><!-- 没有意义，但是刚好可以让方块中间空开，所以就加上了 -->
							<a class="<?php echo ($sub["class"]); ?>"  href="<?php echo U("Finance/ardo");?>?id=<?php echo ($sub["name"]); ?>" data-click="transform">
                            	<h2><?php echo ($sub["name"]); ?></h2>
        					</a><?php endforeach; endif; ?>
						<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
		   	</div>
		</div>
    </div> <!-- End group -->
</div>

<datalist id="product">
    <?php if(is_array($accountList)): foreach($accountList as $key=>$vo): ?><option label="<?php echo ($vo["pyname"]); echo ($vo["name"]); ?>" value="<?php echo ($vo["name"]); ?>" id="<?php echo ($vo["name"]); ?>" src="<?php echo ($vo["name"]); ?>"/><?php endforeach; endif; ?>
</datalist>

</body>
</html>