<?php
/**
 * @author zhaofei
 * @description 系统常用函数
 */
function autoloadClass($name){
	global $corePath;
	global $limiter;
	$file = $corePath.'class'.$limiter.ucfirst($name).'.php';
	if(is_readable($file)){
		require_once $file;
	}else{
		exit('没有此类'.$file);
	}
}

/**
 * 获取网站域名
 * @return string $host
 */
function getHost() {
	if (array_key_exists('REQUEST_SCHEME', $_SERVER)) {
		if ($_SERVER['REQUEST_SCHEME'] == 'https') {
			$host = 'https://';
		} else {
			$host = 'http://';
		}
	}elseif (array_key_exists('HTTPS', $_SERVER)) {
		if ($_SERVER['HTTPS'] == 'off') {
			$host = 'http://';
		} else {
			$host = 'https://';
		}
	} else {
		$host = 'http://';
	}

	$host .= $_SERVER['HTTP_HOST'];
	return $host;
}


/**
 * 记录日志
 * @param string $s
 */
function logs($s) {
	global $core;
	$f = $core->userDir . 'logs/' . $core->guid . '-log' . date("Ymd") . '.txt';
	$handle = @fopen($f, 'a+');
	@fwrite($handle, "[" . date('c') . "~" . current(explode(" ", microtime())) . "]" . "\r\n" . $s . "\r\n");
	@fclose($handle);
}

/**
 * 页面运行时长
 * @return array
 */
function runTime() {
	global $core;

	$rt=array();
	$rt['time']=number_format(1000 * (microtime(1) - $_SERVER['_start_time']), 2);
	$rt['query']=$_SERVER['_query_count'];
	$rt['memory']=$_SERVER['_memory_usage'];
	$rt['error']=$_SERVER['_error_count'];
	if(function_exists('memory_get_usage')){
		$rt['memory']=(int)((memory_get_usage()-$_SERVER['_memory_usage'])/1024);
	}
		echo '<!--' . $rt['time'] . 'ms , ';
	echo  $rt['query'] . ' query';
	if(function_exists('memory_get_usage'))
		echo ' , ' . $rt['memory'] . 'kb memory';
	echo  ' , ' . $rt['error'] . ' error';
	echo '-->';
	return $rt;
}
 
/**
 * 获取参数值
 * @param string $name 数组key
 * @param string $type 数组名
 */
function getVars($name,$type='REQUEST'){
	$array = &$GLOBALS[strtoupper("_$type")];
	if(isset($array[$name])){
		return $array[$name];
	}else{
		return null;
	}
}
?>