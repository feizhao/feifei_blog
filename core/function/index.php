<?php
/**
 * 前台页面函数
 * @author zhaofei
 */
function show($name){
	global $core ;
	$file = $core->userDir.'index'.$core->limiter.$name;
	if(is_readable($file)){
		include_once $file;
	}else{
		exit('没有此文件'.$file);
	}
	
}
?>