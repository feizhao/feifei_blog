<?php
/**
 * @description 功能操作函数
 * @package ablog
 * @subpackage  ablog
 * @copyright  zhaofei
 */

/**
 * 自动加载类文件
 * @param string $classname 类名
 * @return mixed
 */
function AutoloadClass($classname){
	global $blogpath;
	$file = $blogpath.'ablog/class/'.strtolower($classname).'.php';
	if(is_readable($file)){
		require $file;
	}
}

/**
 * 记录日志
 * @param string $s
 */
function Logs($s) {
	global $ablog;
	$f = $ablog->usersdir . 'logs/' . $ablog->guid . '-log' . date("Ymd") . '.txt';
	$handle = @fopen($f, 'a+');
	@fwrite($handle, "[" . date('c') . "~" . current(explode(" ", microtime())) . "]" . "\r\n" . $s . "\r\n");
	@fclose($handle);
}

/**
 * 页面运行时长
 * @return array
 */
function RunTime() {
	global $ablog;

	$rt=array();
	$rt['time']=number_format(1000 * (microtime(1) - $_SERVER['_start_time']), 2);
	$rt['query']=$_SERVER['_query_count'];
	$rt['memory']=$_SERVER['_memory_usage'];
	$rt['error']=$_SERVER['_error_count'];
	if(function_exists('memory_get_usage')){
		$rt['memory']=(int)((memory_get_usage()-$_SERVER['_memory_usage'])/1024);
	}
	
	if(isset($ablog->option['ZC_RUNINFO_DISPLAY'])&&$ablog->option['ZC_RUNINFO_DISPLAY']==false)return $rt;

	echo '<!--' . $rt['time'] . 'ms , ';
	echo  $rt['query'] . ' query';
	if(function_exists('memory_get_usage'))
		echo ' , ' . $rt['memory'] . 'kb memory';
	echo  ' , ' . $rt['error'] . ' error';
	echo '-->';
	return $rt;
}


/**
 * 验证登录
 * @return bool
 */
function verifyLogin() {
	global $ablog;
	if (isset($ablog->membersbyname[GetVars('username', 'POST')])) {
		if ($ablog->Verify_MD5(GetVars('username', 'POST'), GetVars('password', 'POST'))) {
			$un = GetVars('username', 'POST');
			$ps = md5($ablog->user->Password . $ablog->guid);
			$sd = (int)GetVars('savedate');
			if ( $sd == 0) {
				setcookie("username", $un, 0, $ablog->cookiespath);
				setcookie("password", $ps, 0, $ablog->cookiespath);
			} else {
				setcookie("username", $un, time() + 3600 * 24 * $sd, $ablog->cookiespath);
				setcookie("password", $ps, time() + 3600 * 24 * $sd, $ablog->cookiespath);
			}

			return true;
		} else {
			$ablog->ShowError(8, __FILE__, __LINE__);
		}
	} else {
		$ablog->ShowError(8, __FILE__, __LINE__);
	}
}

/**
 * 注销登录
 */
function logout() {
	global $ablog;

	setcookie('username', '', time() - 3600, $ablog->cookiespath);
	setcookie('password', '', time() - 3600, $ablog->cookiespath);
	setcookie("dishtml5", '', time() - 3600, $ablog->cookiespath);

}

/**
 * 获取文章
 * @param mixed $idorname 文章id 或 名称、别名
 * @param array $option|null
 * @return Post
 */
function GetPost($idorname, $option = null) {
	global $ablog;

	if (!is_array($option)) {
		$option = array();
	}

	if (!isset($option['only_article']))
		$option['only_article'] = false;
	if (!isset($option['only_page']))
		$option['only_page'] = false;

	if(is_string($idorname)){
		$w[] = array('array', array(array('log_Alias', $idorname), array('log_Title', $idorname)));
		if($option['only_article']==true){
			$w[]=array('=','log_Type','0');
		}
		elseif($option['only_page']==true){
			$w[]=array('=','log_Type','1');
		}
		$articles = $ablog->GetPostList('*', $w, null, 1, null);
		if (count($articles) == 0) {
			return new Post;
		}
		return $articles[0];
	}
	if(is_integer($idorname)){
		return $ablog->GetPostByID($idorname);
	}
}

/**
 * 获取文章列表
 * @param int $count 数量
 * @param null $cate 分类ID
 * @param null $auth 用户ID
 * @param null $date 日期
 * @param null $tags 标签
 * @param null $search 搜索关键词
 * @param null $option
 * @return array|mixed
 */
function GetList($count = 10, $cate = null, $auth = null, $date = null, $tags = null, $search = null, $option = null) {
	global $ablog;

	if (!is_array($option)) {
		$option = array();
	}

	if (!isset($option['only_ontop']))
		$option['only_ontop'] = false;
	if (!isset($option['only_not_ontop']))
		$option['only_not_ontop'] = false;
	if (!isset($option['has_subcate']))
		$option['has_subcate'] = false;
	if (!isset($option['is_related']))
		$option['is_related'] = false;

	if ($option['is_related']) {
		$at = $ablog->GetPostByID($option['is_related']);
		$tags = $at->Tags;
		if (!$tags)
			return array();
		$count = $count + 1;
	}

	if ($option['only_ontop'] == true) {
		$w[] = array('=', 'log_IsTop', 0);
	} elseif ($option['only_not_ontop'] == true) {
		$w[] = array('=', 'log_IsTop', 1);
	}

	$w = array();
	$w[] = array('=', 'log_Status', 0);

	$articles = array();

	if (!is_null($cate)) {
		$category = new Category;
		$category = $ablog->GetCategoryByID($cate);

		if ($category->ID > 0) {

			if (!$option['has_subcate']) {
				$w[] = array('=', 'log_CateID', $category->ID);
			} else {
				$arysubcate = array();
				$arysubcate[] = array('log_CateID', $category->ID);
				foreach ($ablog->categorys[$category->ID]->SubCategorys as $subcate) {
					$arysubcate[] = array('log_CateID', $subcate->ID);
				}
				$w[] = array('array', $arysubcate);

			}

		}
	}

	if (!is_null($auth)) {
		$author = new Member;
		$author = $ablog->GetMemberByID($auth);

		if ($author->ID > 0) {
			$w[] = array('=', 'log_AuthorID', $author->ID);
		}
	}

	if (!is_null($date)) {
		$datetime = strtotime($date);
		if ($datetime) {
			$datetitle = str_replace(array('%y%', '%m%'), array(date('Y', $datetime), date('n', $datetime)), $ablog->lang['msg']['year_month']);
			$w[] = array('BETWEEN', 'log_PostTime', $datetime, strtotime('+1 month', $datetime));
		}
	}

	if (!is_null($tags)) {
		$tag = new Tag;
		if (is_array($tags)) {
			$ta = array();
			foreach ($tags as $t) {
				$ta[] = array('log_Tag', '%{' . $t->ID . '}%');
			}
			$w[] = array('array_like', $ta);
			unset($ta);
		} else {
			if (is_int($tags)) {
				$tag = $ablog->GetTagByID($tags);
			} else {
				$tag = $ablog->GetTagByAliasOrName($tags);
			}
			if ($tag->ID > 0) {
				$w[] = array('LIKE', 'log_Tag', '%{' . $tag->ID . '}%');
			}
		}
	}

	if (is_string($search)) {
		$search=trim($search);
		if ($search!=='') {
			$w[] = array('search', 'log_Content', 'log_Intro', 'log_Title', $search);
		}
	}

	$articles = $ablog->GetArticleList('*', $w, array('log_PostTime' => 'DESC'), $count, null, false);

	if ($option['is_related']) {
		foreach ($articles as $k => $a) {
			if ($a->ID == $option['is_related'])
				unset($articles[$k]);
		}
		if (count($articles) == $count){
			array_pop($articles);
		}
	}

	return $articles;

}

################################################################################################################
/**
 * 显示索引页面(page、cate、auth、date、tags)
 * @api Filter_Plugin_ViewIndex_Begin
 * @return mixed
 */
function ViewIndex(){
	global $ablog,$action;
	PreViewIndex();
	switch ($action) {
	case 'feed':
		ViewFeed();
		break;
	case 'search':
		ViewSearch();
		break;
	case '':
	default:
		if( $ablog->currenturl==$ablog->cookiespath||
			$ablog->currenturl==$ablog->cookiespath . 'index.php' ){
			ViewList(null,null,null,null,null);
		}elseif(isset($_GET['id'])||isset($_GET['alias'])){
			ViewPost(GetVars('id','GET'),GetVars('alias','GET'));
		}elseif(isset($_GET['page'])||isset($_GET['cate'])||isset($_GET['auth'])||isset($_GET['date'])||isset($_GET['tags'])){
			ViewList(GetVars('page','GET'),GetVars('cate','GET'),GetVars('auth','GET'),GetVars('date','GET'),GetVars('tags','GET'));
		}else{
			ViewAuto($ablog->currenturl);
		}
	}
}

/**
 * 显示RSS2Feed
 * @api Filter_Plugin_ViewFeed_Begin
 * @return mixed
 */
function ViewFeed(){
	global $ablog;
	
	foreach ($GLOBALS['Filter_Plugin_ViewFeed_Begin'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname();
		if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
			$fpsignal=PLUGIN_EXITSIGNAL_NONE;return $fpreturn;
		}
	}
	
	if(!$ablog->CheckRights($GLOBALS['action'])){Http404();die;}

	$rss2 = new Rss2($ablog->name,$ablog->host,$ablog->subname);

	$articles=$ablog->GetArticleList(
		'*',
		array(array('=','log_Status',0)),
		array('log_PostTime'=>'DESC'),
		$ablog->option['ZC_RSS2_COUNT'],
		null
	);

	foreach ($articles as $article) {
		$rss2->addItem($article->Title,$article->Url,($ablog->option['ZC_RSS_EXPORT_WHOLE']==true?$article->Content:$article->Intro),$article->PostTime);
	}

	header("Content-type:text/xml; Charset=utf-8");

	echo $rss2->saveXML();

}

/**
 * 展示搜索结果
 * @api Filter_Plugin_ViewSearch_Begin
 * @api Filter_Plugin_ViewPost_Template
 * @return mixed
 */
function ViewSearch(){
	global $ablog;
	
	foreach ($GLOBALS['Filter_Plugin_ViewSearch_Begin'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname();
		if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
			$fpsignal=PLUGIN_EXITSIGNAL_NONE;return $fpreturn;
		}
	}
	
	if(!$ablog->CheckRights($GLOBALS['action'])){Redirect('./');}

	$q=trim(htmlspecialchars(GetVars('q','GET')));

	$article = new Post;
	$article->ID=0;
	$article->Title=$ablog->lang['msg']['search'] . ' &quot;' . $q . '&quot;';
	$article->IsLock=true;
	$article->Type=ZC_POST_TYPE_PAGE;

	if(isset($ablog->templates['search'])){
		$article->Template='search';
	}

	$w=array();
	$w[]=array('=','log_Type','0');
	if($q){
		$w[]=array('search','log_Content','log_Intro','log_Title',$q);
	}else{
		Redirect('./');
	}

	if(!($ablog->CheckRights('ArticleAll')&&$ablog->CheckRights('PageAll'))){
		$w[]=array('=','log_Status',0);
	}

	$array=$ablog->GetArticleList(
		'',
		$w,
		array('log_PostTime'=>'DESC'),
		array($ablog->searchcount),
		null
	);

	foreach ($array as $a) {
		$article->Content .= '<p><br/>' . $a->Title . '<br/>';
		$article->Content .= '<a href="' . $a->Url . '">' . $a->Url . '</a></p>';
	}

	$ablog->header .= '<meta name="robots" content="noindex,follow" />' . "\r\n";
	$ablog->template->SetTags('title',$article->Title);
	$ablog->template->SetTags('article',$article);
	$ablog->template->SetTags('type',$article->type=0?'article':'page');
	$ablog->template->SetTags('page',1);
	$ablog->template->SetTags('pagebar',null);
	$ablog->template->SetTags('comments',array());
	$ablog->template->SetTemplate($article->Template);

	foreach ($GLOBALS['Filter_Plugin_ViewPost_Template'] as $fpname => &$fpsignal) {
		$fpreturn=$fpname($ablog->template);
	}

	$ablog->template->Display();

}

################################################################################################################
/**
 * 根据Rewrite_url规则显示页面
 * @api Filter_Plugin_ViewAuto_Begin
 * @api Filter_Plugin_ViewAuto_End
 * @param string $inpurl 页面url
 * @return null|string
 */
