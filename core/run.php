<?php
/**
 * @description 项目基础文件
 * @package a_blog
 * @author zhaofei
 */
ini_set('display_errors',1);
ob_start();
$limiter = DIRECTORY_SEPARATOR;
$corePath = dirname(__FILE__).$limiter; 
$appPath = str_replace('\\','/',realpath($corePath . '..' . $limiter)) . '/';
$funPath = $corePath.'function'.$limiter;
require $funPath . 'common.php';
#系统预处理
spl_autoload_register('autoloadClass');
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
$core = null;
$host = getHost();
#数据库类,基础对象
autoloadClass('core');
AutoloadClass('dbSql');
AutoloadClass('base');


#实例化一个blog
$core=Core::getInstance();
$core->init();
 
 
