<?php 
/**
 * 文章列表
 */
if(!defined('__ROOT__')) {exit('error!');} 
if($pageurl == Url::logPage()){
	include View::getView('index');
	exit;
}
?>
<div class="wrap">
	<div class="clear"><!--空白间隔--></div>
<?php doAction('index_loglist_top'); ?>
	<div id="pleft">
    	<div id="ptop"><a href="<?php echo BLOG_URL; ?>">首页</a>&nbsp;&gt;&nbsp;
<?php if ($params[1]=='sort'){ ?>
		<?php echo '<a href="'.Url::sort($sortid).'">'.$sortName.'</a>';?>
<?php }elseif ($params[1]=='tag'){ ?>
			包含标签 <b><?php echo urldecode($params[2]);?></b> 的所有文章
<?php }elseif($params[1]=='author'){ ?>
			作者 <b><?php echo blog_author($author);?></b> 的所有文章
<?php }elseif($params[1]=='keyword'){ ?>
            搜索 <b><?php echo urldecode($params[2]);?></b> 的结果
<?php }else{?><?php }?>
        </div>
<?php 
if (!empty($logs)):
foreach($logs as $value): 
?>
    	<dl id="plist">
			<dt><?php topflg($value['top']); ?><a href="<?php echo $value['log_url']; ?>"><?php echo $value['log_title']; ?></a></dt>
			<dd>
分类：<?php blog_sort($value['logid']); ?>&nbsp;&nbsp;&nbsp;作者：<?php blog_author($value['author']); ?>&nbsp;&nbsp;&nbsp;时间：<?php echo gmdate('Y-n-j G:i', $value['date']); ?><a href="<?php echo $value['log_url']; ?>#comments" class="ct" title="评论/浏览"><?php echo $value['comnum']; ?>/<?php echo $value['views']; ?></a><a href="<?php echo $value['log_url']; ?>" class="vw">查看全文</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php editflg($value['logid'],$value['author']); ?><br />
<?php blog_tag($value['logid']); ?>
			</dd>
		</dl>
<?php 
endforeach;
else:
?>
		<div class="none"><h2>未找到</h2><p>抱歉，没有符合您查询条件的结果。<br /><a href="javascript:history.back(-1)">返回上一页</a></p></div>
<?php endif;?>
		<div id="pagenavi"><?php echo $page_url;?></div>
        <div class="clear"><!--空白间隔--></div>
	</div>
    <div id="pright">
		<div class="mlist ibox2 tophot">
    		<div class="title"><strong><?php echo $ititleB; ?></strong></div>
        	<div class="box">
				<?php widget_hotlog($title); ?>
        	</div>
    	</div>
        <div class="clear"><!--空白间隔--></div>
    	<div class="mlist ibox2 icmt">
    		<div class="title"><strong><?php echo $ititleC; ?></strong></div>
        	<div class="box">
				<?php widget_newcomm($title); ?>
        	</div>
    	</div>
        <div class="clear"><!--空白间隔--></div>
    	<div class="mlist ibox2">
    		<div class="title"><strong><?php echo $ititleD; ?></strong></div>
        	<div class="box">
				<?php widget_random_log($title); ?>
        	</div>
    	</div>
    </div>
</div>
<?php
 include View::getView('footer');
?>