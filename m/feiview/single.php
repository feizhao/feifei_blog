<?php if(!defined('__ROOT__')) {exit('error!');}?>

<ul class="am-list">
<li class="am-g am-list-item-desced">
    <div class="am-list-main">
      <h3 class="am-list-item-hd">
          <?php echo $log_title; ?>
      </h3>
      <div class="am-list-item-text">
      	<?php echo gmdate('Y-n-j', $date); ?> <?php echo $user_cache[$author]['name'];?>
      </div>
       <div class="am-list-item-text">
      	  <?php echo nl2br($log_content); ?>
      </div>
    </div>
</li>
 <?php if(!empty($commentStacks)): ?>
<li class="am-g am-list-item-desced">
	<div class="t">评论：</div>
	<div class="c">
		<?php foreach($commentStacks as $cid):
			$comment = $comments[$cid];
			$comment['poster'] = $comment['url'] ? '<a href="'.$comment['url'].'" target="_blank">'.$comment['poster'].'</a>' : $comment['poster'];
		?>
		<div class="l">
		<b><?php echo $comment['poster']; ?></b>
		<div class="info"><?php echo $comment['date']; ?> <a href="./?action=reply&cid=<?php echo $comment['cid'];?>">回复</a></div>
		<div class="comcont"><?php echo $comment['content']; ?></div>
        <?php if(ROLE === ROLE_ADMIN): ?>
        <div class="delcom"><a href="./?action=delcom&id=<?php echo $comment['cid'];?>&gid=<?php echo $logid; ?>&token=<?php echo LoginAuth::genToken();?>">删除</a></div>
        <?php endif; ?>
		</div>
		<?php endforeach; ?>
		<div id="page"><?php echo $commentPageUrl;?></div>
	</div>
	</li>
    <?php endif;?>
    <?php if($allow_remark == 'y'): ?>
	<li class="am-g am-list-item-desced">
	<div class="t">发表评论：</div>
	<div class="c">
		<form method="post" action="./index.php?action=addcom&gid=<?php echo $logid; ?>">
		<?php if(ISLOGIN == true):?>
		当前已登录为：<b><?php echo $user_cache[UID]['name']; ?></b><br />
		<?php else: ?>
		昵称<br /><input type="text" name="comname" value="" /><br />
		邮件地址 (选填)<br /><input type="text" name="commail" value="" /><br />
		个人主页 (选填)<br /><input type="text" name="comurl" value="" /><br />
		<?php endif; ?>
		内容<br /><textarea name="comment" rows="10"></textarea><br />
		<?php echo $verifyCode; ?><br /><input type="submit" value="发表评论" />
		</form>
	</div>
	</li>
    <?php endif;?>

</ul>