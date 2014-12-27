<?php
/**
 * @description 项目基础文件
 * @package a_blog
 * @author zhaofei
 */

// error_reporting(0);
ob_start();
$nowpath = dirname(__FILE__).DIRECTORY_SEPARATOR; 

define('APP_PATH',str_replace('\\','/',realpath($nowpath . '..' . DIRECTORY_SEPARATOR)) . '/');
$funpath = $nowpath.'function'.DIRECTORY_SEPARATOR;
require_once $funpath . 'common.php';
require_once $funpath . 'opreate.php';
require_once 'debug.php';


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
define('A_BLOG_VERSION', '1.0.0'); 

/**
 *文章类型：文章型
 */
define('A_POST_TYPE_ARTICLE', 0);
/**
 *文章类型：页面型
 */
define('A_POST_TYPE_PAGE', 1);

/**
 *文章状态：公开发布
 */
define('A_POST_STATUS_PUBLIC', 0);
/**
 *文章状态：草稿
 */
define('A_POST_STATUS_DRAFT', 1);
/**
 *文章状态：审核
 */
define('A_POST_STATUS_AUDITING', 2);
/**
 *用户状态：正常
 */
define('A_MEMBER_STATUS_NORMAL', 0);
/**
 *用户状态：审核
 */
define('A_MEMBER_STATUS_AUDITING', 1);
/**
 *用户状态：锁定
 */
define('A_MEMBER_STATUS_LOCKED', 2);

#定义全局变量
$ablog = null;
$action = '';
$currenturl = GetRequestUri();
$lang = array();
$blogpath = APP_PATH;

$usersdir = $blogpath . 'feifei/';
$config = null;
if(is_readable($filename = $usersdir . 'config.php')){
	$config = require($filename);
}else{
	$config = require_once($blogpath . 'ablog/defend/defaultconfig.php');
}
 
unset($basepath,$key,$value,$user_config);

$blogtitle = $config['A_BLOG_SUBNAME'];
$blogname =  $config['A_BLOG_NAME'];
$blogsubname = $config['A_BLOG_SUBNAME'];
$blogtheme = $config['A_BLOG_THEME'];
$blogstyle = $config['A_BLOG_CSS'];
$blogversion = substr(A_BLOG_VERSION,-6,6);

$cookiespath = null;

$bloghost = GetCurrentHost($blogpath,$cookiespath);
#定义命令
$actions= require_once($blogpath.'ablog/defend/action.php');
$tab = require_once($blogpath.'ablog/defend/table.php');
$table=$tab['table'];
$datainfo=$tab['tableinfo'];

#加载zbp 数据库类 基础对象
AutoloadClass('ABLOG');
AutoloadClass('DbSql');
AutoloadClass('Base');


#实例化一个blog
$ablog=ABlog::GetInstance();
$ablog->Initialize();
$activeapps=array();

#加载主题内置的插件
$activeapps[]=$blogtheme;
if (is_readable($filename = $usersdir . 'theme/' . $blogtheme . '/include.php')) {
	require $filename;
}


#加载插件
$ap=explode("|", $config['A_USING_PLUGIN_LIST']);
$ap=array_unique($ap);
foreach ($ap as $plugin) {
	if (is_readable($filename = $usersdir . 'plugin/' . $plugin . '/include.php')) {
		$activeapps[]=$plugin;
		require $filename;
	}elseif(is_readable($filename = $usersdir . 'plugin/' . $plugin . '/plugin.xml')){
		$activeapps[]=$plugin;
	}
}
unset($plugin,$ap,$filename);


#激活所有已加载的插件
ActivePlugin();