function ViewAuto($inpurl) {
	global $ablog;

	foreach ($GLOBALS['Filter_Plugin_ViewAuto_Begin'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($url);
		if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
			$fpsignal=PLUGIN_EXITSIGNAL_NONE;return $fpreturn;
		}
	}
	
	$url=GetValueInArray(explode('?',$inpurl),'0');

	if($ablog->cookiespath === substr($url, 0 , strlen($ablog->cookiespath)))
		$url = substr($url, strlen($ablog->cookiespath));

	if (isset($_SERVER['SERVER_SOFTWARE'])) {
		if ((strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false) && (isset($_GET['rewrite']) == true)){
			//iis+httpd.ini下如果存在真实文件
			$realurl = $ablog->path . urldecode($url);
			if(is_readable($realurl)&&is_file($realurl)){
				die(file_get_contents($realurl));
			}
			unset($realurl);
		}
	}

	$url = urldecode($url);

	if($url==''||$url=='index.php'||trim($url,'/')==''){
		ViewList(null,null,null,null,null);
		return null;
	}
	
	if ($ablog->option['ZC_STATIC_MODE'] == 'ACTIVE') {
		$ablog->ShowError(2, __FILE__, __LINE__);
		return null;
	}

	$r = UrlRule::Rewrite_url($ablog->option['ZC_INDEX_REGEX'], 'index');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		ViewList($m[1], null, null, null, null, true);

		return null;
	}

	$r = UrlRule::Rewrite_url($ablog->option['ZC_DATE_REGEX'], 'date');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		ViewList($m[2], null, null, $m[1], null, true);

		return null;
	}

	$r = UrlRule::Rewrite_url($ablog->option['ZC_AUTHOR_REGEX'], 'auth');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		$result = ViewList($m[2], null, $m[1], null, null, true);
		if ($result == true)
			return null;
	}

	$r = UrlRule::Rewrite_url($ablog->option['ZC_TAGS_REGEX'], 'tags');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		$result = ViewList($m[2], null, null, null, $m[1], true);
		if ($result == true)
			return null;
	}

	$r = UrlRule::Rewrite_url($ablog->option['ZC_CATEGORY_REGEX'], 'cate');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		$result = ViewList($m[2], $m[1], null, null, null, true);
		if ($result == true)
			return null;
	}

	$r = UrlRule::Rewrite_url($ablog->option['ZC_ARTICLE_REGEX'], 'article');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		if (strpos($ablog->option['ZC_ARTICLE_REGEX'], '{%id%}') !== false) {
			$result = ViewPost($m[1], null, true);
		} else {
			$result = ViewPost(null, $m[1], true);
		}
		if ($result == false)
			$ablog->ShowError(2, __FILE__, __LINE__);

		return null;
	}

	$r = UrlRule::Rewrite_url($ablog->option['ZC_PAGE_REGEX'], 'page');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		if (strpos($ablog->option['ZC_PAGE_REGEX'], '{%id%}') !== false) {
			$result = ViewPost($m[1], null, true);
		} else {
			$result = ViewPost(null, $m[1], true);
		}
		if ($result == false)
			$ablog->ShowError(2, __FILE__, __LINE__);

		return null;
	}

	foreach ($GLOBALS['Filter_Plugin_ViewAuto_End'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($url);
		if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
			$fpsignal=PLUGIN_EXITSIGNAL_NONE;return $fpreturn;
		}
	}

	$ablog->ShowError(2, __FILE__, __LINE__);

}

/**
 * 显示列表页面
 * @api Filter_Plugin_ViewList_Begin
 * @api Filter_Plugin_ViewList_Template
 * @param int $page
 * @param int|string $cate
 * @param int|string $auth
 * @param string   $date
 * @param string $tags tags列表
 * @param bool $isrewrite 是否启用urlrewrite
 * @return string
 */
