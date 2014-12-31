<?php
/**
 * @description 项目基础文件
 * @package a_blog
 * @author zhaofei
 */

ob_start();
$nowpath = dirname(__FILE__).DIRECTORY_SEPARATOR; 
$apppath = str_replace('\\','/',realpath($nowpath . '..' . DIRECTORY_SEPARATOR)) . '/';
$funpath = $nowpath.'function'.DIRECTORY_SEPARATOR;
require $funpath . 'common.php';
require $funpath . 'opreate.php';
#系统预处理
spl_autoload_register('AutoloadClass');
if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()){
	function _stripslashes(&$var) {
		if(is_array($var)) {
			foreach($var as $k=>&$v) {
				_stripslashes($v);
			}
		} else {
			$var = stripslashes($var);
		}
	}
	_stripslashes($_GET);
	_stripslashes($_POST);
	_stripslashes($_COOKIE);
	_stripslashes($_REQUEST);
}

#初始化统计信息
$_SERVER['_start_time'] = microtime(true); //RunTime
$_SERVER['_query_count'] = 0;
$_SERVER['_memory_usage'] = 0;
$_SERVER['_error_count'] = 0;
if(function_exists('memory_get_usage')){
	$_SERVER['_memory_usage'] = memory_get_usage(true);	
}
 
#定义全局变量
$ablog = null;
$action = '';
$currenturl = GetRequestUri();
$lang = array();
$blogpath = $apppath;
$usersdir = $blogpath . 'myblog/';
$assets = $blogpath.'assets/';
$config = null;
$globalconf = require($blogpath . 'ablog/conf/global.php');
if(is_readable($filename = $usersdir . 'config.php')){
	$config = array_merge($globalconf,require($filename));
}else{
	$config = array_merge($globalconf,require($blogpath . 'ablog/conf/config.php'));
}
unset($globalconf);
$blogtitle = $config['A_BLOG_SUBNAME'];
$blogname =  $config['A_BLOG_NAME'];
$blogsubname = $config['A_BLOG_SUBNAME'];

$cookiespath = null;

$bloghost = GetCurrentHost($blogpath,$cookiespath);

#定义命令
$actions= require($blogpath.'ablog/defend/action.php');
#加载zbp 数据库类 基础对象
AutoloadClass('ABLOG');
AutoloadClass('DbSql');
AutoloadClass('Base');


#实例化一个blog
$ablog=ABlog::GetInstance();
$ablog->Initialize();
 
 
