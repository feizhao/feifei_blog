<?php 
/**
 * 侧边栏
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
<div class="am-u-md-4 blog-sidebar">
  <div class="am-panel-group">
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
<?php if (Option::get('rss_output_num')):?>
<section class="am-panel am-panel-default">
<div class="am-panel-hd">
<a href="<?php echo BLOG_URL; ?>rss.php" title="RSS订阅"><img src="<?php echo TEMPLATE_URL; ?>images/rss.gif" alt="订阅Rss"/></a>
</div>
</section>

<?php endif;?>
</div> <!--end am-panel-group -->
</div><!--end #siderbar-->
