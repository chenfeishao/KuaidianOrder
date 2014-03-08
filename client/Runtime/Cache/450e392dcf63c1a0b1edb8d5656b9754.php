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
    <script src="/easyOrder/Public/metro/js/load-metro.js"></script>

    <title>快点订单系统</title>
</head>
<body class="metro">
   <div class="example1">
                    <div class="grid">
                        <div class="row">
                            <div class="span4"><div class="calendar small" data-role="calendar" ></div></div>
                            <div class="span4"><div class="calendar small" data-role="calendar" data-start-mode="month"></div></div>
                            <div class="span4"><div class="calendar small" data-role="calendar" data-start-mode="year"></div></div>
                        </div>
                    </div>
                </div>
                <div class="example1">
                    <div class="grid">
                        <div class="row">
                            <div class="span4">
                                <div class="calendar" id="cal-events"></div>
                            </div>
                            <div class="span5">
                                <h3 class="no-margin">Output for getDates</h3>
                                <hr />
                                <div id="calendar-output"></div>

                                <br />
                                <h3 class="no-margin">Output for click</h3>
                                <hr />
                                <div id="calendar-output2"></div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(function(){
                            var cal = $("#cal-events").calendar({
                                multiSelect: true,
                                getDates: function(data){
                                    var r = "", out = $("#calendar-output").html("");
                                    $.each(data, function(i, d){
                                        r += d + "<br />";
                                    });

                                    out.html(r);
                                },

                                click: function(d){
                                    var out = $("#calendar-output2").html("");
                                    out.html(d);
                                }


                            });

                            cal.calendar('setDate', '2013-10-21');
                            cal.calendar('setDate', '2013-10-2');
                        })
                    </script>
                </div>

</body>
</html>