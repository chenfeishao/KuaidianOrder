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

<script>
var newUserTag = true;
function getInfo()
{
	url = "<?php echo U("User/ajaxGetUserInfo");?>";
	$.post(url,
			{
			  name:$("#userName").val()
			},
			function(data,status)
			{
				data = data.split("<?php require_once(CONF_PATH."MyConfigINI.php");echo _AJAX_BREAK_TAG;?>");
			 	if (data[0] == "错误的用户名")//用户名不存在
			 	{
			 		newUserTag = true;
			 		
					$("#tel").val("");
					$("#tel").attr("placeholder","查无此人，输入信息创建新用户");
					$("#historyMoney").html("该账户不存在");
					
					$("#tel").val("").attr("tabindex","2");
			 		$("#address").val("").attr("tabindex","3");
				 	$("#carAddress").val("").attr("tabindex","4");
				 	$("#carNo").val("").attr("tabindex","5");
			 	}
			 	else
			 	{
			 		newUserTag = false;
			 		
			 		$("#tel").val(data[0]).attr("tabindex","999");
			 		$("#address").val(data[1]).attr("tabindex","999");
				 	$("#carAddress").val(data[2]).attr("tabindex","999");
				 	$("#carNo").val(data[3]).attr("tabindex","999");
				 	if (data[4] > 0)
				 	{
				 		$("#historyMoney").html("<b>多</b>（余额）&nbsp;&nbsp;" + data[4] + "元");
				 	}
				 	else if (data[4] == 0)
				 	{
				 		$("#historyMoney").html(data[4] + "元");
				 	}
				 	else if (data[4] < 0)
				 	{
				 		$("#historyMoney").html("<b>欠</b>（我们）&nbsp;&nbsp;" + (0 - data[4]) + "元");	
				 	}
				 	else
				 	{
				 		$("#historyMoney").html("<b>数据错误，请联系软件提供方</b>");	
				 	}
			 	}
			}
		);
}
function blur123()
{
	if ($("#userName").val() == "")
	{
		alert('  用户名为空  这是不合法的输入');
		newUserTag = false;
	}
	if (newUserTag)
	{
		strInfo = $("#userName").val() + '  是新用户，确定要添加吗';
		if(!confirm(strInfo))
		{
			$("#userName").val("");
		}
	}
}
function change()
{
	save = document.getElementById("save");
	originYingShou = <?php echo ($originJinE); ?>;
	originHistory = <?php echo ($originHistory); ?>
	yingShou = document.getElementById("yingShou");
	xianJinShiShou = document.getElementById("xianJinShiShou");
	yinHangShiShou = document.getElementById("yinHangShiShou");
	history = document.getElementById("history");
	benCiQianFuKuan = document.getElementById("benCiQianFuKuan");
	shiShou = document.getElementById("shiShou");
	
	yingShou.value = (Number(originYingShou) - Number(save.value)).toFixed(2);
	shiShouMoney = Number(xianJinShiShou.value) + Number(yinHangShiShou.value);
	shiShou.innerHTML = shiShouMoney.toFixed(2);
	qianFuKuan = (shiShouMoney - Number(yingShou.value)).toFixed(2);
	if (qianFuKuan == 0) 
	{
		$("#benCiQianFuKuan").removeClass("text-warning").removeClass("text-info").removeClass("text-success").addClass('text-info');
		benCiQianFuKuan.innerHTML = "无";
	}
	else if (qianFuKuan > 0)
	{
		$("#benCiQianFuKuan").removeClass("text-warning").removeClass("text-info").removeClass("text-success").addClass('text-success');
		benCiQianFuKuan.innerHTML = "多  " + qianFuKuan;
	}
	else if (qianFuKuan < 0)
	{
		$("#benCiQianFuKuan").removeClass("text-warning").removeClass("text-info").removeClass("text-success").addClass('text-warning');
		benCiQianFuKuan.innerHTML = "少   " + (0 - qianFuKuan);
	}
}
</script>
</head>

<body class="metro">

    <div class="container">
        <h1>
            <a href="<?php echo U("Order/closing");?>"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
           	结算<small class="on-right">相关信息</small>
        </h1>
        
      	<form id="form" method="post" action="<?php echo U("Order/closingOver");?>">
       		<div class="panel">
				<div class="panel-header bg-lightBlue fg-white">
					客户信息<h5 class="fg-white place-right">客户的登录用户名为手机号，登录密码为手机号后3位</h5>
				</div>
				<div class="panel-content grid fluid">
					<div class="row">
						<div class="span3">
							<label><font color=black>客户名称*</font></label>
		                   	<div class="input-control text" data-role="input-control">
		                       <input id="userName" name="userName" type="text" tabindex="1" autofocus="" list="userNameList" oninput="getInfo(event);" onblur="blur123();">
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
		                       <input id="carAddress" name="carAddress" type="text" tabindex="4" list="addressList">
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
		                    <span id="historyMoney" class="place-right">该账户不存在</span>
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
		                	<h1 id="shiShou" class="text-alert place-right">0</h1>
		                </div>
		            </div>
				</div>
			</div>
			
			<div class="grid fluid">
				<div class="row">
					<button class="span12 shortcut primary" data-click="transform">
                         <h2>下单</h2>
                     </button>
	            </div>
			</div>
        	<div class="row">
	        	<div class="stepper rounded" data-role="stepper" data-steps="5" data-start="3"></div>
	        </div>
	        <input name="myTest" value="<?php echo ($myTest); ?>" type="hidden">
		</form>
    </div>
<datalist id="userNameList">
    <?php if(is_array($userName)): foreach($userName as $key=>$vo): ?><option label="<?php echo ($vo["userPinYin"]); echo ($vo["userName"]); ?>" value="<?php echo ($vo["userName"]); ?>"/><?php endforeach; endif; ?>
</datalist>
<datalist id="addressList">
	<option label="x5qd新5千吨" value="新5千吨"/>
	<option label="3qd3千吨" value="3千吨"/>
	<option label="8qd8千吨" value="8千吨"/>
	<option label="4q5bdk4千5百吨库" value="4千5百吨库"/>
	<option label="l5qd老5千吨" value="老5千吨"/>
	<option label="xqtcc西区停车场" value="西区停车场"/>
	<option label="zml中门里" value="中门里"/>
	<option label="tq停前" value="停前"/>
	<option label="dxtccmk地下停车场门口" value="地下停车场门口"/>
	<option label="tdb铁道边（院子里）" value="铁道边（院子里）"/>
	<option label="gdtcc干东停车场" value="干东停车场"/>
	<option label="nhmk农行门口" value="农行门口"/>
	<option label="ghmk工行门口" value="工行门口"/>
	<option label="xmwxb西门外西边" value="西门外西边"/>
	<option label="cwcjdmk城外城酒店门口" value="城外城酒店门口"/>
	<option label="gdqx干东桥下" value="干东桥下"/>
	<option label="gdbqx干东北桥下" value="干东北桥下"/>
	<option label="xqx西桥下" value="西桥下"/>
	<option label="dtkmk大唐库门口" value="大唐库门口"/>
	<option label="dtklm大唐库里面" value="大唐库里面"/>
</datalist>
</body>
</html>