/*创建用户及对应数据库，用户对对应的数据库享有所有权限*/
CREATE USER 'kuaidian'@'localhost' IDENTIFIED BY "kuaidian";/*用户名kuaidian，密码kuaidian*/
GRANT USAGE ON * . * TO 'kuaidian'@'localhost' IDENTIFIED BY "kuaidian" WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
CREATE DATABASE IF NOT EXISTS `kuaidian` ;
GRANT ALL PRIVILEGES ON `kuaidian` . * TO 'kuaidian'@'localhost';

/*使用数据库*/
use kuaidian;

/*创建表*/
create table user(
	/*账户本身信息*/
	userName varchar(100) NOT NULL,
	userPassword varchar(100) NOT NULL,
	/*
	 *	case "root": $userPower = "根账户";break;
	    case "admin": $userPower = "管理员";break;
	    case "yyy": $userPower = "营业员";break;
	    case "zs": $userPower = "钻石账户";break;
	    case "bj": $userPower = "铂金账户";break;
	    case "j": $userPower = "金账户";break;
	    default: $userPower = "普通账户";break;
	 */
	userPower varchar(100) NOT NULL,
	userPinYin varchar(100) NOT NULL,
	
	/*扩展信息*/
	tel varchar(100) NOT NULL,
	address varchar(200) NOT NULL,
	carAddress varchar(200) NOT NULL,
	carNo varchar(100) NOT NULL,
	
	/*
	 * 账户余额，即应收应付款
	 */
	money double NOT NULL,
	
	/*数据库要用的信息*/
	tmpOrderID bigint NOT NULL,
	preTmpOrderID bigint NOT NULL,
	primary key(userName)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
insert user values("wbx","wbx","root","wbx","15355494740","东大街","9路车站","陕AA1111",0,1,1);
insert user values("wwxwwx2008","hhschyy125","admin","hhsc","02983106853","方欣国际食品城一楼125号(后门口)","无","无",0,2,2);
insert user values("youke","youke","pt","youke","812346567","快点软件","门口","无",0,3,3);

create table user_other_info(
	/*账户其他的信息，比如颜色*/
	userName varchar(100) NOT NULL,
	
	
	primary key(userName)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

create table tmp_order(
	id bigint NOT NULL AUTO_INCREMENT,
	goodsIDArray LONGTEXT NOT NULL,
	goodsNumArray LONGTEXT NOT NULL,
	goodsSizeArray LONGTEXT NOT NULL,
	goodsMoneyArray LONGTEXT NOT NULL,
	
	/*付款信息*/
	customName varchar(100) NOT NULL,
	save double NOT NULL,
	xianJinShiShou double NOT NULL,
	yinHangShiShou double NOT NULL,
	
	createDate datetime NOT NULL,
	printDate datetime NOT NULL,
	/*打印状态
	 *  8:该条订单还没有任何有效操作
	 * 	0：不打印
	 *	1:立即打印，还没有发送给打印机
	 *	2：存根已经下发给打印机，但是打印机还没有返回成功打印信号
	 *	3：打印存根成功
	 *	4：发票联已经下发给打印机，但是打印机还没有返回成功打印信号
	 *	5：发票联打印成功
	 *	6：出货单已经下发给打印机，但是打印机还没有返回成功打印信号
	 *	7：出货单已经打印成功，所有打印完成
	 *	8 : 占位符，当出现新的时用这个占位一个空行
	 *	101：重新打印存根联与发票联（只打一张，打完后不再继续打）
	 *	102：存根和发票联已经下发给打印机，但是打印机还没有返回成功打印信号
	 *	103：
	 *	104
	 *	105：重新打印出库单（只打一张，打完后不再继续打）
	 *
	 */
	printState int NOT NULL,
	primary key(id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
INSERT tmp_order VALUES (NULL, '', '', '', '', '', '0', '0', '0', '2014-02-16 02:12:00','2014-02-16 02:12:00', '8');
INSERT tmp_order VALUES (NULL, '', '', '', '', '', '0', '0', '0', '2014-02-16 02:12:00','2014-02-16 02:12:00', '8');
INSERT tmp_order VALUES (NULL, '', '', '', '', '', '0', '0', '0', '2014-02-16 02:12:00','2014-02-16 02:12:00', '8');

create table goods(
	id bigint NOT NULL AUTO_INCREMENT,
	name varchar(100) NOT NULL,
	pyname varchar(100) NOT NULL,
	
	warehouse LONGTEXT NOT NULL,/*存储的库房*/
	class LONGTEXT NOT NULL,
	size LONGTEXT NOT NULL,
	oem LONGTEXT NOT NULL,
	
	/*样式*/
	style LONGTEXT NOT NULL,
	highWide LONGTEXT NOT NULL,
	image LONGTEXT NOT NULL,
	bgColor LONGTEXT NOT NULL,
	brandColor LONGTEXT NOT NULL,
	indexNum bigint,/*在页面上的出现顺序*/
	remark LONGTEXT NOT NULL,/*提示，只针对样式4*/
	
	primary key(id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;


create table finance(
	id bigint NOT NULL AUTO_INCREMENT,
	userID LONGTEXT NOT NULL,/*哪一个用户的账单*/
	money double NOT NULL,
	
	/*记录模式：
	 *		0：应收款
	 *		1：应付款
	 *		2：费用
	 */
	mode int NOT NULL,
	
	remark LONGTEXT NOT NULL,
	createDate datetime NOT NULL,
	primary key(id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
