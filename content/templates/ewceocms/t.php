<?php 
/**
 * 微语部分
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
<div class="wrap">
	<div class="clear"><!--空白间隔--></div>
	<div id="pleft">
    	<div id="ptop">
<a href="<?php echo BLOG_URL; ?>">首页</a>&nbsp;&gt;&nbsp;<a href="<?php echo BLOG_URL; ?>t/">微语</a>
        </div>
        <div id="content" style="height:852px">
<br /><br /><br />抱歉，该模板没有开启微语功能<br /><br /><br />如有需要，请联系模板作者&nbsp;&nbsp;&nbsp;www.ewceo.com
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