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
/**
 *后台主页面显示
 *@return null
 */
function admin_index(){
	echo 'welcome';
}
/**
 * 获取文章类别
 * @return 
 */
function getArtCate(){
	global $core;
	$cates = $core->getArtCate();
	echo '<select name=cate>';
	$cateNum = count($cates);
	for($i=0;$i<$cateNum;$i++){
		echo '<option value='.$cates[$i]['id'].'>'.$cates[$i]['name'].'</option>';
	}
	echo '</select>';
}

/**
 * 获取文章标签
 * @return 
 */
function getTags(){
	global $core;
	$tags = $core->getTags();
	$tagNum = count($tags);
	for($i=0;$i<$tagNum;$i++){
		echo '<label class="am-btn am-btn-default am-btn-xs">';
		echo '<input type="checkbox" name="tag" value='.$tags[$i]['id'].'>'.$tags[$i]['name'];
		echo '</label>';
	}
}
/**
 * 修改、新建文章类别
 * @param $name string
 * @param $order int
 * @param $intro string
 * @param $id int
 * @return true or false
 */
function saveCate($name,$order,$intro,$id=null){
	global $core;
	if(empty($name) or empty($intro)){
		exit('数据参数不足');
	}
	$data = array('name'=>$name,'order'=>(int)$order,'intro'=>$intro);
	if($id){
		$core->save('category',$data,$where);
	}else{
		if($rs = $core->add('category',$data)){
			
		}else{
			echo '错误';
		}
	}
}


?>