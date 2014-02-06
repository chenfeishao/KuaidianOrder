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
    <script src="__PUBLIC__/metro/js/start-screen.js"></script>
    <title>EasyOrder</title>

<script src="__PUBLIC__/checkInput.js"></script>
<script>
function check()
{
	alert("check");
}
</script>  
</head>

<body class="metro">

    <div class="container">
        <h1>
            <a href="/"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
            订单<small class="on-right">南瓜饼</small>
        </h1>
        <div class="tile-area no-padding clearfix">
            <div class="grid">
                <div class="tile-group three">
                    <div class="row">
                        <form>
                            <fieldset>
                                <label><font color=black>数量</font></label>
                                <div class="input-control text" data-role="input-control">
                                    <input id="myInput" type="text" autofocus="" onclick="inputPanel.setNum(0)" onkeydown="return onKeyDownCheckNum(event)">
                                    <button type="button" class="btn-clear" tabindex="1"></button>
                                </div>
                                <label><font color=black>规格</font></label>
                                <div class="input-control text" data-role="input-control">
                                    <input id="myInput" type="text" autofocus="" onclick="inputPanel.setNum(1)" onkeydown="return onKeyDownCheckNum(event)">
                                    <button type="button" class="btn-clear" tabindex="2"></button>
                                </div>
                                <label><font color=black>价格</font></label>
                                <div class="input-control text" data-role="input-control">
                                    <input id="myInput" type="text" onclick="inputPanel.setNum(2)" onkeydown="return onKeyDownCheckNum(event)">
                                    <button type="button" class="btn-clear" tabindex="3"></button>
                                </div>
                                <label><font color=black>总金额</font></label>
                                <div class="input-control text" data-role="input-control">
                                    <input type="text" value="300" disabled="">
                                </div>
                                <input value="提交" type="submit">
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="tile-group three">
                    <div class="row">
                        <button class="shortcut primary" onclick="inputPanel.getKey('7');" data-click="transform">
                            <h1>7</h1>
                        </button>
                        <button class="shortcut primary" onclick="inputPanel.getKey('8');" data-click="transform">
                            <h1>8</h1>
                        </button>
                        <button class="shortcut primary" onclick="inputPanel.getKey('9');" data-click="transform">
                            <h1>9</h1>
                        </button>
                    </div>
                    <div class="row">
                         <button class="shortcut primary" onclick="inputPanel.getKey('4');" data-click="transform">
                            <h1>4</h1>
                        </button>
                        <button class="shortcut primary" onclick="inputPanel.getKey('5');" data-click="transform">
                            <h1>5</h1>
                        </button>
                        <button class="shortcut primary" onclick="inputPanel.getKey('6');" data-click="transform">
                            <h1>6</h1>
                        </button>
                    </div>
                    <div class="row">
                        <button class="shortcut primary" onclick="inputPanel.getKey('1');" data-click="transform">
                            <h1>1</h1>
                        </button>
                        <button class="shortcut primary" onclick="inputPanel.getKey('2');" data-click="transform">
                            <h1>2</h1>
                        </button>
                        <button class="shortcut primary" onclick="inputPanel.getKey('3');" data-click="transform">
                            <h1>3</h1>
                        </button>
                    </div>
                    <div class="row">
                        <button class="shortcut primary" onclick="inputPanel.getKey('0');" data-click="transform">
                            <h1>0</h1>
                        </button>
                        <button class="shortcut primary" onclick="inputPanel.getKey('.');" data-click="transform">
                            <h1>.</h1>
                        </button>
                        <button class="shortcut primary" onclick="inputPanel.getKey('tuige');" data-click="transform">
                            <i style="font-size: 70px;" class="icon-backspace-2"></i>
                        </button>
                    </div>
                    <div class="row">
                        <button class="shortcut primary" onclick="inputPanel.getKey('c');" data-click="transform">
                            <h2>清零</h2>
                        </button>
                        <button class="shortcut primary" onclick="inputPanel.getKey('ok');" data-click="transform">
                            <h4 id="btnValue">下一步</h4>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>


function inputPanel()
{
    this.init = function()
    {
        this.num = 0;
        for (var i = 0; i < document.getElementsByTagName("input").length; i++)
        {
            if (document.getElementsByTagName("input")[i].id != "myInput")
            {
                this.max = i;
                break;
            }
            this.output = document.getElementsByTagName("input")[i];
            this.output.value = "";
        }
    }

    this.setValue = function(input)
    {
        this.output.value += input;
    }

    this.setNum = function(k)
    {
        this.num = k;
        this.output = document.getElementsByTagName("input")[this.num];
        
        //因为点击势必不会再是下一步，所以这里顺便进行变化
        if ( k != (this.max - 1) )
        	document.getElementById("btnValue").innerHTML = "下一步";
    }

    this.getKey = function(input)
    {
        this.output = document.getElementsByTagName("input")[this.num];

        regExpPattern = /\D/g;
        if (input == "tuige")
        {
        	if (this.output.value.length > 0)
        	{
	            if (this.output.value[this.output.value.length - 2] == ".")
	                this.output.value = this.output.value.substring(0,this.output.value.length - 2);
	            else
	                this.output.value = this.output.value.substring(0,this.output.value.length - 1);
        	}
        	else
        		this.output.value = "";
        }
        else if (input == "c")
        {
            this.output.value = "";
        }
        else if (input == "ok")
        {
            if (this.num == this.max - 1)//已经完成输入
            {
                check();
            }
            else if (this.num == this.max - 2)//到最后一步，把“下一步”换成“提交”
            {
                document.getElementById("btnValue").innerHTML = "提交";
            }
            this.num++;
        }
        else if ( (input == ".") || (!regExpPattern.test(input)) )//为数字或者小数点
        {
            this.setValue(input);
        }
    }
}
var inputPanel = new inputPanel();
inputPanel.init();
</script>
</body>
</html>