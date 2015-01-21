<?php 
/**
 * 易玩印象CMS模板首页
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
<div class="wrap">
	<div class="clear"><!--空白间隔--></div>
	<div class="mlist ibox1">
    	<div class="title"><strong><?php echo $ititleA; ?></strong></div>
        <div class="box">
        	<ul>
            	<?php indexnewlist(); ?>
            </ul>
        </div>
    </div>
    <div class="mlist mfp">
    	<div class="ipic">
        	<ul>
    	<?php home_slide(); ?>
        	</ul>
        </div>
        <div class="thnav">
			<ul>
                <li>1</li>
                <li>2</li>
                <li>3</li>
                <li>4</li>
                <li>5</li>
			</ul>
		</div>
    </div>
	<div class="mlist ibox2 tophot">
    	<div class="title"><strong><?php echo $ititleB; ?></strong></div>
        <div class="box">
			<?php widget_hotlog($title); ?>
        </div>
    </div>
    <div class="clear"><!--空白间隔--></div>
    <div class="mlist mpic">
    	<div class="title"><strong><?php echo $ipicname; ?></strong><a href="<?php echo Url::sort($ipicID); ?>" class="more"><img src="<?php echo TEMPLATE_URL; ?>images/more.gif" alt="更多" /></a></div>
        <div class="box">
        	<ul>
            	<?php thumbs_by_sort($ipicID, 8); ?>
            </ul>
        </div>
    </div>
    <div class="mlist ibox2 icmt">
    	<div class="title"><strong><?php echo $ititleC; ?></strong></div>
        <div class="box">
			<?php widget_newcomm($title); ?>
        </div>
    </div>
    <div class="clear"><!--空白间隔--></div>
    <div class="mlist ibox1">
    	<div class="title"><strong><?php echo $ilistnameA; ?></strong><a href="<?php echo Url::sort($ilistaID); ?>" class="more"><img src="<?php echo TEMPLATE_URL; ?>images/more.gif" alt="更多" /></a></div>
        <div class="box">
        	<ul>
				<?php get_list($ilistaID);?>
            </ul>
        </div>
    </div>
    <div class="mlist ibox1 ibox3">
    	<div class="title"><strong><?php echo $ilistnameB; ?></strong><a href="<?php echo Url::sort($ilistbID); ?>" class="more"><img src="<?php echo TEMPLATE_URL; ?>images/more.gif" alt="更多" /></a></div>
        <div class="box">
        	<ul>
				<?php get_list($ilistbID);?>
            </ul>
        </div>
    </div>
    <div class="mlist ibox2">
    	<div class="title"><strong><?php echo $ititleD; ?></strong></div>
        <div class="box">
			<?php widget_random_log($title); ?>
        </div>
    </div>
    <?php if ($indexad == 1) :?>
    <div class="clear"><!--空白间隔--></div>
    <div class="ivad">
    	<a href="<?php echo $indexadurl; ?>" target="_blank"><img src="<?php echo TEMPLATE_URL; ?>images/indexad.gif" /></a>
    </div>
	<?php else: ?><?php endif;?>
    <div class="clear"><!--空白间隔--></div>
    <div class="mlist ibox1">
    	<div class="title"><strong><?php echo $ilistnameC; ?></strong><a href="<?php echo Url::sort($ilistcID); ?>" class="more"><img src="<?php echo TEMPLATE_URL; ?>images/more.gif" alt="更多" /></a></div>
        <div class="box">
        	<ul>
				<?php get_list($ilistcID);?>
            </ul>
        </div>
    </div>
    <div class="mlist ibox1 ibox3">
    	<div class="title"><strong><?php echo $ilistnameD; ?></strong><a href="<?php echo Url::sort($ilistdID); ?>" class="more"><img src="<?php echo TEMPLATE_URL; ?>images/more.gif" alt="更多" /></a></div>
        <div class="box">
        	<ul>
				<?php get_list($ilistdID);?>
            </ul>
        </div>
    </div>
    <div class="mlist ibox2">
    	<div class="title"><strong><?php echo $ititleE; ?></strong></div>
        <div class="box itag">
			<?php widget_tag($title); ?>
        </div>
    </div>
    <div class="clear"><!--空白间隔--></div>
    <div class="mlist linkbox">
    	<div class="title"><strong><?php echo $ititleF; ?></strong></div>
        <div class="box">
			<?php widget_link($title); ?>
        </div>
    </div>
</div>
<?php
 include View::getView('footer');
?>