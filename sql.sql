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
	primary key(userName)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
insert user values("wbx","wbx","root");
insert user values("a","a","zs");