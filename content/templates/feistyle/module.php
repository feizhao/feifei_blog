<?php 
/**
 * 侧边栏组件、页面模块
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
<?php
//widget：blogger
function widget_blogger($title){
	global $CACHE;
	$user_cache = $CACHE->readCache('user');
	$name = $user_cache[1]['mail'] != '' ? "<a href=\"mailto:".$user_cache[1]['mail']."\">".$user_cache[1]['name']."</a>" : $user_cache[1]['name'];?>
   <section class="am-panel am-panel-default">
   <div class="am-panel-hd"><?php echo $title; ?></div>
   <div class="am-panel-bd">
   <p>
    <?php if (!empty($user_cache[1]['photo']['src'])): ?>
	<img src="<?php echo BLOG_URL.$user_cache[1]['photo']['src']; ?>" width="<?php echo $user_cache[1]['photo']['width']; ?>" height="<?php echo $user_cache[1]['photo']['height']; ?>" alt="blogger" />
	<?php endif;?>
   </p>
   <p><b><?php echo $name; ?></b><?php echo $user_cache[1]['des']; ?></p>
   </div>
   </section>
<?php }?>
<?php
//widget：日历
function widget_calendar($title){ ?>
   <section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<div  class="am-panel-bd" id="calendar">
	</div>
	<script>sendinfo('<?php echo Calendar::url(); ?>','calendar');</script>
	</section>
<?php }?>
<?php
//widget：标签
function widget_tag($title){
	global $CACHE;
	$tag_cache = $CACHE->readCache('tags');?>
	<section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<div class="am-panel-hd" id="blogtags">
	<?php foreach($tag_cache as $value): ?>
		<span style="font-size:<?php echo $value['fontsize']; ?>pt; line-height:30px;">
		<a href="<?php echo Url::tag($value['tagurl']); ?>" title="<?php echo $value['usenum']; ?> 篇文章"><?php echo $value['tagname']; ?></a></span>
	<?php endforeach; ?>
	</div>
	</section>
<?php }?>
<?php
//widget：分类
function widget_sort($title){
	global $CACHE;
	$sort_cache = $CACHE->readCache('sort'); ?>
	<section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<ul class="am-list admin-sidebar-list" id="blogsort">
	<?php
	foreach($sort_cache as $value):
		if ($value['pid'] != 0) continue;
	?>
 
	<?php if (empty($value['children'])): ?>
	<li>
	<a href="<?php echo Url::sort($value['sid']); ?>"><?php echo $value['sortname']; ?>(<?php echo $value['lognum'] ?>)</a>
    </li>
	<?php endif; ?>
	<?php if (!empty($value['children'])): ?>
	<li class="admin-parent">
	<!-- href="<?php echo Url::sort($value['sid']); ?>" -->
     <a  data-am-collapse="{target: '#collapse-nav'}" class="am-cf am-collapsed"><?php echo $value['sortname']; ?>(<?php echo $value['lognum'] ?>) <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
        <ul id="collapse-nav" class="am-list admin-sidebar-sub am-collapse" style="height: 0px;">
		<?php
		$children = $value['children'];
		foreach ($children as $key):
			$value = $sort_cache[$key];
		?>
		<li>
			<a href="<?php echo Url::sort($value['sid']); ?>"><?php echo $value['sortname']; ?>(<?php echo $value['lognum'] ?>)</a>
		</li>
		<?php endforeach; ?>
		</ul>
	</li>
	<?php endif; ?>
	<?php endforeach; ?>
	</ul>
	</section>
<?php }?>
<?php
//widget：最新微语
function widget_twitter($title){
	global $CACHE; 
	$newtws_cache = $CACHE->readCache('newtw');
	$istwitter = Option::get('istwitter');
	?>
	<section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<div  class="am-panel-bd" id="twitter">
	<?php foreach($newtws_cache as $value): ?>
	<?php $img = empty($value['img']) ? "" : '<a title="查看图片" class="t_img" href="'.BLOG_URL.str_replace('thum-', '', $value['img']).'" target="_blank">&nbsp;</a>';?>
	<p><?php echo $value['t']; ?><?php echo $img;?> * <?php echo smartDate($value['date']); ?></p>
	<hr/>
	<?php endforeach; ?>
    <?php if ($istwitter == 'y') :?>
	<p><a class="am-btn am-btn-success am-btn-sm" href="<?php echo BLOG_URL . 't/'; ?>">更多&raquo;</a></p>
	<?php endif;?>
	</div>
	</section>
<?php }?>
<?php
//widget：最新评论
function widget_newcomm($title){
	global $CACHE; 
	$com_cache = $CACHE->readCache('comment');
	?>
	<section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<ul id="newcomment" class='am-list blog-list'>
	<?php
	foreach($com_cache as $value):
	$url = Url::comment($value['gid'], $value['page'], $value['cid']);
	?>
	<li id="comment"><?php echo $value['name']; ?>
	<br /><a href="<?php echo $url; ?>"><?php echo $value['content']; ?></a></li>
	<?php endforeach; ?>
	</ul>
	</section>
<?php }?>
<?php
//widget：最新文章
function widget_newlog($title){
	global $CACHE; 
	$newLogs_cache = $CACHE->readCache('newlog');
	?>
	<section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<ul id="newlog" class='am-list blog-list'>
	<?php foreach($newLogs_cache as $value): ?>
	<li><a href="<?php echo Url::log($value['gid']); ?>"><?php echo $value['title']; ?></a></li>
	<?php endforeach; ?>
	</ul>
	</section>
<?php }?>
<?php
//widget：热门文章
function widget_hotlog($title){
	$index_hotlognum = Option::get('index_hotlognum');
	$Log_Model = new Log_Model();
	$randLogs = $Log_Model->getHotLog($index_hotlognum);?>
	<section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<ul id="hotlog" class='am-list blog-list'>
	<?php foreach($randLogs as $value): ?>
	<li><a href="<?php echo Url::log($value['gid']); ?>"><?php echo $value['title']; ?></a></li>
	<?php endforeach; ?>
	</ul>
	</section>
<?php }?>
<?php
//widget：随机文章
function widget_random_log($title){
	$index_randlognum = Option::get('index_randlognum');
	$Log_Model = new Log_Model();
	$randLogs = $Log_Model->getRandLog($index_randlognum);?>
	<section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<ul id="randlog" class='am-list blog-list'>
	<?php foreach($randLogs as $value): ?>
	<li><a href="<?php echo Url::log($value['gid']); ?>"><?php echo $value['title']; ?></a></li>
	<?php endforeach; ?>
	</ul>
	</section>
<?php }?>
<?php
//widget：搜索
function widget_search($title){ ?>
	<section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<ul id="logsearch" class='am-list blog-list'>
	<form name="keyform" method="get" action="<?php echo BLOG_URL; ?>index.php"  role="search">
      <div class="am-form-group">
      <input name="keyword"   placeholder="搜索文章"  class="am-form-field am-input-sm" type="text" />
      </div>
      <button type="submit" class="am-btn am-btn-default am-btn-sm">搜索</button>
    </form>
	</ul>
	</section>
<?php } ?>
<?php
//widget：归档
function widget_archive($title){
	global $CACHE; 
	$record_cache = $CACHE->readCache('record');
	?>
	<section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<ul id="record" class='am-list blog-list'>
	<?php foreach($record_cache as $value): ?>
	<li><a href="<?php echo Url::record($value['date']); ?>"><?php echo $value['record']; ?>(<?php echo $value['lognum']; ?>)</a></li>
	<?php endforeach; ?>
	</ul>
	</section>
<?php } ?>
<?php
//widget：自定义组件
function widget_custom_text($title, $content){ ?>
	<section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<ul class='am-list blog-list'>
	<li>
	<?php echo $content; ?>
	</li>
	</ul>
	</section>
<?php } ?>
<?php
//widget：链接
function widget_link($title){
	global $CACHE; 
	$link_cache = $CACHE->readCache('link');
    //if (!blog_tool_ishome()) return;#只在首页显示友链去掉双斜杠注释即可
	?>
	<section class="am-panel am-panel-default">
	<div class="am-panel-hd"><?php echo $title; ?></div>
	<ul id="link" class='am-list blog-list'>
	<?php foreach($link_cache as $value): ?>
	<li><a href="<?php echo $value['url']; ?>" title="<?php echo $value['des']; ?>" target="_blank"><?php echo $value['link']; ?></a></li>
	<?php endforeach; ?>
	</ul>
	</section>
<?php }?> 
<?php
//blog：导航
function blog_navi(){
	global $CACHE; 
	$navi_cache = $CACHE->readCache('navi');
	?>
	<ul class="am-nav am-nav-pills am-topbar-nav">
	<?php
	foreach($navi_cache as $value):

        if ($value['pid'] != 0) {
            continue;
        }

		if($value['url'] == ROLE_ADMIN && (ROLE == ROLE_ADMIN || ROLE == ROLE_WRITER)):
			?>
			<li><a href="<?php echo BLOG_URL; ?>admin/">管理站点</a></li>
			<li><a href="<?php echo BLOG_URL; ?>admin/?action=logout">退出</a></li>
			<?php 
			continue;
		endif;
		$newtab = $value['newtab'] == 'y' ? 'target="_blank"' : '';
        $value['url'] = $value['isdefault'] == 'y' ? BLOG_URL . $value['url'] : trim($value['url'], '/');
        $current_tab = BLOG_URL . trim(Dispatcher::setPath(), '/') == $value['url'] ? 'am-active' : '';
		?>
		<?php if (empty($value['children']) && empty($value['childnavi'])) :?>
		<li class="item <?php echo $current_tab;?>">
			<a href="<?php echo $value['url']; ?>" <?php echo $newtab;?>><?php echo $value['naviname']; ?></a>
		</li>
	    <?php endif; ?>
		<?php if (!empty($value['children']) or !empty($value['childnavi'])) :?>
		<li class="am-dropdown" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <?php echo $value['naviname']; ?><span class="am-icon-caret-down"></span>
        </a>
        <?php if (!empty($value['children'])) : ?>
       	  <ul class="am-dropdown-content">
          <?php foreach ($value['children'] as $row){
                echo '<li><a href="'.Url::sort($row['sid']).'">'.$row['sortname'].'</a></li>';
          }?>
          </ul>
        <?php endif; ?>
        <?php if (!empty($value['childnavi'])) :?>
        <ul class="am-dropdown-content">
        <?php foreach ($value['childnavi'] as $row){
	        $newtab = $row['newtab'] == 'y' ? 'target="_blank"' : '';
	        echo '<li><a href="' . $row['url'] . "\" $newtab >" . $row['naviname'].'</a></li>';
	    }?>
        </ul>
        <?php endif; ?>
        </li>
        <?php endif;?>
       
		 
	<?php endforeach; ?>
	</ul>
<?php }?>
<?php
//blog：置顶
function topflg($top, $sortop='n', $sortid=null){
    if(blog_tool_ishome()) {
       echo $top == 'y' ? "<img src=\"".TEMPLATE_URL."/images/top.png\" title=\"首页置顶文章\" /> " : '';
    } elseif($sortid){
       echo $sortop == 'y' ? "<img src=\"".TEMPLATE_URL."/images/sortop.png\" title=\"分类置顶文章\" /> " : '';
    }
}
?>
<?php
//blog：编辑
function editflg($logid,$author){
	$editflg = ROLE == ROLE_ADMIN || $author == UID ? '<a href="'.BLOG_URL.'admin/write_log.php?action=edit&gid='.$logid.'" target="_blank">编辑</a>' : '';
	echo $editflg;
}
?>
<?php
//blog：分类
function blog_sort($blogid){
	global $CACHE; 
	$log_cache_sort = $CACHE->readCache('logsort');
	?>
	<?php if(!empty($log_cache_sort[$blogid])): ?>
    <a href="<?php echo Url::sort($log_cache_sort[$blogid]['id']); ?>"><?php echo $log_cache_sort[$blogid]['name']; ?></a>
	<?php endif;?>
<?php }?>
<?php
//blog：文章标签
function blog_tag($blogid){
	global $CACHE;
	$log_cache_tags = $CACHE->readCache('logtags');
	if (!empty($log_cache_tags[$blogid])){
		$tag = '标签:';
		foreach ($log_cache_tags[$blogid] as $value){
			$tag .= "	<a href=\"".Url::tag($value['tagurl'])."\">".$value['tagname'].'</a>';
		}
		echo $tag;
	}
}
?>
<?php
//blog：文章作者
function blog_author($uid){
	global $CACHE;
	$user_cache = $CACHE->readCache('user');
	$author = $user_cache[$uid]['name'];
	$mail = $user_cache[$uid]['mail'];
	$des = $user_cache[$uid]['des'];
	$title = !empty($mail) || !empty($des) ? "title=\"$des $mail\"" : '';
	echo '<a href="'.Url::author($uid)."\" $title>$author</a>";
}
?>
<?php
//blog：相邻文章
function neighbor_log($neighborLog){
	extract($neighborLog);?>
	<?php if($prevLog):?>
	&laquo; <a href="<?php echo Url::log($prevLog['gid']) ?>"><?php echo $prevLog['title'];?></a>
	<?php endif;?>
	<?php if($nextLog && $prevLog):?>
		|
	<?php endif;?>
	<?php if($nextLog):?>
		 <a href="<?php echo Url::log($nextLog['gid']) ?>"><?php echo $nextLog['title'];?></a>&raquo;
	<?php endif;?>
<?php }?>

<?php
//blog：评论列表
function blog_comments($comments){
    extract($comments);
    if($commentStacks): ?>
	<a name="comments"></a>
	<p class="comment-header"><b>评论：</b></p>
	<?php endif; ?>
	<?php
	$isGravatar = Option::get('isgravatar');
	foreach($commentStacks as $cid):
    $comment = $comments[$cid];
	$comment['poster'] = $comment['url'] ? '<a href="'.$comment['url'].'" target="_blank">'.$comment['poster'].'</a>' : $comment['poster'];
	?>
	<ul class="am-comments-list" id="comment-<?php echo $comment['cid']; ?>">
		<a name="<?php echo $comment['cid']; ?>"></a>
	 <li class="am-comment">
	   <?php if($isGravatar == 'y'): ?>
		<a href="#link-to-user-home">
		  <img width="48" height="48" class="am-comment-avatar" alt="" src="<?php echo getGravatar($comment['mail']); ?>">
		</a>
		<?php endif; ?>
	    
		<div class="am-comment-main">
		  <header class="am-comment-hd">
		    <div class="am-comment-meta">
		      <a class="am-comment-author" href="#link-to-user"><?php echo $comment['poster']; ?></a> 评论于 <time title="<?php echo $comment['date']; ?>" datetime="<?php echo $comment['date']; ?>"><?php echo $comment['date']; ?></time>
		    </div>
		  </header>
		  <div class="am-comment-bd">
		    <p><?php echo $comment['content']; ?></p>
		    <p><a href="#comment-<?php echo $comment['cid']; ?>" onclick="commentReply(<?php echo $comment['cid']; ?>,this)">回复</a></p>
		    <?php blog_comments_children($comments, $comment['children']); ?>
		  </div>
		</div>
	 </li>
	</ul>
	<?php endforeach; ?>
    <div id="pagenavi">
	    <?php echo $commentPageUrl;?>
    </div>
<?php }?>
<?php
//blog：子评论列表
function blog_comments_children($comments, $children){
	$isGravatar = Option::get('isgravatar');
	foreach($children as $child):
	$comment = $comments[$child];
	$comment['poster'] = $comment['url'] ? '<a href="'.$comment['url'].'" target="_blank">'.$comment['poster'].'</a>' : $comment['poster'];
	?>
	<ul class="am-comments-list" id="comment-<?php echo $comment['cid']; ?>">
		<a name="<?php echo $comment['cid']; ?>"></a>
		<li class="am-comment">
	   <?php if($isGravatar == 'y'): ?>
		<a href="#link-to-user-home">
		  <img width="48" height="48" class="am-comment-avatar" alt="" src="<?php echo getGravatar($comment['mail']); ?>">
		</a>
		<?php endif; ?>
	    <?php if($isGravatar == 'y'): ?>
			<div class="avatar"></div>
		<?php endif; ?>
		<div class="am-comment-main">
		  <header class="am-comment-hd">
		    <div class="am-comment-meta">
		      <a class="am-comment-author" href="#link-to-user"><?php echo $comment['poster']; ?></a> 回复于 <time title="<?php echo $comment['date']; ?>" datetime="<?php echo $comment['date']; ?>"><?php echo $comment['date']; ?></time>
		    </div>
		  </header>
		  <div class="am-comment-bd">
		    <p><?php echo $comment['content']; ?></p>
			<?php if($comment['level'] < 4): ?>
				<p class="comment-reply"><a href="#comment-<?php echo $comment['cid']; ?>" onclick="commentReply(<?php echo $comment['cid']; ?>,this)">回复</a></p>
			<?php endif; ?>
		    <?php blog_comments_children($comments, $comment['children']); ?>
		  </div>
		</div>
	 </li>
	</ul>
	<?php endforeach; ?>
<?php }?>
<?php
//blog：发表评论表单
function blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark){
	if($allow_remark == 'y'): ?>
	<div id="comment-place">
	<div class="comment-post" id="comment-post">
		<div class="cancel-reply" id="cancel-reply" style="display:none"><a href="javascript:void(0);" onclick="cancelReply()">取消回复</a></div>
		<p class="comment-header"><b>发表评论：</b><a name="respond"></a></p>
		<form class="am-form" method="post" name="commentform" action="<?php echo BLOG_URL; ?>index.php?action=addcom" id="commentform">
			<input type="hidden" name="gid" value="<?php echo $logid; ?>" />
			<?php if(ROLE == ROLE_VISITOR): ?>
			<p>
				<input type="text" name="comname" maxlength="49" value="<?php echo $ckname; ?>" size="22" tabindex="1">
				<label for="author"><small>昵称</small></label>
			</p>
			<p>
				<input type="text" name="commail"  maxlength="128"  value="<?php echo $ckmail; ?>" size="22" tabindex="2">
				<label for="email"><small>邮件地址 (选填)</small></label>
			</p>
			<p>
				<input type="text" name="comurl" maxlength="128"  value="<?php echo $ckurl; ?>" size="22" tabindex="3">
				<label for="url"><small>个人主页 (选填)</small></label>
			</p>
			<?php endif; ?>
			<p><textarea name="comment" id="comment" rows="10" tabindex="4"></textarea></p>
			<p><?php echo $verifyCode; ?> <input type="submit" id="comment_submit" value="发表评论" tabindex="6" /></p>
			<input type="hidden" name="pid" id="comment-pid" value="0" size="22" tabindex="1"/>
		</form>
	</div>
	</div>
	<?php endif; ?>
<?php }?>
<?php
//blog-tool:判断是否是首页
function blog_tool_ishome(){
    if (BLOG_URL . trim(Dispatcher::setPath(), '/') == BLOG_URL){
        return true;
    } else {
        return FALSE;
    }
}
?>