function ViewList($page, $cate, $auth, $date, $tags, $isrewrite = false) {
	global $ablog;

	foreach ($GLOBALS['Filter_Plugin_ViewList_Begin'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($page, $cate, $auth, $date, $tags);
		if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
			$fpsignal=PLUGIN_EXITSIGNAL_NONE;return $fpreturn;
		}
	}
	$type = 'index';
	if ($cate !== null)
		$type = 'category';
	if ($auth !== null)
		$type = 'author';
	if ($date !== null)
		$type = 'date';
	if ($tags !== null)
		$type = 'tag';

	$category = null;
	$author = null;
	$datetime = null;
	$tag = null;

	$w = array();
	$w[] = array('=', 'log_IsTop', 0);
	$w[] = array('=', 'log_Status', 0);

	$page = (int)$page == 0 ? 1 : (int)$page;

	$articles = array();
	$articles_top = array();
	switch ($type) {
		########################################################################################################
		case 'index':
			$pagebar = new Pagebar($ablog->option['ZC_INDEX_REGEX']);
			$pagebar->Count = $ablog->cache->normal_article_nums;
			$category = new Metas;
			$author = new Metas;
			$datetime = new Metas;
			$tag = new Metas;
			$template = $ablog->option['ZC_INDEX_DEFAULT_TEMPLATE'];
			if ($page == 1) {
				$ablog->title = $ablog->subname;
			} else {
				$ablog->title = str_replace('%num%', $page, $ablog->lang['msg']['number_page']);
			}
			break;
		########################################################################################################
		case 'category':
			$pagebar = new Pagebar($ablog->option['ZC_CATEGORY_REGEX']);
			$author = new Metas;
			$datetime = new Metas;
			$tag = new Metas;

			$category = new Category;
			if (strpos($ablog->option['ZC_CATEGORY_REGEX'], '{%id%}') !== false) {
				$category = $ablog->GetCategoryByID($cate);
			}
			if (strpos($ablog->option['ZC_CATEGORY_REGEX'], '{%alias%}') !== false) {
				$category = $ablog->GetCategoryByAliasOrName($cate);
			}
			if ($category->ID == 0) {
				if ($isrewrite == true)
					return false;
				$ablog->ShowError(2, __FILE__, __LINE__);
			}
			if ($page == 1) {
				$ablog->title = $category->Name;
			} else {
				$ablog->title = $category->Name . ' ' . str_replace('%num%', $page, $ablog->lang['msg']['number_page']);
			}
			$template = $category->Template;

			if (!$ablog->option['ZC_DISPLAY_SUBCATEGORYS']) {
				$w[] = array('=', 'log_CateID', $category->ID);
				$pagebar->Count = $category->Count;
			} else {
				$arysubcate = array();
				$arysubcate[] = array('log_CateID', $category->ID);
				foreach ($ablog->categorys[$category->ID]->SubCategorys as $subcate) {
					$arysubcate[] = array('log_CateID', $subcate->ID);
				}
				$w[] = array('array', $arysubcate);
			}

			$pagebar->UrlRule->Rules['{%id%}'] = $category->ID;
			$pagebar->UrlRule->Rules['{%alias%}'] = $category->Alias == '' ? urlencode($category->Name) : $category->Alias;
			break;
		########################################################################################################
		case 'author':
			$pagebar = new Pagebar($ablog->option['ZC_AUTHOR_REGEX']);
			$category = new Metas;
			$datetime = new Metas;
			$tag = new Metas;

			$author = new Member;
			if (strpos($ablog->option['ZC_AUTHOR_REGEX'], '{%id%}') !== false) {
				$author = $ablog->GetMemberByID($auth);
			}
			if (strpos($ablog->option['ZC_AUTHOR_REGEX'], '{%alias%}') !== false) {
				$author = $ablog->GetMemberByAliasOrName($auth);
			}
			if ($author->ID == 0) {
				if ($isrewrite == true)
					return false;
				$ablog->ShowError(2, __FILE__, __LINE__);
			}
			if ($page == 1) {
				$ablog->title = $author->StaticName;
			} else {
				$ablog->title = $author->StaticName . ' ' . str_replace('%num%', $page, $ablog->lang['msg']['number_page']);
			}
			$template = $author->Template;
			$w[] = array('=', 'log_AuthorID', $author->ID);
			$pagebar->Count = $author->Articles;
			$pagebar->UrlRule->Rules['{%id%}'] = $author->ID;
			$pagebar->UrlRule->Rules['{%alias%}'] = $author->Alias == '' ? urlencode($author->Name) : $author->Alias;
			break;
		########################################################################################################
		case 'date':
			$pagebar = new Pagebar($ablog->option['ZC_DATE_REGEX']);
			$category = new Metas;
			$author = new Metas;
			$tag = new Metas;
			$datetime = strtotime($date);

			$datetitle = str_replace(array('%y%', '%m%'), array(date('Y', $datetime), date('n', $datetime)), $ablog->lang['msg']['year_month']);
			if ($page == 1) {
				$ablog->title = $datetitle;
			} else {
				$ablog->title = $datetitle . ' ' . str_replace('%num%', $page, $ablog->lang['msg']['number_page']);
			}

			$ablog->modulesbyfilename['calendar']->Content = BuildModule_calendar(date('Y', $datetime) . '-' . date('n', $datetime));

			$template = $ablog->option['ZC_INDEX_DEFAULT_TEMPLATE'];
			$w[] = array('BETWEEN', 'log_PostTime', $datetime, strtotime('+1 month', $datetime));
			$pagebar->UrlRule->Rules['{%date%}'] = $date;
			$datetime = Metas::ConvertArray(getdate($datetime));
			break;
		########################################################################################################
		case 'tag':
			$pagebar = new Pagebar($ablog->option['ZC_TAGS_REGEX']);
			$category = new Metas;
			$author = new Metas;
			$datetime = new Metas;
			$tag = new Tag;
			if (strpos($ablog->option['ZC_TAGS_REGEX'], '{%id%}') !== false) {
				$tag = $ablog->GetTagByID($tags);
			}
			if (strpos($ablog->option['ZC_TAGS_REGEX'], '{%alias%}') !== false) {
				$tag = $ablog->GetTagByAliasOrName($tags);
			}
			if ($tag->ID == 0) {
				if ($isrewrite == true)
					return false;
				$ablog->ShowError(2, __FILE__, __LINE__);
			}

			if ($page == 1) {
				$ablog->title = $tag->Name;
			} else {
				$ablog->title = $tag->Name . ' ' . str_replace('%num%', $page, $ablog->lang['msg']['number_page']);
			}

			$template = $tag->Template;
			$w[] = array('LIKE', 'log_Tag', '%{' . $tag->ID . '}%');
			$pagebar->UrlRule->Rules['{%id%}'] = $tag->ID;
			$pagebar->UrlRule->Rules['{%alias%}'] = $tag->Alias == '' ? urlencode($tag->Name) : $tag->Alias;
			break;
	}

	$pagebar->PageCount = $ablog->displaycount;
	$pagebar->PageNow = $page;
	$pagebar->PageBarCount = $ablog->pagebarcount;
	$pagebar->UrlRule->Rules['{%page%}'] = $page;

	foreach ($GLOBALS['Filter_Plugin_ViewList_Core'] as $fpname => &$fpsignal) {
		$fpname($type, $page, $category, $author, $datetime, $tag, $w, $pagebar);
	}

	if(isset($ablog->option['ZC_LISTONTOP_TURNOFF'])&&$ablog->option['ZC_LISTONTOP_TURNOFF']==false){
		if ($type == 'index' && $page == 1) {
			$articles_top = $ablog->GetArticleList('*', array(array('=', 'log_IsTop', 1), array('=', 'log_Status', 0)), array('log_PostTime' => 'DESC'), null, null);
		}
	}

	$articles = $ablog->GetArticleList(
		'*', 
		$w,
		array('log_PostTime' => 'DESC'), array(($pagebar->PageNow - 1) * $pagebar->PageCount, $pagebar->PageCount),
		array('pagebar' => $pagebar),
		true
	);

	$ablog->template->SetTags('title', $ablog->title);
	$ablog->template->SetTags('articles', array_merge($articles_top, $articles));
	if ($pagebar->PageAll == 0)
		$pagebar = null;
	$ablog->template->SetTags('pagebar', $pagebar);
	$ablog->template->SetTags('type', $type);
	$ablog->template->SetTags('page', $page);

	$ablog->template->SetTags('date', $datetime);
	$ablog->template->SetTags('tag', $tag);
	$ablog->template->SetTags('author', $author);
	$ablog->template->SetTags('category', $category);

	if (isset($ablog->templates[$template])) {
		$ablog->template->SetTemplate($template);
	} else {
		$ablog->template->SetTemplate('index');
	}

	foreach ($GLOBALS['Filter_Plugin_ViewList_Template'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($ablog->template);
	}
	// var_dump($ablog->template);
	// exit();
	$ablog->template->Display();
	
	return true;
}

/**
 * 显示文章
 * @param int $id 文章ID
 * @param string $alias 文章别名
 * @param bool $isrewrite 是否启用urlrewrite
 * @return string
 */
function ViewPost($id, $alias, $isrewrite = false) {
	global $ablog;
	foreach ($GLOBALS['Filter_Plugin_ViewPost_Begin'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($id, $alias);
		if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
			$fpsignal=PLUGIN_EXITSIGNAL_NONE;return $fpreturn;
		}
	}

	$w = array();

	if ($id !== null) {
		$w[] = array('=', 'log_ID', $id);
	} elseif ($alias !== null) {
		if($ablog->option['ZC_POST_ALIAS_USE_ID_NOT_TITLE']==false){
			$w[] = array('array', array(array('log_Alias', $alias), array('log_Title', $alias)));
		}else{
			$w[] = array('array', array(array('log_Alias', $alias), array('log_ID', $alias)));
		}
	} else {
		$ablog->ShowError(2, __FILE__, __LINE__);
		die();
	}

	if (!($ablog->CheckRights('ArticleAll') && $ablog->CheckRights('PageAll'))) {
		$w[] = array('=', 'log_Status', 0);
	}

	$articles = $ablog->GetPostList('*', $w, null, 1, null);
	if (count($articles) == 0) {
		if ($isrewrite == true)
			return false;
		$ablog->ShowError(2, __FILE__, __LINE__);
	}

	$article = $articles[0];

	if ($article->Type == 0) {
		$ablog->LoadTagsByIDString($article->Tag);
	}

	if (isset($ablog->option['ZC_VIEWNUMS_TURNOFF']) && $ablog->option['ZC_VIEWNUMS_TURNOFF']==false) {
		$article->ViewNums += 1;
		$sql = $ablog->db->sql->Update($ablog->table['Post'], array('log_ViewNums' => $article->ViewNums), array(array('=', 'log_ID', $article->ID)));
		$ablog->db->Update($sql);
	}

	$pagebar = new Pagebar('javascript:GetComments(\'' . $article->ID . '\',\'{%page%}\')', false);
	$pagebar->PageCount = $ablog->commentdisplaycount;
	$pagebar->PageNow = 1;
	$pagebar->PageBarCount = $ablog->pagebarcount;

	if ($ablog->option['ZC_COMMENT_TURNOFF']) {
		$article->IsLock = true;
	}
	
	$comments = array();

	if($article->IsLock==false && $ablog->socialcomment==null){
		$comments = $ablog->GetCommentList(
			'*', 
			array(
				array('=', 'comm_RootID', 0),
				array('=', 'comm_IsChecking', 0),
				array('=', 'comm_LogID', $article->ID)
			),
			array('comm_ID' => ($ablog->option['ZC_COMMENT_REVERSE_ORDER'] ? 'DESC' : 'ASC')),
			array(($pagebar->PageNow - 1) * $pagebar->PageCount, $pagebar->PageCount),
			array('pagebar' => $pagebar)
		);
		$rootid = array();
		foreach ($comments as &$comment) {
			$rootid[] = array('comm_RootID', $comment->ID);
		}
		$comments2 = $ablog->GetCommentList(
			'*', 
			array(
				array('array', $rootid),
				array('=', 'comm_IsChecking', 0),
				array('=', 'comm_LogID', $article->ID)
			),
			array('comm_ID' => ($ablog->option['ZC_COMMENT_REVERSE_ORDER'] ? 'DESC' : 'ASC')),
			null,
			null
		);
		$floorid = ($pagebar->PageNow - 1) * $pagebar->PageCount;
		foreach ($comments as &$comment) {
			$floorid += 1;
			$comment->FloorID = $floorid;
			$comment->Content = TransferHTML($comment->Content, '[enter]') . '<label id="AjaxComment' . $comment->ID . '"></label>';
		}
		foreach ($comments2 as &$comment) {
			$comment->Content = TransferHTML($comment->Content, '[enter]') . '<label id="AjaxComment' . $comment->ID . '"></label>';
		}
	}
	
	$ablog->template->SetTags('title', ($article->Status == 0 ? '' : '[' . $ablog->lang['post_status_name'][$article->Status] . ']') . $article->Title);
	$ablog->template->SetTags('article', $article);
	$ablog->template->SetTags('type', ($article->Type == 0 ? 'article' : 'page'));
	$ablog->template->SetTags('page', 1);
	if ($pagebar->PageAll == 0 || $pagebar->PageAll == 1)
		$pagebar = null;
	$ablog->template->SetTags('pagebar', $pagebar);
	$ablog->template->SetTags('comments', $comments);

	if (isset($ablog->templates[$article->Template])) {
		$ablog->template->SetTemplate($article->Template);
	} else {
		$ablog->template->SetTemplate('single');
	}

	foreach ($GLOBALS['Filter_Plugin_ViewPost_Template'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($ablog->template);
	}

	$ablog->template->Display();

	return true;
}

/**
 * 显示文章下评论列表
 * @param int $postid 文章ID
 * @param int $page 页数
 * @return bool
 */
function ViewComments($postid, $page) {
	global $ablog;

	$post = New Post;
	$post->LoadInfoByID($postid);
	$page = $page == 0 ? 1 : $page;
	$template = 'comments';

	$pagebar = new Pagebar('javascript:GetComments(\'' . $post->ID . '\',\'{%page%}\')');
	$pagebar->PageCount = $ablog->commentdisplaycount;
	$pagebar->PageNow = $page;
	$pagebar->PageBarCount = $ablog->pagebarcount;

	$comments = array();

	$comments = $ablog->GetCommentList(
		'*',
		array(
			array('=', 'comm_RootID', 0),
			array('=', 'comm_IsChecking', 0),
			array('=', 'comm_LogID', $post->ID)
		),
		array('comm_ID' => ($ablog->option['ZC_COMMENT_REVERSE_ORDER'] ? 'DESC' : 'ASC')),
		array(($pagebar->PageNow - 1) * $pagebar->PageCount, $pagebar->PageCount),
		array('pagebar' => $pagebar)
	);
	$rootid = array();
	foreach ($comments as $comment) {
		$rootid[] = array('comm_RootID', $comment->ID);
	}
	$comments2 = $ablog->GetCommentList(
		'*',
		array(
			array('array', $rootid),
			array('=', 'comm_IsChecking', 0),
			array('=', 'comm_LogID', $post->ID)
		),
		array('comm_ID' => ($ablog->option['ZC_COMMENT_REVERSE_ORDER'] ? 'DESC' : 'ASC')),
		null,
		null
	);

	$floorid = ($pagebar->PageNow - 1) * $pagebar->PageCount;
	foreach ($comments as &$comment) {
		$floorid += 1;
		$comment->FloorID = $floorid;
		$comment->Content = TransferHTML($comment->Content, '[enter]') . '<label id="AjaxComment' . $comment->ID . '"></label>';
	}
	foreach ($comments2 as &$comment) {
		$comment->Content = TransferHTML($comment->Content, '[enter]') . '<label id="AjaxComment' . $comment->ID . '"></label>';
	}

	$ablog->template->SetTags('title', $ablog->title);
	$ablog->template->SetTags('article', $post);
	$ablog->template->SetTags('type', 'comment');
	$ablog->template->SetTags('page', $page);
	if ($pagebar->PageAll == 1)
		$pagebar = null;
	$ablog->template->SetTags('pagebar', $pagebar);
	$ablog->template->SetTags('comments', $comments);

	$ablog->template->SetTemplate($template);

	foreach ($GLOBALS['Filter_Plugin_ViewComments_Template'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($ablog->template);
	}

	$s = $ablog->template->Output();

	$a = explode('<label id="AjaxCommentBegin"></label>', $s);
	$s = $a[1];
	$a = explode('<label id="AjaxCommentEnd"></label>', $s);
	$s = $a[0];

	echo $s;

	return true;
}

/**
 * 显示评论
 * @param int $id 评论ID
 */
function ViewComment($id) {
	global $ablog;

	$template = 'comment';
	$comment = $ablog->GetCommentByID($id);
	$post = new Post;
	$post->LoadInfoByID($comment->LogID);

	$comment->Content = TransferHTML(htmlspecialchars($comment->Content), '[enter]') . '<label id="AjaxComment' . $comment->ID . '"></label>';

	$ablog->template->SetTags('title', $ablog->title);
	$ablog->template->SetTags('comment', $comment);
	$ablog->template->SetTags('article', $post);
	$ablog->template->SetTags('type', 'comment');
	$ablog->template->SetTags('page', 1);
	$ablog->template->SetTemplate($template);

	$ablog->template->Display();

	return true;
}

################################################################################################################
/**
 * 提交文章数据
 * @return bool
 */
function PostArticle() {
	global $ablog;
	if (!isset($_POST['ID'])) return;

	if (isset($_COOKIE['timezone'])) {
		$tz = GetVars('timezone', 'COOKIE');
		if (is_numeric($tz)) {
			date_default_timezone_set('Etc/GMT' . sprintf('%+d', -$tz));
		}
		unset($tz);
	}

	if (isset($_POST['Tag'])) {
		$_POST['Tag'] = TransferHTML($_POST['Tag'], '[noscript]');
		$_POST['Tag'] = PostArticle_CheckTagAndConvertIDtoString($_POST['Tag']);
	}
	if (isset($_POST['Content'])) {
		$_POST['Content'] = str_replace('<hr class="more" />', '<!--more-->', $_POST['Content']);
		$_POST['Content'] = str_replace('<hr class="more"/>', '<!--more-->', $_POST['Content']);
		if (strpos($_POST['Content'], '<!--more-->') !== false) {
			if (isset($_POST['Intro'])) {
				$_POST['Intro'] = GetValueInArray(explode('<!--more-->', $_POST['Content']), 0);
			}
		} else {
			if (isset($_POST['Intro'])) {
				if ($_POST['Intro'] == '') {
					$_POST['Intro'] = SubStrUTF8($_POST['Content'], $ablog->option['ZC_ARTICLE_EXCERPT_MAX']);
					if (strpos($_POST['Intro'], '<') !== false) {
						$_POST['Intro'] = CloseTags($_POST['Intro']);
					}
				}
			}
		}
	}

	if (!isset($_POST['AuthorID'])) {
		$_POST['AuthorID'] = $ablog->user->ID;
	} else {
		if (($_POST['AuthorID'] != $ablog->user->ID) && (!$ablog->CheckRights('ArticleAll'))) {
			$_POST['AuthorID'] = $ablog->user->ID;
		}
		if ($_POST['AuthorID'] == 0)
			$_POST['AuthorID'] = $ablog->user->ID;
	}

	if (isset($_POST['Alias'])) {
		$_POST['Alias'] = TransferHTML($_POST['Alias'], '[noscript]');
	}

	if (isset($_POST['PostTime'])) {
		$_POST['PostTime'] = strtotime($_POST['PostTime']);
	}

	if (!$ablog->CheckRights('ArticleAll')) {
		unset($_POST['IsTop']);
	}

	$article = new Post();
	$pre_author = null;
	$pre_tag = null;
	$pre_category = null;
	if (GetVars('ID', 'POST') == 0) {
		if (!$ablog->CheckRights('ArticlePub')) {
			$_POST['Status'] = ZC_POST_STATUS_AUDITING;
		}
	} else {
		$article->LoadInfoByID(GetVars('ID', 'POST'));
		if (($article->AuthorID != $ablog->user->ID) && (!$ablog->CheckRights('ArticleAll'))) {
			$ablog->ShowError(6, __FILE__, __LINE__);
		}
		if ((!$ablog->CheckRights('ArticlePub')) && ($article->Status == ZC_POST_STATUS_AUDITING)) {
			$_POST['Status'] = ZC_POST_STATUS_AUDITING;
		}
		$pre_author = $article->AuthorID;
		$pre_tag = $article->Tag;
		$pre_category = $article->CateID;
	}

	foreach ($ablog->datainfo['Post'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$article->$key = GetVars($key, 'POST');
		}
	}

	$article->Type = ZC_POST_TYPE_ARTICLE;

	foreach ($GLOBALS['Filter_Plugin_PostArticle_Core'] as $fpname => &$fpsignal) {
		$fpname($article);
	}

	FilterPost($article);
	FilterMeta($article);

	$article->Save();

	CountTagArrayString($pre_tag . $article->Tag);
	CountMemberArray(array($pre_author, $article->AuthorID));
	CountCategoryArray(array($pre_category, $article->CateID));
	CountPostArray(array($article->ID));
	CountNormalArticleNums();

	$ablog->AddBuildModule('previous');
	$ablog->AddBuildModule('calendar');
	$ablog->AddBuildModule('comments');
	$ablog->AddBuildModule('archives');
	$ablog->AddBuildModule('tags');
	$ablog->AddBuildModule('authors');

	foreach ($GLOBALS['Filter_Plugin_PostArticle_Succeed'] as $fpname => &$fpsignal)
		$fpname($article);

	return true;
}

/**
 * 删除文章
 * @return bool
 */
function DelArticle() {
	global $ablog;

	$id = (int)GetVars('id', 'GET');

	$article = new Post();
	$article->LoadInfoByID($id);
	if ($article->ID > 0) {

		if (!$ablog->CheckRights('ArticleAll') && $article->AuthorID != $ablog->user->ID)
			$ablog->ShowError(6, __FILE__, __LINE__);

		$pre_author = $article->AuthorID;
		$pre_tag = $article->Tag;
		$pre_category = $article->CateID;

		$article->Del();

		DelArticle_Comments($article->ID);

		CountTagArrayString($pre_tag);
		CountMemberArray(array($pre_author));
		CountCategoryArray(array($pre_category));
		CountNormalArticleNums();

		$ablog->AddBuildModule('previous');
		$ablog->AddBuildModule('calendar');
		$ablog->AddBuildModule('comments');
		$ablog->AddBuildModule('archives');
		$ablog->AddBuildModule('tags');
		$ablog->AddBuildModule('authors');

		foreach ($GLOBALS['Filter_Plugin_DelArticle_Succeed'] as $fpname => &$fpsignal)
			$fpname($article);
	} else {

	}

	return true;
}

/**
 * 提交文章数据时检查tag数据，并将新tags转为标准格式返回
 * @param string $tagnamestring 提交的文章tag数据，可以:,，、等符号分隔
 * @return string 返回如'{1}{2}{3}{4}'的字符串
 */
function PostArticle_CheckTagAndConvertIDtoString($tagnamestring) {
	global $ablog;
	$s = '';
	$tagnamestring = str_replace(';', ',', $tagnamestring);
	$tagnamestring = str_replace('，', ',', $tagnamestring);
	$tagnamestring = str_replace('、', ',', $tagnamestring);
	$tagnamestring = strip_tags($tagnamestring);
	$tagnamestring = trim($tagnamestring);
	if ($tagnamestring == '')
		return '';
	if ($tagnamestring == ',')
		return '';
	$a = explode(',', $tagnamestring);
	$b = array();
	foreach ($a as &$value) {
		$value = trim($value);
		if ($value)	$b[] = $value;
	}
	$b = array_unique($b);
	$b = array_slice($b, 0, 20);
	$c = array();

	$t = $ablog->LoadTagsByNameString($tagnamestring);
	foreach ($t as $key => $value) {
		$c[] = $key;
	}
	$d = array_diff($b, $c);
	if ($ablog->CheckRights('TagNew')) {
		foreach ($d as $key) {
			$tag = new Tag;
			$tag->Name = $key;
			FilterTag($tag);
			$tag->Save();
			$ablog->tags[$tag->ID] = $tag;
			$ablog->tagsbyname[$tag->Name] =& $ablog->tags[$tag->ID];
		}
	}

	foreach ($b as $key) {
		if (!isset($ablog->tagsbyname[$key])) continue;
		$s .= '{' . $ablog->tagsbyname[$key]->ID . '}';
	}

	return $s;
}

/**
 * 删除文章下所有评论
 * @param int $id 文章ID
 */
function DelArticle_Comments($id) {
	global $ablog;

	$sql = $ablog->db->sql->Delete($ablog->table['Comment'], array(array('=', 'comm_LogID', $id)));
	$ablog->db->Delete($sql);
}

################################################################################################################
/**
 * 提交页面数据
 * @return bool
 */
function PostPage() {
	global $ablog;
	if (!isset($_POST['ID'])) return;

	if (isset($_POST['PostTime'])) {
		$_POST['PostTime'] = strtotime($_POST['PostTime']);
	}

	if (!isset($_POST['AuthorID'])) {
		$_POST['AuthorID'] = $ablog->user->ID;
	} else {
		if (($_POST['AuthorID'] != $ablog->user->ID) && (!$ablog->CheckRights('PageAll'))) {
			$_POST['AuthorID'] = $ablog->user->ID;
		}
	}

	if (isset($_POST['Alias'])) {
		$_POST['Alias'] = TransferHTML($_POST['Alias'], '[noscript]');
	}

	$article = new Post();
	$pre_author = null;
	if (GetVars('ID', 'POST') == 0) {
	} else {
		$article->LoadInfoByID(GetVars('ID', 'POST'));
		if (($article->AuthorID != $ablog->user->ID) && (!$ablog->CheckRights('PageAll'))) {
			$ablog->ShowError(6, __FILE__, __LINE__);
		}
		$pre_author = $article->AuthorID;
	}

	foreach ($ablog->datainfo['Post'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$article->$key = GetVars($key, 'POST');
		}
	}

	$article->Type = ZC_POST_TYPE_PAGE;

	foreach ($GLOBALS['Filter_Plugin_PostPage_Core'] as $fpname => &$fpsignal) {
		$fpname($article);
	}

	FilterPost($article);
	FilterMeta($article);

	$article->Save();

	CountMemberArray(array($pre_author, $article->AuthorID));
	CountPostArray(array($article->ID));

	$ablog->AddBuildModule('comments');

	if (GetVars('AddNavbar', 'POST') == 0)
		$ablog->DelItemToNavbar('page', $article->ID);
	if (GetVars('AddNavbar', 'POST') == 1)
		$ablog->AddItemToNavbar('page', $article->ID, $article->Title, $article->Url);

	foreach ($GLOBALS['Filter_Plugin_PostPage_Succeed'] as $fpname => &$fpsignal)
		$fpname($article);

	return true;
}

/**
 * 删除页面
 * @return bool
 */
function DelPage() {
	global $ablog;

	$id = (int)GetVars('id', 'GET');

	$article = new Post();
	$article->LoadInfoByID($id);
	if ($article->ID > 0) {

		if (!$ablog->CheckRights('PageAll') && $article->AuthorID != $ablog->user->ID)
			$ablog->ShowError(6, __FILE__, __LINE__);

		$pre_author = $article->AuthorID;

		$article->Del();

		DelArticle_Comments($article->ID);

		CountMemberArray(array($pre_author));

		$ablog->AddBuildModule('comments');

		$ablog->DelItemToNavbar('page', $article->ID);

		foreach ($GLOBALS['Filter_Plugin_DelPage_Succeed'] as $fpname => &$fpsignal)
			$fpname($article);
	} else {

	}

	return true;
}

################################################################################################################
/**
 * 提交评论
 * @return bool
 */
function PostComment() {
	global $ablog;

	$_POST['LogID'] = $_GET['postid'];

	if ($ablog->VerifyCmtKey($_GET['postid'], $_GET['key']) == false)
		$ablog->ShowError(43, __FILE__, __LINE__);

	if ($ablog->option['ZC_COMMENT_VERIFY_ENABLE']) {
		if ($ablog->user->ID == 0) {
			if ($ablog->CheckValidCode($_POST['verify'], 'cmt') == false)
				$ablog->ShowError(38, __FILE__, __LINE__);
		}
	}

	$replyid = (integer)GetVars('replyid', 'POST');

	if ($replyid == 0) {
		$_POST['RootID'] = 0;
		$_POST['ParentID'] = 0;
	} else {
		$_POST['ParentID'] = $replyid;
		$c = $ablog->GetCommentByID($replyid);
		if ($c->Level == 3) {
			$ablog->ShowError(52, __FILE__, __LINE__);
		}
		$_POST['RootID'] = Comment::GetRootID($c->ID);
	}

	$_POST['AuthorID'] = $ablog->user->ID;
	$_POST['Name'] = $_POST['name'];
	if($ablog->user->ID > 0)$_POST['Name'] = $ablog->user->Name;
	$_POST['Email'] = $_POST['email'];
	$_POST['HomePage'] = $_POST['homepage'];
	$_POST['Content'] = $_POST['content'];
	$_POST['PostTime'] = Time();
	$_POST['IP'] = GetGuestIP();
	$_POST['Agent'] = GetGuestAgent();

	$cmt = new Comment();

	foreach ($ablog->datainfo['Comment'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if ($key == 'IsChecking') continue;
		if (isset($_POST[$key])) {
			$cmt->$key = GetVars($key, 'POST');
		}
	}

	foreach ($GLOBALS['Filter_Plugin_PostComment_Core'] as $fpname => &$fpsignal) {
		$fpname($cmt);
	}

	FilterComment($cmt);

	if ($cmt->IsThrow == false) {

		$cmt->Save();

		if ($cmt->IsChecking == false) {

			CountPostArray(array($cmt->LogID));

			$ablog->AddBuildModule('comments');

			$ablog->comments[$cmt->ID] = $cmt;

			if (GetVars('isajax', 'POST')) {
				ViewComment($cmt->ID);
			}

			foreach ($GLOBALS['Filter_Plugin_PostComment_Succeed'] as $fpname => &$fpsignal)
				$fpname($cmt);

			return true;

		} else {

			$ablog->ShowError(53, __FILE__, __LINE__);

		}

	} else {

		$ablog->ShowError(14, __FILE__, __LINE__);

	}
}

/**
 * 删除评论
 * @return bool
 */
function DelComment() {
	global $ablog;

	$id = (int)GetVars('id', 'GET');
	$cmt = $ablog->GetCommentByID($id);
	if ($cmt->ID > 0) {

		$comments = $ablog->GetCommentList('*', array(array('=', 'comm_LogID', $cmt->LogID)), null, null, null);

		DelComment_Children($cmt->ID);

		$cmt->Del();
		
		CountPostArray(array($cmt->LogID));

		$ablog->AddBuildModule('comments');

		foreach ($GLOBALS['Filter_Plugin_DelComment_Succeed'] as $fpname => &$fpsignal)
			$fpname($cmt);
	}

	return true;
}

/**
 * 删除评论下的子评论
 * @param int $id 父评论ID
 */
function DelComment_Children($id) {
	global $ablog;

	$cmt = $ablog->GetCommentByID($id);

	foreach ($cmt->Comments as $comment) {
		if (Count($comment->Comments) > 0) {
			DelComment_Children($comment->ID);
		}
		$comment->Del();
	}

}

/**
 * 删除评论保留其子评论
 * @param int $id 父评论ID
 * @param array $array 将子评论ID存入新数组
 */
function DelComment_Children_NoDel($id, &$array) {
	global $ablog;

	$cmt = $ablog->GetCommentByID($id);

	foreach ($cmt->Comments as $comment) {
		$array[] = $comment->ID;
		if (Count($comment->Comments) > 0) {
			DelComment_Children_NoDel($comment->ID, $array);
		}
	}

}

/**
 *检查评论数据并保存、更新计数、更新“最新评论”模块
 */
function CheckComment() {
	global $ablog;

	$id = (int)GetVars('id', 'GET');
	$ischecking = (bool)GetVars('ischecking', 'GET');

	$cmt = $ablog->GetCommentByID($id);
	$cmt->IsChecking = $ischecking;

	$cmt->Save();

	CountPostArray(array($cmt->LogID));
	$ablog->AddBuildModule('comments');
}

/**
 * 评论批量处理（删除、通过审核、加入审核）
 */
function BatchComment() {
	global $ablog;
	if (isset($_POST['all_del'])) {
		$type = 'all_del';
	}
	if (isset($_POST['all_pass'])) {
		$type = 'all_pass';
	}
	if (isset($_POST['all_audit'])) {
		$type = 'all_audit';
	}
	$array = array();
	$array = $_POST['id'];
	if ($type == 'all_del') {
		$arrpost = array();
		foreach ($array as $i => $id) {
			$cmt = $ablog->GetCommentByID($id);
			if ($cmt->ID == 0)
				continue;
			$arrpost[] = $cmt->LogID;
		}
		$arrpost = array_unique($arrpost);
		foreach ($arrpost as $i => $id)
			$comments = $ablog->GetCommentList('*', array(array('=', 'comm_LogID', $id)), null, null, null);

		$arrdel = array();
		foreach ($array as $i => $id) {
			$cmt = $ablog->GetCommentByID($id);
			if ($cmt->ID == 0)
				continue;
			$arrdel[] = $cmt->ID;
			DelComment_Children_NoDel($cmt->ID, $arrdel);
		}
		foreach ($arrdel as $i => $id) {
			$cmt = $ablog->GetCommentByID($id);
			$cmt->Del();
		}
	}
	if ($type == 'all_pass')
		foreach ($array as $i => $id) {
			$cmt = $ablog->GetCommentByID($id);
			if ($cmt->ID == 0)
				continue;
			$cmt->IsChecking = false;
			$cmt->Save();
		}
	if ($type == 'all_audit')
		foreach ($array as $i => $id) {
			$cmt = $ablog->GetCommentByID($id);
			if ($cmt->ID == 0)
				continue;
			$cmt->IsChecking = true;
			$cmt->Save();
		}
}

################################################################################################################
/**
 * 提交分类数据
 * @return bool
 */
function PostCategory() {
	global $ablog;
	if (!isset($_POST['ID'])) return;

	if (isset($_POST['Alias'])) {
		$_POST['Alias'] = TransferHTML($_POST['Alias'], '[noscript]');
	}

	$parentid = (int)GetVars('ParentID', 'POST');
	if ($parentid > 0) {
		if ($ablog->categorys[$parentid]->Level > 2) {
			$_POST['ParentID'] = '0';
		}
	}

	$cate = new Category();
	if (GetVars('ID', 'POST') == 0) {
	} else {
		$cate->LoadInfoByID(GetVars('ID', 'POST'));
	}

	foreach ($ablog->datainfo['Category'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$cate->$key = GetVars($key, 'POST');
		}
	}

	foreach ($GLOBALS['Filter_Plugin_PostCategory_Core'] as $fpname => &$fpsignal) {
		$fpname($cate);
	}

	FilterCategory($cate);
	FilterMeta($cate);

	CountCategory($cate);

	$cate->Save();

	$ablog->LoadCategorys();
	$ablog->AddBuildModule('catalog');

	if (GetVars('AddNavbar', 'POST') == 0)
		$ablog->DelItemToNavbar('category', $cate->ID);
	if (GetVars('AddNavbar', 'POST') == 1)
		$ablog->AddItemToNavbar('category', $cate->ID, $cate->Name, $cate->Url);

	foreach ($GLOBALS['Filter_Plugin_PostCategory_Succeed'] as $fpname => &$fpsignal)
		$fpname($cate);

	return true;
}

/**
 * 删除分类
 * @return bool
 */
function DelCategory() {
	global $ablog;

	$id = (int)GetVars('id', 'GET');
	$cate = $ablog->GetCategoryByID($id);
	if ($cate->ID > 0) {
		DelCategory_Articles($cate->ID);
		$cate->Del();

		$ablog->LoadCategorys();
		$ablog->AddBuildModule('catalog');
		$ablog->DelItemToNavbar('category', $cate->ID);

		foreach ($GLOBALS['Filter_Plugin_DelCategory_Succeed'] as $fpname => &$fpsignal)
			$fpname($cate);
	}

	return true;
}

/**
 * 删除分类下所有文章
 * @param int $id 分类ID
 */
function DelCategory_Articles($id) {
	global $ablog;

	$sql = $ablog->db->sql->Update($ablog->table['Post'], array('log_CateID' => 0), array(array('=', 'log_CateID', $id)));
	$ablog->db->Update($sql);
}

################################################################################################################
/**
 * 提交标签数据
 * @return bool
 */
function PostTag() {
	global $ablog;
	if (!isset($_POST['ID'])) return;

	if (isset($_POST['Alias'])) {
		$_POST['Alias'] = TransferHTML($_POST['Alias'], '[noscript]');
	}

	$tag = new Tag();
	if (GetVars('ID', 'POST') == 0) {
	} else {
		$tag->LoadInfoByID(GetVars('ID', 'POST'));
	}

	foreach ($ablog->datainfo['Tag'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$tag->$key = GetVars($key, 'POST');
		}
	}

	foreach ($GLOBALS['Filter_Plugin_PostTag_Core'] as $fpname => &$fpsignal) {
		$fpname($tag);
	}

	FilterTag($tag);
	FilterMeta($tag);

	CountTag($tag);

	$tag->Save();

	if (GetVars('AddNavbar', 'POST') == 0)
		$ablog->DelItemToNavbar('tag', $tag->ID);
	if (GetVars('AddNavbar', 'POST') == 1)
		$ablog->AddItemToNavbar('tag', $tag->ID, $tag->Name, $tag->Url);

	$ablog->AddBuildModule('tags');

	foreach ($GLOBALS['Filter_Plugin_PostTag_Succeed'] as $fpname => &$fpsignal)
		$fpname($tag);

	return true;
}

/**
 * 删除标签
 * @return bool
 */
function DelTag() {
	global $ablog;

	$tagid = (int)GetVars('id', 'GET');
	$tag = $ablog->GetTagByID($tagid);
	if ($tag->ID > 0) {
		$tag->Del();
		$ablog->DelItemToNavbar('tag', $tag->ID);
		$ablog->AddBuildModule('tags');
		foreach ($GLOBALS['Filter_Plugin_DelTag_Succeed'] as $fpname => &$fpsignal)
			$fpname($tag);
	}

	return true;
}

################################################################################################################
/**
 * 提交用户数据
 * @return bool
 */
function PostMember() {
	global $ablog;
	if (!isset($_POST['ID'])) return;

	if (!$ablog->CheckRights('MemberAll')) {
		unset($_POST['Level']);
		unset($_POST['Name']);
		unset($_POST['Status']);
	}
	if (isset($_POST['Password'])) {
		if ($_POST['Password'] == '') {
			unset($_POST['Password']);
		} else {
			if (strlen($_POST['Password']) < $ablog->option['ZC_PASSWORD_MIN'] || strlen($_POST['Password']) > $ablog->option['ZC_PASSWORD_MAX']) {
				$ablog->ShowError(54, __FILE__, __LINE__);
			}
			if (!CheckRegExp($_POST['Password'], '[password]')) {
				$ablog->ShowError(54, __FILE__, __LINE__);
			}
			$_POST['Password'] = Member::GetPassWordByGuid($_POST['Password'], $_POST['Guid']);
		}
	}

	if (isset($_POST['Name'])) {
		if (isset($ablog->membersbyname[$_POST['Name']])) {
			if ($ablog->membersbyname[$_POST['Name']]->ID <> $_POST['ID']) {
				$ablog->ShowError(62, __FILE__, __LINE__);
			}
		}
	}

	if (isset($_POST['Alias'])) {
		$_POST['Alias'] = TransferHTML($_POST['Alias'], '[noscript]');
	}

	$mem = new Member();
	if (GetVars('ID', 'POST') == 0) {
		if (isset($_POST['Password']) == false || $_POST['Password'] == '') {
			$ablog->ShowError(73, __FILE__, __LINE__);
		}
		$_POST['IP'] = GetGuestIP();
	} else {
		$mem->LoadInfoByID(GetVars('ID', 'POST'));
	}

	if ($ablog->CheckRights('MemberAll')) {
		if ($mem->ID == $ablog->user->ID) {
			unset($_POST['Level']);
			unset($_POST['Status']);
		}
	}

	foreach ($ablog->datainfo['Member'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$mem->$key = GetVars($key, 'POST');
		}
	}

	foreach ($GLOBALS['Filter_Plugin_PostMember_Core'] as $fpname => &$fpsignal) {
		$fpname($mem);
	}

	FilterMember($mem);
	FilterMeta($mem);

	CountMember($mem);

	$mem->Save();

	foreach ($GLOBALS['Filter_Plugin_PostMember_Succeed'] as $fpname => &$fpsignal)
		$fpname($mem);

	if (isset($_POST['Password'])) {
		if ($mem->ID == $ablog->user->ID) {
			Redirect($ablog->host . 'system/cmd.php?act=login');
		}
	}

	return true;
}

/**
 * 删除用户
 * @return bool
 */
function DelMember() {
	global $ablog;

	$id = (int)GetVars('id', 'GET');
	$mem = $ablog->GetMemberByID($id);
	if ($mem->ID > 0 && $mem->ID <> $ablog->user->ID) {
		DelMember_AllData($id);
		$mem->Del();
		foreach ($GLOBALS['Filter_Plugin_DelMember_Succeed'] as $fpname => &$fpsignal)
			$fpname($mem);
	} else {
		return false;
	}

	return true;
}

/**
 * 删除用户下所有数据（包括文章、评论、附件）
 * @param int $id 用户ID
 */
function DelMember_AllData($id) {
	global $ablog;

	$w = array();
	$w[] = array('=', 'log_AuthorID', $id);

	$articles = $ablog->GetPostList('*', $w);
	foreach ($articles as $a) {
		$a->Del();
	}

	$w = array();
	$w[] = array('=', 'comm_AuthorID', $id);
	$comments = $ablog->GetCommentList('*', $w);
	foreach ($comments as $c) {
		$c->AuthorID = 0;
		$c->Save();
	}

	$w = array();
	$w[] = array('=', 'ul_AuthorID', $id);
	$uploads = $ablog->GetUploadList('*', $w);
	foreach ($uploads as $u) {
		$u->Del();
		$u->DelFile();
	}

}

################################################################################################################
/**
 * 提交模块数据
 * @return bool
 */
function PostModule() {
	global $ablog;

	if (isset($_POST['catalog_style'])) {
		$ablog->option['ZC_MODULE_CATALOG_STYLE'] = $_POST['catalog_style'];
		$ablog->SaveOption();
	}

	if (!isset($_POST['ID'])) return;
	if (!GetVars('FileName', 'POST')) {
		$_POST['FileName'] = 'mod' . rand(1000, 2000);
	} else {
		$_POST['FileName'] = strtolower($_POST['FileName']);
	}
	if (!GetVars('HtmlID', 'POST')) {
		$_POST['HtmlID'] = $_POST['FileName'];
	}
	if (isset($_POST['MaxLi'])) {
		$_POST['MaxLi'] = (integer)$_POST['MaxLi'];
	}
	if (isset($_POST['IsHideTitle'])) {
		$_POST['IsHideTitle'] = (integer)$_POST['IsHideTitle'];
	}
	if (!isset($_POST['Type'])) {
		$_POST['Type'] = 'div';
	}
	if (isset($_POST['Content'])) {
		if ($_POST['Type'] != 'div') {
			$_POST['Content'] = str_replace(array("\r", "\n"), array('', ''), $_POST['Content']);
		}
	}
	if (isset($_POST['Source'])) {
		if ($_POST['Source'] == 'theme') {
			$c = GetVars('Content', 'POST');
			$d = $ablog->usersdir . 'theme/' . $ablog->theme . '/include/';
			$f = $d . GetVars('FileName', 'POST') . '.php';
			if(!file_exists($d)){
				@mkdir($d,0755);
			}
			@file_put_contents($f, $c);
			return true;
		}
	}
	
	$mod = $ablog->GetModuleByID(GetVars('ID', 'POST'));

	foreach ($ablog->datainfo['Module'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$mod->$key = GetVars($key, 'POST');
		}
	}

	if (isset($_POST['NoRefresh'])) {
		$mod->NoRefresh = (bool)$_POST['NoRefresh'];
	}
	
	foreach ($GLOBALS['Filter_Plugin_PostModule_Core'] as $fpname => &$fpsignal) {
		$fpname($mod);
	}

	FilterModule($mod);

	$mod->Save();

	$ablog->AddBuildModule($mod->FileName);

	foreach ($GLOBALS['Filter_Plugin_PostModule_Succeed'] as $fpname => &$fpsignal)
		$fpname($mod);

	return true;
}

/**
 * 删除模块
 * @return bool
 */
function DelModule() {
	global $ablog;

	if (GetVars('source', 'GET') == 'theme') {
		if (GetVars('filename', 'GET')) {
			$f = $ablog->usersdir . 'theme/' . $ablog->theme . '/include/' . GetVars('filename', 'GET') . '.php';
			if (file_exists($f))
				@unlink($f);

			return true;
		}

		return false;
	}

	$id = (int)GetVars('id', 'GET');
	$mod = $ablog->GetModuleByID($id);
	if ($mod->Source <> 'system') {
		$mod->Del();
		foreach ($GLOBALS['Filter_Plugin_DelModule_Succeed'] as $fpname => &$fpsignal)
			$fpname($mod);
	} else {
		return false;
	}

	return true;
}

################################################################################################################
/**
 * 附件上传
 */
function PostUpload() {
	global $ablog;

	foreach ($_FILES as $key => $value) {
		if ($_FILES[$key]['error'] == 0) {
			if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
				$tmp_name = $_FILES[$key]['tmp_name'];
				$name = $_FILES[$key]['name'];

				$upload = new Upload;
				$upload->Name = $_FILES[$key]['name'];
				$upload->SourceName = $_FILES[$key]['name'];
				$upload->MimeType = $_FILES[$key]['type'];
				$upload->Size = $_FILES[$key]['size'];
				$upload->AuthorID = $ablog->user->ID;

				if (!$upload->CheckExtName())
					$ablog->ShowError(26, __FILE__, __LINE__);
				if (!$upload->CheckSize())
					$ablog->ShowError(27, __FILE__, __LINE__);

				$upload->SaveFile($_FILES[$key]['tmp_name']);
				$upload->Save();
			}
		}
	}
	if (isset($upload))
		CountMemberArray(array($upload->AuthorID));

}

/**
 * 删除附件
 * @return bool
 */
function DelUpload() {
	global $ablog;

	$id = (int)GetVars('id', 'GET');
	$u = $ablog->GetUploadByID($id);
	if ($ablog->CheckRights('UploadAll') || (!$ablog->CheckRights('UploadAll') && $u->AuthorID == $ablog->user->ID)) {
		$u->Del();
		CountMemberArray(array($u->AuthorID));
		$u->DelFile();
	} else {
		return false;
	}

	return true;
}

################################################################################################################
/**
 * 启用插件
 * @param string $name 插件ID
 * @return string 返回插件ID
 */
function EnablePlugin($name) {
	global $ablog;

	$app=$ablog->LoadApp('plugin',$name);
	$app->CheckCompatibility();

	$ablog->option['ZC_USING_PLUGIN_LIST'] = AddNameInString($ablog->option['ZC_USING_PLUGIN_LIST'], $name);
	$ablog->SaveOption();

	return $name;
}

/**
 * 禁用插件
 * @param string $name 插件ID
 */
function DisablePlugin($name) {
	global $ablog;
	$ablog->option['ZC_USING_PLUGIN_LIST'] = DelNameInString($ablog->option['ZC_USING_PLUGIN_LIST'], $name);
	$ablog->SaveOption();
}

/**
 * 设置当前主题样式
 * @param string $theme 主题ID
 * @param string $style 样式名
 * @return string 返回主题ID
 */
function SetTheme($theme, $style) {
	global $ablog;
	
	$app=$ablog->LoadApp('theme',$theme);
	$app->CheckCompatibility();
	
	$oldtheme = $ablog->option['ZC_BLOG_THEME'];

	if ($oldtheme != $theme) {
		$app = $ablog->LoadApp('theme', $theme);
		if ($app->sidebars_sidebar1 | $app->sidebars_sidebar2 | $app->sidebars_sidebar3 | $app->sidebars_sidebar4 | $app->sidebars_sidebar5) {
			$s1 = $ablog->option['ZC_SIDEBAR_ORDER'];
			$s2 = $ablog->option['ZC_SIDEBAR2_ORDER'];
			$s3 = $ablog->option['ZC_SIDEBAR3_ORDER'];
			$s4 = $ablog->option['ZC_SIDEBAR4_ORDER'];
			$s5 = $ablog->option['ZC_SIDEBAR5_ORDER'];
			$ablog->option['ZC_SIDEBAR_ORDER'] = $app->sidebars_sidebar1;
			$ablog->option['ZC_SIDEBAR2_ORDER'] = $app->sidebars_sidebar2;
			$ablog->option['ZC_SIDEBAR3_ORDER'] = $app->sidebars_sidebar3;
			$ablog->option['ZC_SIDEBAR4_ORDER'] = $app->sidebars_sidebar4;
			$ablog->option['ZC_SIDEBAR5_ORDER'] = $app->sidebars_sidebar5;
			$ablog->cache->ZC_SIDEBAR_ORDER1 = $s1;
			$ablog->cache->ZC_SIDEBAR_ORDER2 = $s2;
			$ablog->cache->ZC_SIDEBAR_ORDER3 = $s3;
			$ablog->cache->ZC_SIDEBAR_ORDER4 = $s4;
			$ablog->cache->ZC_SIDEBAR_ORDER5 = $s5;
			$ablog->SaveCache();
		} else {
			if ($ablog->cache->ZC_SIDEBAR_ORDER1 | $ablog->cache->ZC_SIDEBAR_ORDER2 | $ablog->cache->ZC_SIDEBAR_ORDER3 | $ablog->cache->ZC_SIDEBAR_ORDER4 | $ablog->cache->ZC_SIDEBAR_ORDER5) {
				$ablog->option['ZC_SIDEBAR_ORDER'] = $ablog->cache->ZC_SIDEBAR_ORDER1;
				$ablog->option['ZC_SIDEBAR2_ORDER'] = $ablog->cache->ZC_SIDEBAR_ORDER2;
				$ablog->option['ZC_SIDEBAR3_ORDER'] = $ablog->cache->ZC_SIDEBAR_ORDER3;
				$ablog->option['ZC_SIDEBAR4_ORDER'] = $ablog->cache->ZC_SIDEBAR_ORDER4;
				$ablog->option['ZC_SIDEBAR5_ORDER'] = $ablog->cache->ZC_SIDEBAR_ORDER5;
				$ablog->cache->ZC_SIDEBAR_ORDER1 = '';
				$ablog->cache->ZC_SIDEBAR_ORDER2 = '';
				$ablog->cache->ZC_SIDEBAR_ORDER3 = '';
				$ablog->cache->ZC_SIDEBAR_ORDER4 = '';
				$ablog->cache->ZC_SIDEBAR_ORDER5 = '';
				$ablog->SaveCache();
			}
		}

	}

	$ablog->option['ZC_BLOG_THEME'] = $theme;
	$ablog->option['ZC_BLOG_CSS'] = $style;

	$ablog->BuildTemplate();

	$ablog->SaveOption();

	if ($oldtheme != $theme) {
		UninstallPlugin($oldtheme);

		return $theme;
	}
}

/**
 * 设置侧栏
 */
function SetSidebar() {
	global $ablog;

	$ablog->option['ZC_SIDEBAR_ORDER'] = trim(GetVars('sidebar', 'POST'), '|');
	$ablog->option['ZC_SIDEBAR2_ORDER'] = trim(GetVars('sidebar2', 'POST'), '|');
	$ablog->option['ZC_SIDEBAR3_ORDER'] = trim(GetVars('sidebar3', 'POST'), '|');
	$ablog->option['ZC_SIDEBAR4_ORDER'] = trim(GetVars('sidebar4', 'POST'), '|');
	$ablog->option['ZC_SIDEBAR5_ORDER'] = trim(GetVars('sidebar5', 'POST'), '|');
	$ablog->SaveOption();
}

/**
 * 保存网站设置选项
 */
function SaveSetting() {
	global $ablog;

	foreach ($_POST as $key => $value) {
		if (substr($key, 0, 2) !== 'ZC') continue;
		if ($key == 'ZC_PERMANENT_DOMAIN_ENABLE' || 
			$key == 'ZC_DEBUG_MODE' || 
			$key == 'ZC_COMMENT_TURNOFF' || 
			$key == 'ZC_COMMENT_REVERSE_ORDER' || 
			$key == 'ZC_DISPLAY_SUBCATEGORYS' || 
			$key == 'ZC_GZIP_ENABLE' ||
			$key == 'ZC_SYNTAXHIGHLIGHTER_ENABLE' ||
			$key == 'ZC_COMMENT_VERIFY_ENABLE'
		) {
			$ablog->option[$key] = (boolean)$value;
			continue;
		}
		if ($key == 'ZC_RSS2_COUNT' || 
			$key == 'ZC_UPLOAD_FILESIZE' || 
			$key == 'ZC_DISPLAY_COUNT' || 
			$key == 'ZC_SEARCH_COUNT' || 
			$key == 'ZC_PAGEBAR_COUNT' || 
			$key == 'ZC_COMMENTS_DISPLAY_COUNT' || 
			$key == 'ZC_MANAGE_COUNT'
		) {
			$ablog->option[$key] = (integer)$value;
			continue;
		}
		if ($key == 'ZC_UPLOAD_FILETYPE'){
			$value = strtolower($value);
			$value = DelNameInString($value, 'php');
			$value = DelNameInString($value, 'asp');
		}
		$ablog->option[$key] = trim(str_replace(array("\r", "\n"), array("", ""), $value));
	}

	$ablog->option['ZC_BLOG_HOST'] = trim($ablog->option['ZC_BLOG_HOST']);
	$ablog->option['ZC_BLOG_HOST'] = trim($ablog->option['ZC_BLOG_HOST'], '/') . '/';
	$lang = require($ablog->usersdir . 'language/' . $ablog->option['ZC_BLOG_LANGUAGEPACK'] . '.php');
	$ablog->option['ZC_BLOG_LANGUAGE'] = $lang['lang'];
	$ablog->option['ZC_BLOG_PRODUCT'] = 'Z-BlogPHP';	
	$ablog->SaveOption();
}

################################################################################################################
/**
 * 过滤扩展数据
 * @param $object
 */
function FilterMeta(&$object) {

	//$type=strtolower(get_class($object));

	foreach ($_POST as $key => $value) {
		if (substr($key, 0, 5) == 'meta_') {
			$name = substr($key, 5 - strlen($key));
			$object->Metas->$name = $value;
		}
	}

	foreach ($object->Metas->Data as $key => $value) {
		if ($value == "")
			unset($object->Metas->Data[$key]);
	}

}

/**
 * 过滤评论数据
 * @param $comment
 */
function FilterComment(&$comment) {
	global $ablog;

	if (!CheckRegExp($comment->Name, '[username]')) {
		$ablog->ShowError(15, __FILE__, __LINE__);
	}
	if ($comment->Email && (!CheckRegExp($comment->Email, '[email]'))) {
		$ablog->ShowError(29, __FILE__, __LINE__);
	}
	if ($comment->HomePage && (!CheckRegExp($comment->HomePage, '[homepage]'))) {
		$ablog->ShowError(30, __FILE__, __LINE__);
	}

	$comment->Name = substr($comment->Name, 0, 20);
	$comment->Email = substr($comment->Email, 0, 30);
	$comment->HomePage = substr($comment->HomePage, 0, 100);

	$comment->Content = TransferHTML($comment->Content, '[nohtml]');

	$comment->Content = substr($comment->Content, 0, 1000);
	$comment->Content = trim($comment->Content);
	if (strlen($comment->Content) == 0) {
		$ablog->ShowError(46, __FILE__, __LINE__);
	}
}

/**
 * 过滤文章数据
 * @param $article
 */
function FilterPost(&$article) {
	global $ablog;

	$article->Title = strip_tags($article->Title);
	$article->Alias = TransferHTML($article->Alias, '[normalname]');
	$article->Alias = str_replace(' ', '', $article->Alias);

	if ($article->Type == ZC_POST_TYPE_ARTICLE) {
		if (!$ablog->CheckRights('ArticleAll')) {
			$article->Content = TransferHTML($article->Content, '[noscript]');
			$article->Intro = TransferHTML($article->Intro, '[noscript]');
		}
	} elseif ($article->Type == ZC_POST_TYPE_PAGE) {
		if (!$ablog->CheckRights('PageAll')) {
			$article->Content = TransferHTML($article->Content, '[noscript]');
			$article->Intro = TransferHTML($article->Intro, '[noscript]');
		}
	}
}

/**
 * 过滤用户数据
 * @param $member
 */
function FilterMember(&$member) {
	global $ablog;
	$member->Intro = TransferHTML($member->Intro, '[noscript]');
	$member->Alias = TransferHTML($member->Alias, '[normalname]');
	$member->Alias = str_replace('/', '', $member->Alias);
	$member->Alias = str_replace('.', '', $member->Alias);
	$member->Alias = str_replace(' ', '', $member->Alias);
	$member->Alias = str_replace('_', '', $member->Alias);
	if (strlen($member->Name) < $ablog->option['ZC_USERNAME_MIN'] || strlen($member->Name) > $ablog->option['ZC_USERNAME_MAX']) {
		$ablog->ShowError(77, __FILE__, __LINE__);
	}

	if (!CheckRegExp($member->Name, '[username]')) {
		$ablog->ShowError(77, __FILE__, __LINE__);
	}

	if (!CheckRegExp($member->Email, '[email]')) {
		$member->Email = 'null@null.com';
	}

	if (substr($member->HomePage, 0, 4) != 'http') {
		$member->HomePage = 'http://' . $member->HomePage;
	}

	if (!CheckRegExp($member->HomePage, '[homepage]')) {
		$member->HomePage = '';
	}

	if (strlen($member->Email) > $ablog->option['ZC_EMAIL_MAX']) {
		$ablog->ShowError(29, __FILE__, __LINE__);
	}

	if (strlen($member->HomePage) > $ablog->option['ZC_HOMEPAGE_MAX']) {
		$ablog->ShowError(30, __FILE__, __LINE__);
	}

}

/**
 * 过滤模块数据
 * @param $module
 */
function FilterModule(&$module) {
	global $ablog;
	$module->FileName = TransferHTML($module->FileName, '[filename]');
	$module->HtmlID = TransferHTML($module->HtmlID, '[normalname]');
}

/**
 * 过滤分类数据
 * @param $category
 */
function FilterCategory(&$category) {
	global $ablog;
	$category->Name = strip_tags($category->Name);
	$category->Alias = TransferHTML($category->Alias, '[normalname]');
	//$category->Alias=str_replace('/','',$category->Alias);
	$category->Alias = str_replace('.', '', $category->Alias);
	$category->Alias = str_replace(' ', '', $category->Alias);
	$category->Alias = str_replace('_', '', $category->Alias);
}

/**
 * 过滤tag数据
 * @param $tag
 */
function FilterTag(&$tag) {
	global $ablog;
	$tag->Name = strip_tags($tag->Name);
	$tag->Alias = TransferHTML($tag->Alias, '[normalname]');
}

################################################################################################################
#统计函数
/**
 *统计公开文章数
 */
function CountNormalArticleNums() {
	global $ablog;
	$s = $ablog->db->sql->Count($ablog->table['Post'], array(array('COUNT', '*', 'num')), array(array('=', 'log_Type', 0), array('=', 'log_IsTop', 0), array('=', 'log_Status', 0)));
	$num = GetValueInArrayByCurrent($ablog->db->Query($s), 'num');

	$ablog->cache->normal_article_nums = $num;
	$ablog->SaveCache();
}

/**
 * 统计文章下评论数
 * @param post $article
 */
function CountPost(&$article) {
	global $ablog;

	$id = $article->ID;

	$s = $ablog->db->sql->Count($ablog->table['Comment'], array(array('COUNT', '*', 'num')), array(array('=', 'comm_LogID', $id), array('=', 'comm_IsChecking', 0)));
	$num = GetValueInArrayByCurrent($ablog->db->Query($s), 'num');

	$article->CommNums = $num;
}

/**
 * 批量统计指定文章下评论数并保存
 * @param array $array 记录文章ID的数组
 */
function CountPostArray($array) {
	global $ablog;
	$array = array_unique($array);
	foreach ($array as $value) {
		if ($value == 0) continue;
		$article = new Post;
		$article->LoadInfoByID($value);
		CountPost($article);
		$article->Save();
	}
}

/**
 * 统计分类下文章数
 * @param category &$category
 */
function CountCategory(&$category) {
	global $ablog;

	$id = $category->ID;

	$s = $ablog->db->sql->Count($ablog->table['Post'], array(array('COUNT', '*', 'num')), array(array('=', 'log_Type', 0), array('=', 'log_IsTop', 0), array('=', 'log_Status', 0), array('=', 'log_CateID', $id)));
	$num = GetValueInArrayByCurrent($ablog->db->Query($s), 'num');

	$category->Count = $num;
}

/**
 * 批量统计指定分类下文章数并保存
 * @param array $array 记录分类ID的数组
 */
function CountCategoryArray($array) {
	global $ablog;
	$array = array_unique($array);
	foreach ($array as $value) {
		if ($value == 0) continue;
		CountCategory($ablog->categorys[$value]);
		$ablog->categorys[$value]->Save();
	}
}

/**
 * 统计tag下的文章数
 * @param tag &$tag
 */
function CountTag(&$tag) {
	global $ablog;

	$id = $tag->ID;

	$s = $ablog->db->sql->Count($ablog->table['Post'], array(array('COUNT', '*', 'num')), array(array('LIKE', 'log_Tag', '%{' . $id . '}%')));
	$num = GetValueInArrayByCurrent($ablog->db->Query($s), 'num');

	$tag->Count = $num;
}

/**
 * 批量统计指定tag下文章数并保存
 * @param string $string 类似'{1}{2}{3}{4}{4}'的tagID串
 */
function CountTagArrayString($string) {
	global $ablog;
	$array = $ablog->LoadTagsByIDString($string);
	foreach ($array as &$tag) {
		CountTag($tag);
		$tag->Save();
	}
}

/**
 * 统计用户下的文章数、页面数、评论数、附件数等
 * @param $member
 */
function CountMember(&$member) {
	global $ablog;
	if(!($member  instanceof  Member))return;

	$id = $member->ID;

	$s = $ablog->db->sql->Count($ablog->table['Post'], array(array('COUNT', '*', 'num')), array(array('=', 'log_AuthorID', $id), array('=', 'log_Type', 0)));
	$member_Articles = GetValueInArrayByCurrent($ablog->db->Query($s), 'num');

	$s = $ablog->db->sql->Count($ablog->table['Post'], array(array('COUNT', '*', 'num')), array(array('=', 'log_AuthorID', $id), array('=', 'log_Type', 1)));
	$member_Pages = GetValueInArrayByCurrent($ablog->db->Query($s), 'num');

	$s = $ablog->db->sql->Count($ablog->table['Comment'], array(array('COUNT', '*', 'num')), array(array('=', 'comm_AuthorID', $id)));
	$member_Comments = GetValueInArrayByCurrent($ablog->db->Query($s), 'num');

	$s = $ablog->db->sql->Count($ablog->table['Upload'], array(array('COUNT', '*', 'num')), array(array('=', 'ul_AuthorID', $id)));
	$member_Uploads = GetValueInArrayByCurrent($ablog->db->Query($s), 'num');

	$member->Articles = $member_Articles;
	$member->Pages = $member_Pages;
	$member->Comments = $member_Comments;
	$member->Uploads = $member_Uploads;
}

/**
 * 批量统计指定用户数据并保存
 * @param array $array 记录用户ID的数组
 */
function CountMemberArray($array) {
	global $ablog;
	$array = array_unique($array);
	foreach ($array as $value) {
		if ($value == 0) continue;
		if(isset($ablog->members[$value])){
			CountMember($ablog->members[$value]);
			$ablog->members[$value]->Save();
		}
	}
}

################################################################################################################
#BuildModule
/**
 * 导出网站分类模块数据
 * @return string 模块内容
 */
function BuildModule_catalog() {
	global $ablog;
	$s = '';

	if ($ablog->option['ZC_MODULE_CATALOG_STYLE'] == '2') {

		foreach ($ablog->categorysbyorder as $key => $value) {
			if ($value->Level == 0) {
				$s .= '<li class="li-cate"><a href="' . $value->Url . '">' . $value->Name . '</a><!--' . $value->ID . 'begin--><!--' . $value->ID . 'end--></li>';
			}
		}
		foreach ($ablog->categorysbyorder as $key => $value) {
			if ($value->Level == 1) {
				$s = str_replace('<!--' . $value->ParentID . 'end-->', '<li class="li-subcate"><a href="' . $value->Url . '">' . $value->Name . '</a><!--' . $value->ID . 'begin--><!--' . $value->ID . 'end--></li><!--' . $value->ParentID . 'end-->', $s);
			}
		}
		foreach ($ablog->categorysbyorder as $key => $value) {
			if ($value->Level == 2) {
				$s = str_replace('<!--' . $value->ParentID . 'end-->', '<li class="li-subcate"><a href="' . $value->Url . '">' . $value->Name . '</a><!--' . $value->ID . 'begin--><!--' . $value->ID . 'end--></li><!--' . $value->ParentID . 'end-->', $s);
			}
		}
		foreach ($ablog->categorysbyorder as $key => $value) {
			if ($value->Level == 3) {
				$s = str_replace('<!--' . $value->ParentID . 'end-->', '<li class="li-subcate"><a href="' . $value->Url . '">' . $value->Name . '</a><!--' . $value->ID . 'begin--><!--' . $value->ID . 'end--></li><!--' . $value->ParentID . 'end-->', $s);
			}
		}

		foreach ($ablog->categorysbyorder as $key => $value) {
			$s = str_replace('<!--' . $value->ID . 'begin--><!--' . $value->ID . 'end-->', '', $s);
		}
		foreach ($ablog->categorysbyorder as $key => $value) {
			$s = str_replace('<!--' . $value->ID . 'begin-->', '<ul class="ul-subcates">', $s);
			$s = str_replace('<!--' . $value->ID . 'end-->', '</ul>', $s);
		}

	} elseif ($ablog->option['ZC_MODULE_CATALOG_STYLE'] == '1') {
		foreach ($ablog->categorysbyorder as $key => $value) {
			$s .= '<li>' . $value->Symbol . '<a href="' . $value->Url . '">' . $value->Name . '</a></li>';
		}
	} else {
		foreach ($ablog->categorysbyorder as $key => $value) {
			$s .= '<li><a href="' . $value->Url . '">' . $value->Name . '</a></li>';
		}
	}

	return $s;
}

/**
 * 导出日历模块数据
 * @param string $date 日期
 * @return string 模块内容
 */
function BuildModule_calendar($date = '') {
	global $ablog;

	if ($date == '')
		$date = date('Y-m', time());

	$s = '<table id="tbCalendar"><caption>';

	$url = new UrlRule($ablog->option['ZC_DATE_REGEX']);
	$value = strtotime('-1 month', strtotime($date));
	$url->Rules['{%date%}'] = date('Y-n', $value);
	$url->Rules['{%year%}'] = date('Y', $value);
	$url->Rules['{%month%}'] = date('n', $value);

	$url->Rules['{%day%}'] = 1;
	$s .= '<a href="' . $url->Make() . '">«</a>';

	$value = strtotime($date);
	$url->Rules['{%date%}'] = date('Y-n', $value);
	$url->Rules['{%year%}'] = date('Y', $value);
	$url->Rules['{%month%}'] = date('n', $value);
	$s .= '&nbsp;&nbsp;&nbsp;<a href="' . $url->Make() . '">' . str_replace(array('%y%', '%m%'), array(date('Y', $value), date('n', $value)), $ablog->lang['msg']['year_month']) . '</a>&nbsp;&nbsp;&nbsp;';

	$value = strtotime('+1 month', strtotime($date));
	$url->Rules['{%date%}'] = date('Y-n', $value);
	$url->Rules['{%year%}'] = date('Y', $value);
	$url->Rules['{%month%}'] = date('n', $value);
	$s .= '<a href="' . $url->Make() . '">»</a></caption>';

	$s .= '<thead><tr>';
	for ($i = 1; $i < 8; $i++) {
		$s .= '<th title="' . $ablog->lang['week'][$i] . '" scope="col"><small>' . $ablog->lang['week_abbr'][$i] . '</small></th>';
	}

	$s .= '</tr></thead>';
	$s .= '<tbody>';
	$s .= '<tr>';

	$a = 1;
	$b = date('t', strtotime($date));
	$j = date('N', strtotime($date . '-1'));
	$k = 7 - date('N', strtotime($date . '-' . date('t', strtotime($date))));

	if ($j > 1) {
		$s .= '<td class="pad" colspan="' . ($j - 1) . '"> </td>';
	} elseif ($j = 1) {
		$s .= '';
	}

	$l = $j - 1;
	for ($i = $a; $i < $b + 1; $i++) {
		$s .= '<td>' . $i . '</td>';

		$l = $l + 1;
		if ($l % 7 == 0)
			$s .= '</tr><tr>';
	}

	if ($k > 1) {
		$s .= '<td class="pad" colspan="' . ($k) . '"> </td>';
	} elseif ($k = 1) {
		$s .= '';
	}

	$s .= '</tr></tbody>';
	$s .= '</table>';
	$s = str_replace('<tr></tr>', '', $s);

	$fdate = strtotime($date);
	$ldate = (strtotime(date('Y-m-t', strtotime($date))) + 60 * 60 * 24);
	$sql = $ablog->db->sql->Select(
		$ablog->table['Post'],
		array('log_ID', 'log_PostTime'),
		array(
			array('=', 'log_Type', '0'),
			array('=', 'log_Status', '0'),
			array('BETWEEN', 'log_PostTime', $fdate, $ldate)
		),
		array('log_PostTime' => 'ASC'),
		null,
		null
	);
	$array = $ablog->db->Query($sql);
	$arraydate = array();
	$arrayid = array();
	foreach ($array as $key => $value) {
		$arraydate[date('j', $value['log_PostTime'])] = $value['log_ID'];
	}
	if (count($arraydate) > 0) {
		foreach ($arraydate as $key => $value) {
			$arrayid[] = array('log_ID', $value);
		}
		$articles = $ablog->GetArticleList('*', array(array('array', $arrayid)),null,null,null,false);
		foreach ($arraydate as $key => $value) {
			$a = $ablog->GetPostByID($value);
			$s = str_replace('<td>' . $key . '</td>', '<td><a href="' . $a->Url . '">' . $key . '</a></td>', $s);
		}
	}

	return $s;

}

/**
 * 导出最新留言模块数据
 * @return string 模块内容
 */
function BuildModule_comments() {
	global $ablog;

	$i = $ablog->modulesbyfilename['comments']->MaxLi;
	if ($i == 0) $i = 10;
	$comments = $ablog->GetCommentList('*', array(array('=', 'comm_IsChecking', 0)), array('comm_PostTime' => 'DESC'), $i, null);

	$s = '';
	foreach ($comments as $comment) {
		$s .= '<li><a href="' . $comment->Post->Url . '#cmt' . $comment->ID . '" title="' . htmlspecialchars($comment->Author->StaticName . ' @ ' . $comment->Time()) . '">' . TransferHTML($comment->Content, '[noenter]') . '</a></li>';
	}

	return $s;
}

/**
 * 导出最近发表文章模块数据
 * @return string 模块内容
 */
function BuildModule_previous() {
	global $ablog;

	$i = $ablog->modulesbyfilename['previous']->MaxLi;
	if ($i == 0) $i = 10;
	$articles = $ablog->GetArticleList('*', array(array('=', 'log_Type', 0), array('=', 'log_Status', 0)), array('log_PostTime' => 'DESC'), $i, null,false);
	$s = '';
	foreach ($articles as $article) {
		$s .= '<li><a href="' . $article->Url . '">' . $article->Title . '</a></li>';
	}

	return $s;
}

/**
 * 导出文章归档模块数据
 * @return string 模块内容
 */
function BuildModule_archives() {
	global $ablog;

	$i = $ablog->modulesbyfilename['archives']->MaxLi;
	if($i<0)return '';

	$fdate;
	$ldate;

	$sql = $ablog->db->sql->Select($ablog->table['Post'], array('log_PostTime'), null, array('log_PostTime' => 'DESC'), array(1), null);

	$array = $ablog->db->Query($sql);

	if (count($array) == 0)
		return '';

	$ldate = array(date('Y', $array[0]['log_PostTime']), date('m', $array[0]['log_PostTime']));

	$sql = $ablog->db->sql->Select($ablog->table['Post'], array('log_PostTime'), null, array('log_PostTime' => 'ASC'), array(1), null);

	$array = $ablog->db->Query($sql);

	if (count($array) == 0)
		return '';

	$fdate = array(date('Y', $array[0]['log_PostTime']), date('m', $array[0]['log_PostTime']));

	$arraydate = array();

	for ($i = $fdate[0]; $i < $ldate[0] + 1; $i++) {
		for ($j = 1; $j < 13; $j++) {
			$arraydate[] = strtotime($i . '-' . $j);
		}
	}

	foreach ($arraydate as $key => $value) {
		if ($value - strtotime($ldate[0] . '-' . $ldate[1]) > 0)
			unset($arraydate[$key]);
		if ($value - strtotime($fdate[0] . '-' . $fdate[1]) < 0)
			unset($arraydate[$key]);
	}

	$arraydate = array_reverse($arraydate);

	$s = '';

	foreach ($arraydate as $key => $value) {
		$url = new UrlRule($ablog->option['ZC_DATE_REGEX']);
		$url->Rules['{%date%}'] = date('Y-n', $value);
		$url->Rules['{%year%}'] = date('Y', $value);
		$url->Rules['{%month%}'] = date('n', $value);
		$url->Rules['{%day%}'] = 1;

		$fdate = $value;
		$ldate = (strtotime(date('Y-m-t', $value)) + 60 * 60 * 24);
		$sql = $ablog->db->sql->Count($ablog->table['Post'], array(array('COUNT', '*', 'num')), array(array('=', 'log_Type', '0'), array('=', 'log_Status', '0'), array('BETWEEN', 'log_PostTime', $fdate, $ldate)));
		$n = GetValueInArrayByCurrent($ablog->db->Query($sql), 'num');
		if ($n > 0) {
			$s .= '<li><a href="' . $url->Make() . '">' . str_replace(array('%y%', '%m%'), array(date('Y', $fdate), date('n', $fdate)), $ablog->lang['msg']['year_month']) . ' (' . $n . ')</a></li>';
		}
	}

	return $s;

}

/**
 * 导出导航模块数据
 * @return string 模块内容
 */
function BuildModule_navbar() {
	global $ablog;

	$s = $ablog->modulesbyfilename['navbar']->Content;

	$a = array();
	preg_match_all('/<li id="navbar-(page|category|tag)-(\d+)">/', $s, $a);

	$b = $a[1];
	$c = $a[2];
	foreach ($b as $key => $value) {

		if ($b[$key] == 'page') {

			$type = 'page';
			$id = $c[$key];
			$o = $ablog->GetPostByID($id);
			$url = $o->Url;
			$name = $o->Title;

			$a = '<li id="navbar-' . $type . '-' . $id . '"><a href="' . $url . '">' . $name . '</a></li>';
			$s = preg_replace('/<li id="navbar-' . $type . '-' . $id . '">.*?<\/a><\/li>/', $a, $s);

		}
		if ($b[$key] == 'category') {

			$type = 'category';
			$id = $c[$key];
			$o = $ablog->GetCategoryByID($id);
			$url = $o->Url;
			$name = $o->Name;

			$a = '<li id="navbar-' . $type . '-' . $id . '"><a href="' . $url . '">' . $name . '</a></li>';
			$s = preg_replace('/<li id="navbar-' . $type . '-' . $id . '">.*?<\/a><\/li>/', $a, $s);

		}
		if ($b[$key] == 'tag') {

			$type = 'tag';
			$id = $c[$key];
			$o = $ablog->GetTagByID($id);
			$url = $o->Url;
			$name = $o->Name;

			$a = '<li id="navbar-' . $type . '-' . $id . '"><a href="' . $url . '">' . $name . '</a></li>';
			$s = preg_replace('/<li id="navbar-' . $type . '-' . $id . '">.*?<\/a><\/li>/', $a, $s);

		}
	}

	return $s;
}

/**
 * 导出tags模块数据
 * @return string 模块内容
 */
function BuildModule_tags() {
	global $ablog;
	$s = '';
	$i = $ablog->modulesbyfilename['tags']->MaxLi;
	if ($i == 0) $i = 25;
	$array = $ablog->GetTagList('*', '', array('tag_Count' => 'DESC'), $i, null);
	$array2 = array();
	foreach ($array as $tag) {
		$array2[$tag->ID] = $tag;
	}
	ksort($array2);

	foreach ($array2 as $tag) {
		$s .= '<li><a href="' . $tag->Url . '">' . $tag->Name . '<span class="tag-count"> (' . $tag->Count . ')</span></a></li>';
	}

	return $s;
}

/**
 * 导出用户列表模块数据
 * @param int $level 要导出的用户最低等级，默认为4（即协作者）
 * @return string 模块内容
 */
function BuildModule_authors($level = 4) {
	global $ablog;
	$s = '';

	$w = array();
	$w[] = array('<=', 'mem_Level', $level);

	$array = $ablog->GetMemberList('*', $w, array('mem_ID' => 'ASC'), null, null);

	foreach ($array as $member) {
		$s .= '<li><a href="' . $member->Url . '">' . $member->Name . '<span class="article-nums"> (' . $member->Articles . ')</span></a></li>';
	}

	return $s;
}

/**
 * 导出网站统计模块数据
 * @param array $array
 * @return string 模块内容
 */
function BuildModule_statistics($array = array()) {
	global $ablog;
	$all_artiles = 0;
	$all_pages = 0;
	$all_categorys = 0;
	$all_tags = 0;
	$all_views = 0;
	$all_comments = 0;

	if (count($array) == 0) {
		return $ablog->modulesbyfilename['statistics']->Content;
	}

	if (isset($array[0])) $all_artiles = $array[0];
	if (isset($array[1])) $all_pages = $array[1];
	if (isset($array[2])) $all_categorys = $array[2];
	if (isset($array[3])) $all_tags = $array[3];
	if (isset($array[4])) $all_views = $array[4];
	if (isset($array[5])) $all_comments = $array[5];

	$s = "";
	$s .= "<li>{$ablog->lang['msg']['all_artiles']}:{$all_artiles}</li>";
	$s .= "<li>{$ablog->lang['msg']['all_pages']}:{$all_pages}</li>";
	$s .= "<li>{$ablog->lang['msg']['all_categorys']}:{$all_categorys}</li>";
	$s .= "<li>{$ablog->lang['msg']['all_tags']}:{$all_tags}</li>";
	$s .= "<li>{$ablog->lang['msg']['all_comments']}:{$all_comments}</li>";
	if($ablog->option['ZC_VIEWNUMS_TURNOFF']==false){
		$s .= "<li>{$ablog->lang['msg']['all_views']}:{$all_views}</li>";
	}

	$ablog->modulesbyfilename['statistics']->Type = "ul";

	return $s;

}

################################################################################################################
/**
 * 显示404页面
 *
 * 可通过主题中的404.php模板自定义显示效果
 * @api Filter_Plugin_APP_ShowError
 * @param $idortext
 * @param $file
 * @param $line
 */
function ShowError404($idortext,$file,$line){
	global $ablog;

	if(!in_array( "Status: 404 Not Found" ,  headers_list() )) return;

	$ablog->template->SetTags('title', $ablog->title);

	$ablog->template->SetTemplate('404');

	$ablog->template->Display();

	$GLOBALS['Filter_Plugin_APP_ShowError']['ShowError404'] = PLUGIN_EXITSIGNAL_RETURN;

	exit;
}

/**
 * ViewIndex的预处理
 */
function PreViewIndex(){
	global $ablog;
	if(isset($ablog->templates['404']))Add_Filter_Plugin('Filter_Plugin_APP_ShowError','ShowError404');
	$t=array();
	$o=array();
	foreach($ablog->templatetags as $k => $v){
		if(is_string($v) || is_numeric($v) || is_bool($v) )
			$t['{$' . $k . '}']=$v;
	}
	foreach($ablog->option as $k => $v){
		if($k!='ZC_BLOG_CLSID' && $k!='ZC_SQLITE_NAME' && $k!='ZC_SQLITE3_NAME' && $k!='ZC_MYSQL_USERNAME' && $k!='ZC_MYSQL_PASSWORD' && $k!='ZC_MYSQL_NAME')
			$o['{#' . $k . '#}']=$v;
	}
	foreach($ablog->modulesbyfilename as $m){
		$m->Content = str_replace(array_keys($t),array_values($t),$m->Content);
		$m->Content = str_replace(array_keys($o),array_values($o),$m->Content);
	}
}

/**
 * 通过文件获取应用URL地址
 * @param string $file 文件名
 * @return string 返回URL地址
 */
function plugin_dir_url($file) {
	global $ablog;
	$s1=$ablog->path;
	$s2=str_replace('\\','/',dirname($file).'/');
	$s3='';
	$s=substr($s2,strspn($s1,$s2,0));
	if(strpos($s,'feifeis/plugin/')!==false){
		$s=substr($s,strspn($s,$s3='feifeis/plugin/',0));
	}else{
		$s=substr($s,strspn($s,$s3='feifeis/theme/',0));
	}
	$a=explode('/',$s);
	$s=$a[0];
	$s=$ablog->host . $s3 . $s . '/';
	return $s;
}

/**
 * 通过文件获取应用目录路径
 * @param $file
 * @return string
 */
function plugin_dir_path($file) {
	global $ablog;
	$s1=$ablog->path;
	$s2=str_replace('\\','/',dirname($file).'/');
	$s3='';
	$s=substr($s2,strspn($s1,$s2,0));
	if(strpos($s,'feifeis/plugin/')!==false){
		$s=substr($s,strspn($s,$s3='feifei/plugin/',0));
	}else{
		$s=substr($s,strspn($s,$s3='feifei/theme/',0));
	}
	$a=explode('/',$s);
	$s=$a[0];
	$s=$ablog->path . $s3 . $s . '/';
	return $s;
}