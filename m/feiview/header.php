<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $site_title; ?></title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="<?php echo TEMPLATE_URL; ?>assets/i/favicon.ico">
  <link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>assets/css/amazeui.min.css">
  <link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>assets/css/app.css">
</head>
<body>
<!-- Header -->
<header data-am-widget="header" class="am-header am-header-default">
  <h1 class="am-header-title">
     <a href="./"><?php echo Option::get('blogname'); ?>
  </h1>
</header>

<!-- Menu -->
<nav data-am-widget="menu" class="am-menu  am-menu-offcanvas1" data-am-menu-offcanvas>
  <a href="javascript: void(0)" class="am-menu-toggle">
    <i class="am-menu-toggle-icon am-icon-bars"></i>
  </a>
  <div class="am-offcanvas">
    <div class="am-offcanvas-bar">
      <ul class="am-menu-nav sm-block-grid-1">
      <?php if(ISLOGIN === true): ?>
      <li class="am-parent">
      <a href="##">翌念</a>
      <ul class="am-menu-sub am-collapse  sm-block-grid-3 ">
        <li class="">
          <a href="./?action=write">写文章</a> 
        </li>
        <li class="">
           <a href="./?action=logout">退出</a>
        </li>
        
      </ul>
      </li>    
      <?php else:?>
      <li class=""> 
        <a href="<?php echo BLOG_URL; ?>m/?action=login">登录</a>
      </li>
      <?php endif;?>
        <li class=""> 
          <a href="./">首页</a>
        </li>
        <li class="">
           <?php if(Option::get('istwitter') == 'y'): ?>
            <a href="./?action=tw">微语</a>
            <?php endif;?>
        </li>
      </ul>
    </div>
  </div>
</nav>


 