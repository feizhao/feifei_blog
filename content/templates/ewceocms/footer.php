<?php 
/**
 * 页面底部信息
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
<div class="clear"><!--空白间隔--></div>
<div id="footerbar">
<a href="<?php echo BLOG_URL; ?>admin/">网站管理</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo BLOG_URL; ?>"><?php echo $blogname; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo BLOG_URL; ?>m/">手机版</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo BLOG_URL; ?>rss.php">RSS</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $guestbookurl; ?>">留言建议</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $abouturl; ?>">关于本站</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $contacturl; ?>">联系方式</a><p>
<!--提示：免费版修改或删除模板作者信息可能导致出错提示，如确需删除请联系模板作者获取授权版。-->
All Rights Reserved. Powered by <a href="http://www.emlog.net" title="emlog <?php echo Option::EMLOG_VERSION;?>">emlog</a> Themes by <a href="http://www.ewceo.com" id="ewcms" target="_blank">易玩稀有</a>
<br /><a href="http://www.miibeian.gov.cn" target="_blank"><?php echo $icp; ?></a> <?php echo $footer_info; ?><?php doAction('index_footer'); ?></p>
</div>
<div id="totop"><a href="javascript:scroll(0,0)"></a></div>
</body>
</html>