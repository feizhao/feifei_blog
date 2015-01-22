<?php 
/*
* 阅读日志页面
*/
if(!defined('__ROOT__')) {exit('error!');} 
?>
<DIV CLASS="CLEAR CONTENT">
<DIV CLASS="MAIN LEFT">


<DIV CLASS="NEW_CONTENT LEFT">
<H1><?php echo $log_title; ?></H1>
<DIV CLASS="DH">
<DIV class="dhlist">

<DIV class="dh_cos"></DIV>
</DIV>
</DIV>
<DIV CLASS="AB"></div>
<p>　<?php echo $log_content; ?>　</p> 
<p class="att"><?php blog_att($logid); ?></p>
<div id='loadings'></div>
<DIV CLASS="BB">

<!-- JiaThis Button BEGIN -->
<div id="jiathis_style_32x32">
	<a class="jiathis_button_qzone"></a>
	<a class="jiathis_button_tsina"></a>
	<a class="jiathis_button_tqq"></a>
	<a class="jiathis_button_renren"></a>
	<a class="jiathis_button_kaixin001"></a>
	<a href="http://www.jiathis.com/share/" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
	<a class="jiathis_counter_style"></a>
</div>
<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script>


原创文章如转载请注明：转载自<<a href="<?php echo $log_url; ?>"><?php echo $blogname; ?></a>>
<!-- JiaThis Button END -->

<DIV CLASS="CLEAR"></DIV>

</DIV>
<DIV CLASS="CB">
<?php blog_tag($logid); ?> <br>
<?php blog_sort($logid); ?>&nbsp;| 发布：<?php blog_author($author); ?>&nbsp;| 评论：<?php echo $comnum; ?>条&nbsp;| 发表时间：<?php echo gmdate('Y-n-j G:i l', $date); ?><?php editflg($logid,$author); ?><br>
引用:<?php blog_trackback($tb, $tb_url, $allow_tb); ?><br>
<?php neighbor_log($neighborLog); ?>
</DIV>
</DIV>
<DIV CLASS="NEW_INFO LEFT">
<DIV CLASS="Article_bottom_box">

</DIV>
<DIV id="art_wrapper">
<a href="http://onoboy.com"><img src="<?php echo TEMPLATE_URL; ?>img/658-80.gif"></a>
</DIV>
</DIV>
<DIV CLASS="NEW_INFO LEFT">
<H2><SPAN>相关</SPAN>文章</H2>
<UL CLASS="TOP_ARTICLES">
 
<?php doAction('log_related', $logData); ?>

</UL>
</DIV>





<DIV CLASS="NEW_INFO LEFT">
	<?php blog_comments($comments); ?>
	<?php blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark); ?>
</DIV>

<DIV CLASS="CLEAR"></DIV>

</DIV>

<!--sidebar-->
	
<?php
 include View::getView('side');
 include View::getView('footer');
?>