<?php 
/**
 * 站点首页模板
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
<div class="am-u-md-8">
<?php doAction('index_loglist_top'); ?>
<?php 
if (!empty($logs)):
foreach($logs as $value): 
?>
<article class="blog-main">
  <h3 class="am-article-title blog-title">
     <?php topflg($value['top'], $value['sortop'], isset($sortid)?$sortid:''); ?>
	<a href="<?php echo $value['log_url']; ?>"><?php echo $value['log_title']; ?></a>
  </h3>
  <p class="date">
	
	</p>
  <h4 class="am-article-meta blog-meta">
  <a href="#"><?php blog_author($value['author']); ?> </a> 
  <?php echo gmdate('Y-n-j', $value['date']); ?>  
  <?php blog_sort($value['logid']); ?> 
  <?php editflg($value['logid'],$value['author']); ?>
  <?php blog_tag($value['logid']); ?>
  </h4>

  <div class="am-g blog-content">
    <div class="am-u-lg-7">
        <p><?php echo $value['log_description']; ?></p>
    </div>
    <div class="am-u-lg-5">
    <p class="count">
	<a href="<?php echo $value['log_url']; ?>#comments">评论(<?php echo $value['comnum']; ?>)</a>
	<a href="<?php echo $value['log_url']; ?>">浏览(<?php echo $value['views']; ?>)</a>
	</p>
    </div>
  </div>
</article>
<hr class="am-article-divider blog-hr">
<?php 
endforeach;
else:
?>
	<h2>未找到</h2>
	<p>抱歉，没有符合您查询条件的结果。</p>
<?php endif;?>

 
 <ul class="am-pagination blog-pagination">
      <?php echo $page_url;?>
 </ul>
</div><!-- end #contentleft-->
<?php
 include View::getView('side');
 include View::getView('footer');
?>