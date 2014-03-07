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
function getCookie(c_name)
{
    if (document.cookie.length>0)
      {
          var c_start = document.cookie.indexOf(c_name + "=");
          if (c_start != -1)
            {
                c_start = c_start + c_name.length + 1;
                var c_end = document.cookie.indexOf(";",c_start);
                if (c_end == -1) c_end = document.cookie.length;
                return unescape(document.cookie.substring(c_start,c_end));
            }
      }
    return "";
}
function fleshVerify(){ 
    //重载验证码
    var time = new Date().getTime();
        document.getElementById('verifyImg').src= '__APP__/Public/verify/'+time;
}
</script>
<script>
function go(id,k)
{
	if (k == 0)
	{
		url = "<?php echo U("Order/inputPanel");?>" + "?id=" + id;
	}
	else
	{
		url = "<?php echo U("Order/edit");?>" + "?id=" + id;
	}
	window.location = url;
}

$(function(){
    $("#userWindows").on('click', function(){
        if ( (getCookie('userName') == "") || (getCookie('userName') == null) )
        {
            $.Dialog({
                shadow: true,
                overlay: true,
                draggable: true,
                icon: '<span class="icon-windows"></span>',
                title: '用户登录',
                width: 400,
                padding: 10,
                onShow: function(){
                    var content = '<form class="user-input" method="post" action="<?php echo U("User/toLogin");?>">' +
                                '<label>用户名</label>' +
                                '<div class="input-control text"><input type="text" name="userName"><button class="btn-clear"></button></div>' +
                                '<label>密码</label>'+
                                '<div class="input-control password"><input type="password" name="userPassword"><button class="btn-reveal"></button></div>' +
                                '<label>验证码</label>' +
                                '<div class="grid fluid">\
                                	<div class="row">\
	                                	<div class="input-control text">\
	                                		<input type="text" name="yzm">\
	                                		<button class="btn-clear"></button>\
	                                	</div>\
                                	</div>\
                                	<div class="row">\
                                		<img class="span5" id="verifyImg" src="__APP__/Public/verify/" onclick="fleshVerify()"/>\
                                		<label class="span7">看不清？点击图片更换<br><b>注意：区分大小写</b></label>\
                                	</div>\
                                </div>'+
                                '<div class="form-actions">' +
                                '<button class="button primary">登录</button>&nbsp;'+
                                '<button class="button" type="button" onclick="$.Dialog.close()">取消</button> '+
                                '</div>'+
                                '</form>';
                    $.Dialog.content(content);
                }
            });
        }
        else
        {
            window.location='<?php echo U("User/logout");?>';
        }
    });
});
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
        $.Dialog({
            shadow: true,
            overlay: true,
            draggable: true,
            icon: '<span class="icon-cart"></span>',
            title: '详细',
            width: 1000,//还需要改下面html里的宽高
            padding: 10,
            onShow: function(_dialog){
            	url = "<?php echo U("Order/inputPanelIn");?>" + "?id=" + $("#"+$("#quickSelect").val()).attr("src");
                var html = [
                    '<iframe width="1000" height="580" src=\"'+url+'\" frameborder="0" allowfullscreen></iframe>'
                ].join("");
                $.Dialog.content(html);
            }
        });
		
		tag = 0;
		$("#quickSelect").val("");
	}
	else if ( (e.which == 13) && (tag == 0) )
		tag++;
}
</script>
</head>
<body class="metro">
<div class="tile-area tile-area-dark">
    <h1 class="tile-area-title fg-white">欢迎<small>游客登录用户名和密码：youke</small></h1>

    <div class="user-id" id="userWindows">
        <div class="user-id-image">
            <?php if($_SESSION['userName']== NULL ): ?><span class="icon-user no-display1"></span>
			<?php else: ?><img src="__PUBLIC__/metro/images/Battlefield_4_Icon.png"><?php endif; ?>
        </div>
        <div class="user-id-name">
            <span class="first-name">
           		<?php if($_SESSION['userName']== NULL ): ?>未登录
   				<?php else: echo (session('userName')); endif; ?>
    		</span>
            <span class="last-name">
            	<?php if($_SESSION['userPower']== NULL ): else: echo (session('userPower')); endif; ?>
            </span>
        </div>
    </div>

    <div class="tile-group two">
        <div class="tile-group-title">开始</div>

		<div class="tile double ribbed-amber">
            <div class="input-control text span3 place-left margin10" style="margin-left: 10px">
                <input autofocus="" id="quickSelect" name="quick" type="text" list="product" onclick='$(this).val("");' onkeydown="onKeyDownDo(event)">
	        </div>
	        <div class="brand">
	            <div class="label"><h3 class="no-margin fg-white"><span class="icon-search"></span><span class="place-right">快速选择商品</span></h3></div>
	        </div>
        </div>
        
        <a href="<?php echo U("Order/closing");?>" class="tile bg-violet">
            <div class="tile-content icon">
                <span class="icon-basket"></span>
            </div>
            <div class="brand">
                <div class="label">结账</div>
            </div>
        </a>

        <a href="<?php echo U("Order/history");?>" class="tile bg-darkOrange">
            <div class="tile-content icon">
                <span class="icon-history"></span>
            </div>
            <div class="brand">
                <div class="label">历史出货记录</div>
            </div>
        </a>
        
        <a href="<?php echo U("Finance/index");?>" class="tile bg-lightGreen">
            <div class="tile-content icon">
                <span class="icon-book"></span>
            </div>
            <div class="brand">
                <div class="label">财务管理</div>
            </div>
        </a>
    </div> <!-- End group -->



	<?php if($select != NULL): ?><div class="tile-group three">
		   <div class="tile-group-title">购物车</div>
		   
		   <?php if(is_array($select)): foreach($select as $key=>$vo): ?><div class="<?php echo ($vo["className"]); ?> selected"  onclick='go(<?php echo ($vo["id"]); ?>,1)'>
					<div class="<?php echo ($vo["content"]); ?>">
						<?php if($vo["image"] == NULL): ?><span class="icon-tag"></span>
		   				<?php else: ?><img src="<?php echo ($vo["image"]); ?>"/><?php endif; ?>
					</div>
					<div class="<?php echo ($vo["brand"]); ?>">
		                <div class="label"><?php echo ($vo["name"]); ?></div>
		                <div class="badge"><?php echo ($vo["num"]); ?></div>
		            </div>
				</div><?php endforeach; endif; ?>
		
		</div><?php endif; ?>


    <div class="tile-group eleven">
        <div class="tile-group-title">货物清单</div>
        
        <div class="tab-control" data-effect="fade" data-role="tab-control">
		    <ul class="tabs">
		        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="#___<?php echo ($i); ?>"><?php echo ($vo["tabName"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		    </ul>
		
		    <div class="frames">
		    	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="frame" id="___<?php echo ($i); ?>">
			         	<?php if(is_array($vo['goods'])): foreach($vo['goods'] as $key=>$sub): ?><div class="<?php echo ($sub["className"]); ?>"  onclick='go(<?php echo ($sub["id"]); ?>,0)'>
								<div class="<?php echo ($sub["content"]); ?>">
									<?php if($sub["image"] == NULL): ?><span class="icon-tag"></span>
					   				<?php else: ?><img src="<?php echo ($sub["image"]); ?>"/><?php endif; ?>
								</div>
								<div class="<?php echo ($sub["brand"]); ?>">
					                <div class="label"><?php echo ($sub["name"]); ?></div>
					            </div>
							</div><?php endforeach; endif; ?>
						<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
		   	</div>
		</div>
		
    </div> <!-- End group -->
</div>

<datalist id="product">
    <?php if(is_array($productList)): foreach($productList as $key=>$vo): ?><option label="<?php echo ($vo["pyname"]); echo ($vo["name"]); ?>" value="<?php echo ($vo["name"]); ?>" id="<?php echo ($vo["name"]); ?>" src="<?php echo ($vo["id"]); ?>"/><?php endforeach; endif; ?>
</datalist>

</body>
</html>