<?php
define("_SOFTNAME","爱情银行");//如若更改，则Tpl/Public/title.html文件也需要更改
define("_VERSION","1.0.0");
define("_CURRENCY","爱情币");
define("_INIT_MONEY",100);
define("_SPECIAL_END_FLAG","@#$%^&*&^%$#@!$(&&$%^");
define("_SPECIAL_END_FLAG_STRLEN",strlen(_SPECIAL_END_FLAG));
define("_SELECT_CONTENT_BREAK_FLAG","--------做到+");
define("_SELECT_CONTENT_BREAK_FLAG_STRLEN",strlen(_SELECT_CONTENT_BREAK_FLAG));
define("_SPECAL_BILL_END_FLAG","%$#@");
define("_SPECAL_BILL_END_FLAG_STRLEN",strlen(_SPECAL_BILL_END_FLAG));
define("_SPECAL_DIARY_END_FLAG",_SPECAL_BILL_END_FLAG);
define("_SPECAL_DIARY_END_FLAG_STRLEN",strlen(_SPECAL_DIARY_END_FLAG));
define("_SELECT_NOTE_BREAK_FLAG",_SPECAL_BILL_END_FLAG);
define("_SELECT_NOTE_BREAK_FLAG_STRLEN",strlen(_SELECT_NOTE_BREAK_FLAG));
define("_ADD_REMARK","有你陪伴很开心");
define("_SUB_REMARK","你没做到约定");
define("_DEBUG",false);

$_ANNOUNCEMENT = array("本系统正在测试","已升级成为1.0.0版本","空","空","空","空","空");//预留7个空
/*
 权限：
销售     进货     库存管理     应收款     应付款     员工管理   财务  账号 
0     1    2     3    4      5    6  7
*/
$_POWERCHINESE = array("销售","进货","库存","应收款","应付款","员工管理","财务","账号");
?>