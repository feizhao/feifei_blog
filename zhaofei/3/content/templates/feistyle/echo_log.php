<?php 
/**
 * 阅读文章页面
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
<div class="am-u-md-8">
   <article class="blog-main">
    <h3 class="am-article-title blog-title">
        <?php topflg($top); ?><?php echo $log_title; ?>
    </h3>
	<h4 class="am-article-meta blog-meta">
	<?php echo gmdate('Y-n-j', $date); ?>  
	<?php blog_author($author); ?> 
	<?php blog_sort($logid); ?> 
	<?php editflg($logid,$author); ?>
	</h4>
	   <div class="am-g blog-content">
        <div class="am-u-lg-8">
         <p><?php echo $log_content; ?></p>
        </div>
        <div class="am-u-lg-4">
          <p><?php blog_tag($logid); ?></p>
        </div>
      </div>
	  
	  <div class="am-g">
        <div class="am-u-sm-12">
          <p><?php doAction('log_related', $logData); ?></p>
          <p><?php neighbor_log($neighborLog); ?></p>
          <p>
          <?php blog_comments($comments); ?>
		  <?php blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark); ?>
		  </p>
        </div>
      </div>
	</article>
</div><!--end #contentleft-->

  
<?php
 include View::getView('side');
 include View::getView('footer');
?>