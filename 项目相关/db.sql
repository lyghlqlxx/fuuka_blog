-- 创建库
CREATE DATABASE lightblog;

-- 选择库
USE lightblog;

-- 创建用户表
CREATE TABLE bk_user(
	id int unsigned not null primary key auto_increment,
	username varchar(50) not null unique,
	password char(32) not null,
    name varchar(20) not null,
	sex tinyint unsigned not null default 0,
	age tinyint unsigned not null default 0,
    email char(32) not null unique,
	level tinyint unsigned not null default 1,
	addtime int unsigned not null default 0,
	lasttime int unsigned not null default 0,
    display tinyint unsigned not null default 1,
	pic varchar(255) not null default 'default.jpg'
)engine=MyISAM DEFAULT CHARSET=utf8; 

-- 文章分类表
CREATE TABLE bk_category(
	id int unsigned not null primary key auto_increment,
	form_id int unsigned not null,
	name varchar(255) not null,
    	path varchar(20) not null,
    	display tinyint unsigned not null default 1
)engine=MyISAM DEFAULT CHARSET=utf8; 

-- 博客文章表
CREATE TABLE bk_post(
	id int unsigned not null primary key auto_increment,
	post_author int unsigned not null,
	addtime int unsigned not null default 0,
    lasttime int unsigned not null default 0,
	title varchar(255) not null,
	content text not null,
	plain_text text not null,
    url varchar(255) not null default 'http://youdomain.com',
	term_id int unsigned not null default 1,
	zan int unsigned not null default 0,
	view int unsigned not null default 0,
    display tinyint unsigned not null default 1,
	pic varchar(255) not null default 'default.jpg'
)engine=MyISAM DEFAULT CHARSET=utf8; 

-- 私信聊天表
create table bk_message(
	id int unsigned not null primary key auto_increment,
	send_id int unsigned not null,
	goal_id int unsigned not null,
    message varchar(255) not null,
	send_time int unsigned not null default 0,
	is_read tinyint unsigned not null default 0
)engine=MyISAM DEFAULT CHARSET=utf8;

-- 文章评论表
create table bk_comment(
	id int unsigned not null primary key auto_increment,
	send_id int unsigned not null,
	post_id int unsigned not null,
	send_time int unsigned not null default 0,
	conmment text not null
)engine=MyISAM DEFAULT CHARSET=utf8;

-- banner表
create table bk_banner(
    id int unsigned not null primary key auto_increment,
    title varchar(255) not null,
    pic varchar(255) not null,
    url varchar(255) not null,
    alt varchar(255) not null,
    display tinyint unsigned not null default 1
)engine=MyISAM DEFAULT CHARSET=utf8;
