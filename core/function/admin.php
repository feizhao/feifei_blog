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
	echo '<select id=cate name=cate>';
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
		echo '<input type="checkbox" name="tag[]" value='.$tags[$i]['id'].'>'.$tags[$i]['name'];
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
	$data = array();
	if(empty($name) or empty($intro)){
		$data['status'] = false;
		$data['msg'] = '类名和介绍不能为空';
		echo json_encode($data);
		exit();
	}
	$keyVal = array('name'=>$name,'order'=>(int)$order,'intro'=>$intro);
	if($id){
		$core->save('category',$keyVal,$where);
	}else{
		if($rs = $core->add('category',$keyVal)){
			$data = array('status'=>true,'msg'=>'添加成功','value'=>$rs);
		}else{
			$data = array('status'=>true,'msg'=>'添加失败');
		}
		echo json_encode($data);
	}
}
/**
 * 修改、新建标签
 * @param $name string
 * @param $order int
 * @param $id int
 * @return true or false
 */
function saveTag($name,$order,$id=null){
	global $core;
	$data = array();
	if(empty($name)){
		$data['status'] = false;
		$data['msg'] = '标签名不能为空';
		echo json_encode($data);
		exit();
	}
	$keyVal = array('name'=>$name,'order'=>(int)$order);
	if($id){
		$core->save('tag',$keyVal,$where);
	}else{
		if($rs = $core->add('tag',$keyVal)){
			$html =  '<label class="am-btn am-btn-default am-btn-xs">';
			$html.= '<input type="checkbox" name="tag" value='.$rs.'>'.$name;
		    $html.'</label>';
			$data = array('status'=>true,'msg'=>'添加成功','value'=>$html);
		}else{
			$data = array('status'=>true,'msg'=>'添加失败');
		}
		echo json_encode($data);
	}
}

/**
 * 修改、新建文章
 * @param $name string
 * @param $order int
 * @param $id int
 * @return true or false
 */
function saveActicle(){
	$intro = getVars('intro','POST');
	if(empty($intro)){
		$intro = substr(getVars('content','POST'),0,255);
	}
	if(getVars('tag','POST')){
		$tag = implode(',',getVars('tag','POST'));		
	}else{
		$tag = null;
	}
	$type = getVars('type','POST');
	if($type){
		foreach ($type as $key => $value) {
			if($value=='is_re'){
				$is_recommend=1;
			}
			if($value=='is_top'){
				$is_top=1;
			}
		}
	}
	//构造keyvalue数组
	$keyVal = array(
		'title'=>getVars('title','POST'),
		'content'=>getVars('content','POST'),
		'author'=>getVars('author','POST')?getVars('author','POST'):getVars('username','COOKIE'),
		'intro'=>$intro,
		'cate_id'=>(int)getVars('cate','POST'),
		'status'=>getVars('status','POST')?getVars('status','POST'):1,
		'is_top'=> $is_top,
		'is_recommend'=> $is_recommend,
		'post_time'=>getVars('time','POST')?strtotime(getVars('time','POST')):time(),
		'tag_ids'=>$tag,
	);
	global $core;	
	if(getVars('id')){
		$core->save('post',$keyVal,$where);
	}else{
		if($rs = $core->add('post',$keyVal)){
			 redirect('feifei.php?act=ArticleMag');
		}else{
			 $core->error('添加失败');
		}
	}
}


?>