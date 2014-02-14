/*创建用户及对应数据库，用户对对应的数据库享有所有权限*/
CREATE USER 'easyOrder'@'localhost' IDENTIFIED BY "easyOrder";/*用户名easyOrder，密码easyOrder*/
GRANT USAGE ON * . * TO 'easyOrder'@'localhost' IDENTIFIED BY "easyOrder" WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
CREATE DATABASE IF NOT EXISTS `easyOrder` ;
GRANT ALL PRIVILEGES ON `easyOrder` . * TO 'easyOrder'@'localhost';

/*使用数据库*/
use easyOrder;

/*创建表*/
create table user(
	/*账户本身信息*/
	userName varchar(100) NOT NULL,
	userPassword varchar(100) NOT NULL,
	userPower varchar(100) NOT NULL,
	userPinYin varchar(100) NOT NULL,
	
	/*扩展信息*/
	tel varchar(100) NOT NULL,
	address varchar(200) NOT NULL,
	carAddress varchar(200) NOT NULL,
	carNo varchar(100) NOT NULL,
	
	/*数据库要用的信息*/
	tmpOrderID bigint NOT NULL,
	primary key(userName)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
insert user values("wbx","wbx","root","wbx","15355494740","东大街","9路车站","陕AA1111",1);

create table tmp_order(
	id bigint NOT NULL AUTO_INCREMENT,
	goodsIDArray LONGTEXT NOT NULL,
	goodsNumArray LONGTEXT NOT NULL,
	goodsSizeArray LONGTEXT NOT NULL,
	goodsMoneyArray LONGTEXT NOT NULL,
	
	/*付款信息*/
	userName varchar(100) NOT NULL,
	save double NOT NULL,
	xianJinShiShou double NOT NULL,
	yinHangShiShou double NOT NULL,
	primary key(id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
insert tmp_order values (null,"","","","","",0,0,0);
insert tmp_order values (null,"","","","","",0,0,0);

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
insert goods values(null,"测试货物南瓜饼","cshwngb","@#$%^&*本地1@#$%^&*10@#$%^&*本地2@#$%^&*5","南瓜饼","@#$%^&*默认@#$%^&*10斤","安井","1","double","","blue","red",1,"");