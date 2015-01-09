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
?>