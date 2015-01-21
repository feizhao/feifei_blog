<?php if(!defined('__ROOT__')) {exit('error!');} ?>
<DIV CLASS="CLEAR CONTENT">
<DIV CLASS="RIGHT BSPACING">
<DIV CLASS="FLASH_IMG">
<?php
//获取首页幻灯片
function home_slide(){
$db = MySql::getInstance();
$sql = "SELECT blogid as g,filepath,(SELECT title FROM ".DB_PREFIX."blog where `gid`=g) as t,(SELECT excerpt FROM ".DB_PREFIX."blog where `gid`=g) as c FROM ".DB_PREFIX."attachment WHERE `filepath` LIKE '%jpg' OR `filepath` LIKE '%gif' OR `filepath` LIKE '%png' GROUP BY `blogid` ORDER BY `addtime` DESC LIMIT 0, 4";
$imgs = $db->query($sql);
while($row = $db->fetch_array($imgs)){
$slide .= '<a href="'.Url::log($row['g']).'" target="_blank" title="'.$row['t'].'"><img width="445" height="230" src="'.BLOG_URL.substr($row['filepath'],3,strlen($row['filepath'])).'" class="attachment-asylmf_slide wp-post-image" alt="'.$row['t'].'" title="'.$row['t'].'" /></a>'; }
echo $slide;}?>
<div style='visibility:hidden' id=KinSlideshow> 
<?php home_slide(); ?>
</div></DIV></DIV>

<!--新闻轮播开始-->
<DIV CLASS="ART_INFO LEFT">
<DIV CLASS="PIC_MENU">
<UL id="TRI" class="TRI RIGHT"><SPAN><FONT color="#990000">24小时</FONT>排行</SPAN>
<LI CLASS="SELECTED">最新<LI>热门<LI>冷门<LI>随机</LI>
</UL>
</DIV>
<DIV CLASS="PICBOX" id="PICBOX">
<UL>
<?php
//最新文章
function new_blog($num=14){
	$db = MySql::getInstance();
	$sql = 	"SELECT gid,title FROM ".DB_PREFIX."blog WHERE type='blog' ORDER BY `gid` DESC LIMIT 0,".$num."";
	$list = $db->query($sql);
	while($row = $db->fetch_array($list)){
			$l .='<li><a href="'.Url::log($row['gid']).'">'.$row['title'].'</a></li>';		
		}
	echo $l;
	}
	new_blog();
?>
</UL>
 
 
 <UL CLASS="NONE">
<?php
//最热文章
function hot_blog($num=14){
	$db = MySql::getInstance();
	$sql = 	"SELECT gid,title FROM ".DB_PREFIX."blog WHERE type='blog' ORDER BY `views` DESC LIMIT 0,".$num."";
	$list = $db->query($sql);
	while($row = $db->fetch_array($list)){
			$l .='<li><a href="'.Url::log($row['gid']).'">'.$row['title'].'</a></li>';		
		}
	echo $l;
	}
	hot_blog();
?>
 </UL>

  <UL CLASS="NONE">
   <?php
//冷门
function leng_blog($num=14){
	$db = MySql::getInstance();
	$sql = 	"SELECT gid,title FROM ".DB_PREFIX."blog WHERE type='blog' ORDER BY `views` ASC LIMIT 0,".$num."";
	$list = $db->query($sql);
	while($row = $db->fetch_array($list)){
			$l .='<li><a href="'.Url::log($row['gid']).'">'.$row['title'].'</a></li>';		
		}
	echo $l;
	}
	leng_blog();
?>
  </UL>



  <UL CLASS="NONE">
<?php
//随机文章
function sui_blog($num=14){
	$db = MySql::getInstance();
	$sql = 	"SELECT gid,title FROM ".DB_PREFIX."blog WHERE type='blog' ORDER BY RAND() LIMIT 0,".$num."";
	$list = $db->query($sql);
	while($row = $db->fetch_array($list)){
			$l .='<li><a href="'.Url::log($row['gid']).'">'.$row['title'].'</a></li>';		
		}
	echo $l;
	}
	sui_blog();
