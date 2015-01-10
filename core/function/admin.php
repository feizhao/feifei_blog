<?php
/**
 * 后台页面函数
 * @author zhaofei
 */

/**
 * 显示
 * @param string $name 
 * @return null;
 */
function show($name){
	global $core ;
	$file = $core->userDir.'admin'.$core->limiter.$name.'.php';
	if(is_readable($file)){
		include_once $file;
	}else{
		exit('没有此文件'.$file);
	}
	
}
?>