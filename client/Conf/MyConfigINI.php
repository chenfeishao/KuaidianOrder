<?php
define("_SPECAL_BREAK_FLAG","@#$%^&*");
define("_ORIGIN_PREFIX","origin_");//原始数组的名称前缀。commonModel中ModelBaseOP用
define("_AJAX_BREAK_TAG","~;^");//AJAX输出时候的控制终端的标志

//+=====================安全========================================+
define("_ISHTTPS",false);//是否开启强制https连接。注：Public、Print两个Action没有这个
define("_KEY128","ayuiowlm78^%ew%.");//ASE加密算法中的key。16B=128bit
define("_IV","789asUI{}:asdag1");//ASE加密算法中的初始向量。16B=128bit
define("BLOCKSIZE",33);//标签组里最多能显示多少个方格（用于首页）

define("COMPANYNAME","快点订餐系统");//软件注册的公司名称，用于excel输出
?>