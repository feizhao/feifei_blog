<?php if(!defined('__ROOT__')) {exit('error!');}?>
<div class=containertitle><b>应用中心</b>
<div class=line></div>
<iframe src="<?php echo OFFICIAL_SERVICE_HOST;?>store/<?php echo Option::EMLOG_VERSION; ?>/<?php echo $site_url_encode; ?>" id="main" name="main" width="100%" height="910" frameborder="0" scrolling="yes" style="overflow: visible;display:"></iframe>
<script>
$("#menu_store").addClass('sidebarsubmenu1');
</script>