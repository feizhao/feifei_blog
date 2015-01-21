<?php 
/**
 * 自建页面模板
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
<div class="am-u-md-8">
<article class="blog-main">
      <h3 class="am-article-title blog-title">
        <a href="#"><?php echo $log_title; ?></a>
      </h3>
      <h4 class="am-article-meta blog-meta">by <a href="">open</a> posted on 2014/06/17 under <a href="#">字体</a></h4>

      <div class="am-g blog-content">
          	<?php echo $log_content; ?>
      </div>
      <div class="am-g">
        <div class="am-u-sm-12">
          <p><?php blog_comments($comments); ?></p>

          <p><?php blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark); ?></p>
        </div>
      </div>
    </article>

    <hr class="am-article-divider blog-hr">

</div><!--end #contentleft-->
<?php
 include View::getView('side');
 include View::getView('footer');
?>