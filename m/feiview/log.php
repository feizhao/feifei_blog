
<?php if(!defined('__ROOT__')) {exit('error!');}?>
 
<!-- List -->
<div data-am-widget="list_news" class="am-list-news am-list-news-default">
  <!--列表标题-->
  <div class="am-list-news-bd">
    <ul class="am-list">
    <?php foreach($logs as $value): ?>
      <li class="am-g am-list-item-desced">
        <div class="am-list-main">
          <h3 class="am-list-item-hd">
             <a href="<?php echo BLOG_URL; ?>m/?post=<?php echo $value['logid'];?>"><?php echo $value['log_title']; ?></a>
          </h3>
          <div class="am-list-item-text">
          <?php echo gmdate('Y-n-j', $value['date']); ?> 评论:<?php echo $value['comnum']; ?> 阅读:<?php echo $value['views']; ?> 
          <?php if(ROLE == ROLE_ADMIN || $value['author'] == UID): ?>
		     <a href="./?action=write&id=<?php echo $value['logid'];?>">编辑</a>
		  <?php endif;?>
          </div>
        </div>
      </li>
     <?php endforeach; ?>
    </ul>
  </div>
   <div class="am-list-news-ft" id='page'>
     <?php echo $page_url;?>
  </div>
</div>

