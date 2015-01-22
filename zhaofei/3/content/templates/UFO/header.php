<?php
/*
Template Name:UFO(舍力修复版)
Description:CMS风格 ……
Author:Syan(原作者)
Author Url:http://www.shuyong.net
Sidebar Amount:1
ForEmlog:4.1.0
*/
if(!defined('__ROOT__')) {exit('error!');}
require_once View::getView('module');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $site_title;if($page>=2){echo ' - 第'.$page.'页';}?></title>
<meta name="keywords" content="<?php echo $site_key; ?>" />
<meta name="description" content="<?php echo $site_description; ?>" />
<LINK rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>themes/UFO_Cms/STYLE/UFO_Cms.css"/>
<SCRIPT language="javascript" src="<?php echo TEMPLATE_URL; ?>themes/UFO_Cms/SCRIPT/Sean_flash.js" type="text/javascript"></SCRIPT>
<SCRIPT language="javascript" src="<?php echo TEMPLATE_URL; ?>themes/UFO_Cms/SCRIPT/Sean_script.js" type="text/javascript"></SCRIPT>
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php echo BLOG_URL; ?>xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="<?php echo BLOG_URL; ?>wlwmanifest.xml" />
<link rel="alternate" type="application/rss+xml" title="RSS"  href="<?php echo BLOG_URL; ?>rss.php" />
<script src="<?php echo BLOG_URL; ?>include/lib/js/common_tpl.js" type="text/javascript"></script>
<?php doAction('index_head'); ?>
</HEAD>
<BODY>
<DIV CLASS=TOP_HEADER>
<DIV CLASS=CONTENT>
<UL><a href="javascript:window.external.AddFavorite('<?php echo BLOG_URL; ?>','<?php echo $blogname; ?>')"><b>收藏本站</b></a></UL>
<SPAN>欢迎访问<?php echo $blogname; ?></SPAN>
</DIV>
</DIV>
<DIV CLASS="HEADER">
<DIV CLASS="CONTENT"><A HREF="<?php echo BLOG_URL; ?>" TITLE="<?php echo $blogname; ?>" CLASS="LOGO LEFT"></A>
<DIV CLASS="SEARCH_BOX RIGHT">
<SPAN>
<DIV ID="BLOG_SUB"><?php echo $bloginfo; ?></DIV>
</SPAN>
<DIV CLASS="SEARCH RIGHT">
<DIV CLASS="SEARCH_01 LEFT">
<form name="keyform" method="get" action="<?php echo BLOG_URL; ?>" CLASS="SEARCHFORM">
<DIV CLASS="LEFT"><input name="keyword" CLASS="SEARCH_INPUT" type="text" placeholder="善用搜索,事半功倍" /></DIV>
<input type="image" class="sousuo" title="搜索" src="<?php echo TEMPLATE_URL; ?>themes/UFO_Cms/STYLE/UFO_img/btn_srch.gif" /></form>
</DIV>
<DIV CLASS="HOT_TAGS LEFT"> 
<?php 
global $CACHE;
$tag_cache = $CACHE->readCache('tags');
for ($i=0; $i<=7; $i++){ ?>
<a href="<?php echo Url::tag($tag_cache[$i]['tagurl']); ?>" title="<?php echo $tag_cache[$i]['usenum']; ?> 篇日志"><?php echo $tag_cache[$i]['tagname']; ?></a>		
<?php } ?>	
</DIV></DIV>
</DIV>
</DIV>
<DIV CLASS="CLEAR CONTENT MENU">
<UL>
<?php echo blog_navi(); ?>
</UL><SPAN ID="MOOD"><FONT color="#1C86EE"><?php $newtws_cache = $CACHE->readCache('newtw');echo $newtws_cache[0]['t'];?></FONT></SPAN>
</DIV>
</DIV>