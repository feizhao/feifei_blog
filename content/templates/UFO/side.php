<?php 
/*
* 侧边栏
*/
if(!defined('__ROOT__')) {exit('error!');} 
?>
<DIV CLASS="RIGHT SIDEBAR">            
  <DIV CLASS="SIDEBAR_TAB">
  <UL id="TRI_SIDEBAR" class="TRI RIGHT"><SPAN><FONT color="#990000">互动</FONT>排行榜</SPAN>
      <LI CLASS="SELECTED">最新评论<LI>最活跃读者</LI>
  </UL>
  </DIV>
  <DIV CLASS="S" id="PICBOX_SIDEBAR">
  <UL>
    <?php global $CACHE; 
	$com_cache = $CACHE->readCache('comment');
	foreach($com_cache as $value):
	$url = Url::log($value['gid']).'#'.$value['cid'];
	?>
	<li><?php echo $value['name']; ?>:<a href="<?php echo $url; ?>"><?php echo $value['content']; ?></a></li>
	<?php endforeach; ?>
  </UL>


  <UL CLASS="NONE">
     
	  <div id="hotfriends">
<UL class="hotfriends"> 
<?php
$db = MySql::getInstance();
$sql = "SELECT COUNT(poster) AS p,poster,mail,url FROM ".DB_PREFIX."comment WHERE mail !='' AND url !='' AND hide='n' GROUP BY poster ORDER BY p DESC LIMIT 15";
$count = $db->query($sql);
while($row = $db->fetch_array($count)){
	$g = getGravatar($row['mail']);
	$mostactive .= '<a href="'. $row['url'] . '" title="' .$row['poster'].' 发表 '. $row['p'] . ' 条评论"  rel="nofollow" target="_blank"><img class="avatar avatar-50 photo" width="50" height="50" src="'.$g.'" alt="'.$row['poster'].'发表'.$row['p'].'条评论!" /></a>';
	}
	echo $mostactive;
?>


</UL>
 <div style="clear:both;"></div>
</div>
</UL>
 
  </DIV>
  <?php 
$widgets = !empty($options_cache['widgets1']) ? unserialize($options_cache['widgets1']) : array();
doAction('diff_side');
foreach ($widgets as $val)
{
	$widget_title = @unserialize($options_cache['widget_title']);
	$custom_widget = @unserialize($options_cache['custom_widget']);
	if(strpos($val, 'custom_wg_') === 0)
	{
		$callback = 'widget_custom_text';
		if(function_exists($callback))
		{
			call_user_func($callback, htmlspecialchars($custom_widget[$val]['title']), $custom_widget[$val]['content']);
		}
	}else{
		$callback = 'widget_'.$val;
		if(function_exists($callback))
		{
			preg_match("/^.*\s\((.*)\)/", $widget_title[$val], $matchs);
			$wgTitle = isset($matchs[1]) ? $matchs[1] : $widget_title[$val];
			call_user_func($callback, htmlspecialchars($wgTitle));
		}
	}
}
?>
</DIV>
</DIV>