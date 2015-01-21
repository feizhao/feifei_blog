<?php 
/**
 * 自建页面模板
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
<div class="wrap">
	<div class="clear"><!--空白间隔--></div>
	<div id="pleft">
    	<div id="ptop">
<a href="<?php echo BLOG_URL; ?>">首页</a>&nbsp;&gt;&nbsp;<a href="<?php echo Url::log($logid); ?>"><?php echo $log_title; ?></a>
        </div>
        <div id="content">
	<h1><?php echo $log_title; ?></h1>
	<?php echo $log_content; ?>
    	</div>
        <div id="logx">
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
        <div class="clear"><!--空白间隔--></div>
    	<div class="mlist ibox2">
    		<div class="title"><strong><?php echo $ititleE; ?></strong></div>
        	<div class="box itag">
				<?php widget_tag($title); ?>
        	</div>
    	</div>
    </div>
</div>
<?php
 include View::getView('footer');
?>