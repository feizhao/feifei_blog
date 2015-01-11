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


/**
 * 设置http状态头
 * @param int $number HttpStatus
 * @internal param string $status 成功获取状态码设置静态参数status
 * @return bool
 */
function setHttpStatusCode($number) {
	static $status = '';
	if ($status != '')
		return false;
	switch ($number) {
		case 200:
			header("HTTP/1.1 200 OK");
			break;
		case 301:
			header("HTTP/1.1 301 Moved Permanently");
			break;
		case 302:
			header("HTTP/1.1 302 Found");
			break;
		case 304:
			header("HTTP/1.1 304 Not Modified");
			break;
		case 404:
			header('HTTP/1.1 404 Not Found');
			break;
		case 500:
			header('HTTP/1.1 500 Internal Server Error');
			break;
		case 503:
			header('HTTP/1.1 503 Service Unavailable');
	}
	$status = $number;

	return true;
}

/**
 * 302跳转
 * @param string $url 跳转链接
*/
function redirect($url) {
	setHttpStatusCode(302);
	header('Location: ' . $url);
	die();
}

/**
 * 验证登录
 * @return bool
 */
function verifyLogin() {
	global $core;
	if (getVars('username', 'POST')) {
		if ($core->verify_MD5(getVars('username', 'POST'), md5(getVars('password', 'POST')))) {
			$un = getVars('username', 'POST');
			$ps = md5($core->user->password . $core->user->guid);
			$sd = (int)getVars('savedate');
			if ( $sd == 0) {
				setcookie("username", $un, 0);
				setcookie("password", $ps, 0);
			} else {
				setcookie("username", $un, time() + 3600 * 24 * $sd);
				setcookie("password", $ps, time() + 3600 * 24 * $sd);
			}

			return true;
		} else {
			$core->error('登录失败');
		}
	} else {
		$core->error('参数不全');
	}
}


/**
 * 注销登录
 */
function logout() {
	global $core;
	setcookie('username', '', time() - 3600);
	setcookie('password', '', time() - 3600);
}

?>