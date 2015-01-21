<?php
/*
Template Name:易玩印象CMS
Description:一款由《易玩稀有》制作的CMS模板
Version:1.5
Author:emlog
Author Url:http://www.ewceo.com
Sidebar Amount:0
ForEmlog:5.1.2
*/
if(!defined('__ROOT__')) {exit('error!');}
require_once View::getView('module');
require_once View::getView('option');
if(function_exists('emLoadJQuery')) {
    emLoadJQuery();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $site_title; ?></title>
<meta name="keywords" content="<?php echo $site_key; ?>" />
<meta name="description" content="<?php echo $site_description; ?>" />
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php echo BLOG_URL; ?>xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="<?php echo BLOG_URL; ?>wlwmanifest.xml" />
<link rel="alternate" type="application/rss+xml" title="RSS"  href="<?php echo BLOG_URL; ?>rss.php" />
<link href="<?php echo TEMPLATE_URL; ?>main.css" rel="stylesheet" type="text/css" />
<script src="<?php echo BLOG_URL; ?>include/lib/js/common_tpl.js" type="text/javascript"></script>
<?php doAction('index_head'); ?>
<script type="text/javascript">
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?"":e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1;};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p;}('$(0(){$("\\b\\6\\k\\e\\2\\i\\5\\1\\B\\b\\D\\6\\8\\j\\9\\v\\7\\A\\b\\6\\H").n(0(){$(a).p(\'\\b\\6\').I(r)},0(){$(a).p(\'\\b\\6\').E(r)})});$(h).m(0(){$("\\c\\8\\f\\2\\u\\G\\g\\c\\f\\2\\u\\g\\v\\j\\q\\8\\d\\7\\e").o("\\f")});$(0(){$("\\c\\5\\i\\8\\4\\g\\6\\8").n(0(){$(a).o("\\9\\2\\l\\1\\d")},0(){$(a).F("\\9\\2\\l\\1\\d")})});$(h).m(0(){$("\\k\\1\\3\\4\\5\\7").C(0(){$(a).X("\\9\\d\\1\\q","\\9\\e\\e\\i\\j\\s\\s\\3\\3\\3\\c\\1\\3\\4\\1\\2\\c\\4\\2\\5");$("\\k\\1\\3\\4\\5\\7").W("\\U\\V\\Z\\13")})});0 y(t){14 x=h.12(t);z(x){w 10}11{w M}};$(0(){z(!y("\\1\\3\\4\\5\\7"))N("\\L\\J\\K\\O\\S\\T\\R\\P\\Q\\Y")})',62,67,'function|x65|x6f|x77|x63|x6d|x6c|x73|x69|x68|this|x75|x2e|x72|x74|x62|x20|document|x70|x3a|x23|x76|ready|hover|addClass|find|x66|100|x2f|ewcms1|x78|x61|return|ewcms2|ewcms0|if|x28|x6e|each|x3e|slideUp|removeClass|x31|x29|slideDown|u52ff|u4fee|u8bf7|false|alert|u6539|u8005|u4fe1|u4f5c|u6a21|u677f|u6613|u73a9|html|attr|u606f|u7a00|true|else|getElementById|u6709|var'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?"":e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1;};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p;}('6 5="";6 i=0;6 a="";6 3="";$(e).v(4(){a=$(".9 2").x;b(i);$(".9 2").w(4(){u(5);3=$(y).t();$(".l 2").f().8(3).g();$(".9 2").c("7").8(3).d("7")},4(){i=3+1<a?3+1:0;5=s("b("+i+")",p)})});4 b(k){i=k;$(".l 2").f().8(i).g();$(".9 2").c("7").8(i).d("7");i=i+1<a?i+1:0;5=s("b("+i+")",p)};z.C=4(){r($(e).j()>B){$("#q").g(o)}n{$("#q").f(o)};r($(e).j()>A){$("#m").d(\'h\')}n{$("#m").c(\'h\')}}',39,39,'||li|dq|function|xf|var|navli|eq|thnav|len|luenbo|removeClass|addClass|document|fadeOut|fadeIn|logrt||scrollTop|num|ipic|lograd|else|200|5000|totop|if|setTimeout|index|clearTimeout|ready|hover|length|this|window|793|100|onscroll'.split('|'),0,{}))
</script>
</head>
<body>
<div id="topbox">
	<div id="head" class="wrap">
    	<div id="ilogo">
        <?php if ($ilogo == 1) :?>
			<a href="<?php echo BLOG_URL; ?>"><img src="<?php echo TEMPLATE_URL; ?>images/logo.png" alt="<?php echo $blogname; ?>" /></a>
		<?php else: ?>
        	<a href="<?php echo BLOG_URL; ?>"><?php echo $blogname; ?></a>
        <?php endif;?>
        </div>
        <div id="headsh">
			<div id="topnav">
<?php if(ROLE == 'admin' || ROLE == 'writer'): ?><a href="<?php echo BLOG_URL; ?>admin/">网站管理</a>&nbsp;&nbsp;<a href="<?php echo BLOG_URL; ?>admin/?action=logout">[退出]</a><?php else: ?><a href="<?php echo BLOG_URL; ?>admin/">登录</a><?php endif; ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $guestbookurl; ?>">留言建议</a>
			</div>
<form name="keyform" method="get" action="<?php echo BLOG_URL; ?>index.php">
<input name="keyword" type="text" value="搜索更给力..." onFocus="if(this.value=='搜索更给力...'){this.value=''};" onblur="if(this.value==''){this.value='搜索更给力...'};" value="搜索更给力..." class="hdin" x-webkit-speech x-webkit-grammar="builtin:translate" /><input type="submit" value="搜索" class="hdbtn" />
</form>
        </div>
        <?php if ($itext == 1) :?>
        <div id="toptext">
        <?php echo $bloginfo; ?>
        </div>
		<?php else: ?><?php endif;?>
    </div>
</div>
<div id="topmenubox">
	<div id="nav" class="wrap"><?php blog_navi();?></div>
</div>