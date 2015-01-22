<?php 
/*
* 首页日志列表部分
*/
if(!defined('__ROOT__')) {exit('error!');} 
if($pageurl == BLOG_URL.'page/' || $pageurl == BLOG_URL.'?page='){
        include View::getView('home');
	}else{
?>

<DIV CLASS="CLEAR CONTENT">
<DIV CLASS="MAIN LEFT">
<DIV CLASS="NEW_INFO LEFT">
<H2><FONT color="#990000">最新</FONT>图文</H2>
<DIV CLASS="infiniteCarousel">
    <DIV CLASS="wrapper">
      <UL>
<?php
//获取首页幻灯片
function top_img(){
			$db = MySql::getInstance();
			$sql = "SELECT blogid as g,filepath,(SELECT title FROM ".DB_PREFIX."blog where `gid`=g) as t FROM ".DB_PREFIX."attachment WHERE `filepath` LIKE '%jpg' OR `filepath` LIKE '%gif' OR `filepath` LIKE '%png' GROUP BY `blogid` ORDER BY `addtime` DESC LIMIT 0, 4";
			$imgs = $db->query($sql);
    		while($row = $db->fetch_array($imgs)){
          $img .= '<li><a href="'.Url::log($row['g']).'" target="_blank" title="'.$row['t'].'"><img src="'.BLOG_URL.substr($row['filepath'],3,strlen($row['filepath'])).'" alt="'.$row['t'].'" /><span class="title">'.$row['t'].'</span></a></li> ';}
          echo $img;		
	}
	top_img();
	?>
      </UL>
    </DIV>
</DIV>
</DIV>
 
<DIV ID="NEW_INFO" CLASS="NEW_INFO LEFT">
<H2><SPAN>本类文章</SPAN>列表</H2>

<DIV ID="NEW_INFO_LIST">
        
<?php doAction('index_loglist_top'); ?>
<?php foreach($logs as $value): ?>
 
 <DL>
<DT><a href="<?php echo $value['log_url']; ?>" target='_blank' title="<?php echo $value['log_title']; ?>"><?php topflg($value['top']); ?><?php echo $value['log_title']; ?></a></DT>
<DD><p><?php echo subString(strip_tags($value['log_description']),0,300); ?></p></DD>
<DD CLASS="TAGS"><SPAN><?php echo gmdate('Y-n-j G:i l', $value['date']); ?> <a href="<?php echo $value['log_url']; ?>#comments">Comments:<?php echo $value['comnum']; ?></a></SPAN><?php blog_tag($value['logid']); ?></DD>
</DL>



<?php endforeach; ?>

 
</DIV>
<DIV CLASS="pager"><?php echo $page_url;?></DIV>
</DIV>
</DIV>
<?php
 include View::getView('side');
 include View::getView('footer');
 }
?>
