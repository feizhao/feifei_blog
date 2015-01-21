<?php 
/**
 * 阅读文章页面
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
<div class="wrap">
	<div class="clear"><!--空白间隔--></div>
	<div id="pleft">
    	<div id="ptop">
<a href="<?php echo BLOG_URL; ?>">首页</a>&nbsp;&gt;&nbsp;<?php blog_sort($logid); ?>&nbsp;&gt;&nbsp;<a href="<?php echo Url::log($logid); ?>"><?php echo $log_title; ?></a>
        </div>
        <div id="content">
			<h1><?php topflg($top); ?><?php echo $log_title; ?></h1>
			<p class="date">本文由&nbsp;<?php blog_author($author); ?>&nbsp;于&nbsp;<?php echo gmdate('Y-n-j G:i', $date); ?> 发布在&nbsp;<?php blog_sort($logid); ?>&nbsp;&nbsp;&nbsp;<?php editflg($logid,$author); ?></p>
			<?php echo $log_content; ?>
			<p class="tag"><?php blog_tag($logid); ?></p>
			<div class="nextlog"><?php neighbor_log($neighborLog); ?></div>
    	</div>
        <div id="logx">
			<?php doAction('log_related', $logData); ?>
			<?php blog_comments($comments); ?>
			<?php blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark); ?>
    	</div>
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
        <?php if ($lograd == 1) :?>
        <div class="clear"><!--空白间隔--></div>
        <div id="lograd">
        	<a href="<?php echo $logradurl; ?>" target="_blank"><img src="<?php echo TEMPLATE_URL; ?>images/logad.gif" /></a>
        </div>
        <?php else: ?><?php endif;?>
    </div>
</div>
<?php
 include View::getView('footer');
?>