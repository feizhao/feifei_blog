<?php 
/**
 * 微语部分
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
<div class="am-u-md-8">
 <?php 
    foreach($tws as $val):
    $author = $user_cache[$val['author']]['name'];
    $avatar = empty($user_cache[$val['author']]['avatar']) ? 
                BLOG_URL . 'admin/views/images/avatar.jpg' : 
                BLOG_URL . $user_cache[$val['author']]['avatar'];
    $tid = (int)$val['id'];
    $img = empty($val['img']) ? "" : '<a title="查看图片" href="'.BLOG_URL.str_replace('thum-', '', $val['img']).'" target="_blank"><img style="border: 1px solid #EFEFEF;" src="'.BLOG_URL.$val['img'].'"/></a>';
    ?> 
    <article class="blog-main">
      <h3 class="am-article-title blog-title">
         <img src="<?php echo $avatar; ?>" width="32px" height="32px" />
         <?php echo $author.'<br/>'.$img;?>
      </h3>
      <h4 class="am-article-meta blog-meta"><a href="#">发表时间</a><?php echo $val['date'];?></h4>

      <div class="am-g blog-content">
        <div class="am-u-lg-9">
           <p><?php echo $val['t'] ; ?></p>
        </div>
        <div class="am-u-lg-3">
        <p><a href="javascript:loadr('<?php echo DYNAMIC_BLOGURL; ?>?action=getr&tid=<?php echo $tid;?>','<?php echo $tid;?>');">回复(<span id="rn_<?php echo $tid;?>"><?php echo $val['replynum'];?></span>)</a></p>
        </div>
      </div>
       <ul id="r_<?php echo $tid;?>" class="r"></ul>
      <?php if ($istreply == 'y'):?>
        <div class="am-g">
        <div class="am-u-sm-12">
          <div class="huifu" id="rp_<?php echo $tid;?>">
          <textarea id="rtext_<?php echo $tid; ?>"></textarea>
          <div class="tbutton">
              <div class="tinfo" style="display:<?php if(ROLE == ROLE_ADMIN || ROLE == ROLE_WRITER){echo 'none';}?>">
              昵称：<input type="text" id="rname_<?php echo $tid; ?>" value="" />
              <span style="display:<?php if($reply_code == 'n'){echo 'none';}?>">验证码：<input type="text" id="rcode_<?php echo $tid; ?>" value="" /><?php echo $rcode; ?></span>        
              </div>
              <input class="button_p" type="button" onclick="reply('<?php echo DYNAMIC_BLOGURL; ?>index.php?action=reply',<?php echo $tid;?>);" value="回复" /> 
              <div class="msg"><span id="rmsg_<?php echo $tid; ?>" style="color:#FF0000"></span></div>
          </div>
          </div>

        </div>
      </div>
      <?php endif;?>
    </article>
       <hr class="am-article-divider blog-hr">
<?php endforeach;?>
 <ul class="am-pagination blog-pagination">
    <li id="pagenavi"><?php echo $pageurl;?><span></span></li>
</ul>


</div><!--end #contentleft-->
<?php
 include View::getView('side');
 include View::getView('footer');
?>