?>
  </UL>

</DIV>
</DIV>
<DIV CLASS="TOP_INFO"></DIV>
</DIV>
<DIV CLASS="CLEAR CONTENT">

<DIV CLASS="MAIN LEFT">
<DIV CLASS="NEW_INFO LEFT">
<H2><FONT color="#990000">置顶</FONT>推荐</H2>
<UL id="TOP_ART" CLASS="TOP_ARTICLES">
 <?php
//推荐文章
	$db = MySql::getInstance();
	$sql = 	"SELECT gid,title,content FROM ".DB_PREFIX."blog WHERE type='blog' and top='y' ORDER BY `top` DESC LIMIT 0,5";
	$list = $db->query($sql);
	$row = $db->fetch_array($list);
?>
<DIV class='T'>

<a href="<?php echo Url::log($row['gid']); ?>" title="<?php echo $row['title']; ?>">【置顶】<?php echo $row['title']; ?></a></DIV>

<DIV class='TS'><?php echo subString(strip_tags($row['content']),0,350); ?>
</DIV>
<?php
//推荐文章
	$db = MySql::getInstance();
	$sql = 	"SELECT gid,title,content FROM ".DB_PREFIX."blog WHERE type='blog' and top='y' ORDER BY `top` DESC LIMIT 1,4";
	$list = $db->query($sql);
	while($row = $db->fetch_array($list)){
?>
<li><a href="<?php echo Url::log($row['gid']); ?>" title="<?php echo $row['title']; ?>" rel="bookmark"><?php echo $row['title']; ?></a></li>
<?php } ?>
</DIV>
<DIV CLASS="NEW_INFO LEFT">
<H2><FONT color="#990000">图志</FONT>更新</H2>
<DIV CLASS="infiniteCarousel">
<DIV CLASS="wrapper">

<UL>
<?php
//获取含图片图片的最新文章
function zhong_img(){
$db = MySql::getInstance();
$sql = "SELECT blogid as g,filepath,(SELECT title FROM ".DB_PREFIX."blog where `gid`=g) as t FROM ".DB_PREFIX."attachment WHERE `filepath` LIKE '%jpg' OR `filepath` LIKE '%gif' OR `filepath` LIKE '%png' GROUP BY `blogid` ORDER BY `addtime` DESC LIMIT 0, 8";
$imgs = $db->query($sql);
while($row = $db->fetch_array($imgs)){
$img .= '<li><a href="'.Url::log($row['g']).'" target="_blank" title="'.$row['t'].'"><img src="'.BLOG_URL.substr($row['filepath'],3,strlen($row['filepath'])).'" alt="'.$row['t'].'" /><span class="title">'.$row['t'].'</span></a></li> ';}
echo $img;}
zhong_img();
?>
 </UL>

 </DIV>
</DIV>
</DIV>

<DIV ID="NEW_INFO" CLASS="NEW_INFO LEFT">
<H2><FONT color="#990000">最近</FONT>更新</H2>

<?php foreach($logs as $value): ?>
<DIV ID="NEW_INFO_LIST">
    
<DL>
<DT><a href="<?php echo $value['log_url']; ?>" target='_blank' title="<?php echo $value['log_title']; ?>"><?php echo $value['log_title']; ?></a></DT>
<DD>

<p><?php echo subString(strip_tags($value['content']),0,400); ?></p></DD>
<DD CLASS="TAGS"><span>
<?php echo gmdate('Y-n-j G:i l', $value['date']); ?> <a href="<?php echo $value['log_url']; ?>#comments">Comments:<?php echo $value['comnum']; ?></a></span><?php blog_tag($value['logid']); ?>

</DD>
</DL>

</DIV>
<?php endforeach; ?>

</DIV>
<DIV CLASS="pager"><?php echo $page_url;?></DIV>
</DIV>
<!--sidebar-->
	



<!--end sidebar-->
<?php
 include View::getView('side');
 include View::getView('footer');
?>