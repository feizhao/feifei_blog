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
	$cateNum = count($cates);
	for($i=0;$i<$cateNum;$i++){
		echo '<option value='.$cates[$i]['id'].'>'.$cates[$i]['name'].'</option>';
	}
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
			 redirect('feifei.php?act=ArticleMng');
		}else{
			 $core->error('添加失败');
		}
	}
}

/**
 *获取文章列表
 */
function getPostList(){
	if(getVars('page')){
		$page = (int)getVars('page');
		$start = $page * 1;
		$limit = "$start,1";
	}else{
		$page = 1; 
		$limit = "0,1";
	}
	if(getVars('show')){
		$where = getVars('show');
	}else{
		$where = null;
	}
	global $core;
	$posts = $core->getPostList($where,$limit);
    echo '<table class="am-table am-table-striped am-table-hover table-main">
        <thead>
          <tr>
            <th class="table-check"><input type="checkbox" /></th><th class="table-id">ID</th><th class="table-title">标题</th><th class="table-type">类别</th><th class="table-author">作者</th><th class="table-date">修改日期</th><th class="table-set">操作</th>
          </tr>
      </thead>
      <tbody>';
      $num = count($posts);
      for($i=0;$i<$num;$i++){
      	echo '<tr>';
      	echo '<td><input type="checkbox" /></td>';
      	echo '<td>'.$i.'</td>';
      	echo '<td><a href="#">'.$posts[$i]['title'].'</a></td>';
      	echo '<td>default</td>';
      	echo '<td>测试1号</td>';
      	echo '<td>2014年9月4日 7:28:47</td>';
      	echo ' <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                    <button class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-pencil-square-o"></span> 编辑</button>
                    <button class="am-btn am-btn-default am-btn-xs"><span class="am-icon-copy"></span> 复制</button>
                    <button class="am-btn am-btn-default am-btn-xs am-text-danger"><span class="am-icon-trash-o"></span> 删除</button>
                  </div>
                </div>
              </td>';
      	echo '</tr>';
      }
       
    echo ' </tbody></table>' ;
	$allNum = $core->getNum('post');
	$pagenation = new Page($page,1,$allNum); // 4(第一个参数) = currentPage, 10(第二个参数) = pageSize, 200(第三个参数) = 总数
    $pagenation->set_link( 'feifei.php?' );
    echo $pagenation->show();
}


?>