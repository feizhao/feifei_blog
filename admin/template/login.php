<?php
require '../../ablog/run.php';
$ablog->Load();
?>
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title><?php echo $ablog->name . '-' . $lang['msg']['login']?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="alternate icon" type="image/png" href="<?php echo $ablog->assets; ?>i/favicon.png">
  <link rel="stylesheet" href="<?php echo $ablog->assets; ?>css/amazeui.min.css"/>
  <style>
    .header {
      text-align: center;
    }
    .header h1 {
      font-size: 200%;
      color: #333;
      margin-top: 30px;
    }
    .header p {
      font-size: 14px;
    }
  </style>
</head>
<body>
<div class="header">
  <div class="am-g">
    <h1><?php echo htmlspecialchars($blogname)?></h1>
    <p><?php echo htmlspecialchars($blogsubname)?></p>
  </div>
  <hr />
</div>
<div class="am-g">
  <div class="col-lg-6 col-md-8 col-sm-centered">
    <h3>登录</h3>
    <hr>
  <!--   <div class="am-btn-group">
      <a href="#" class="am-btn am-btn-secondary am-btn-sm"><i class="am-icon-github am-icon-sm"></i> Github</a>
      <a href="#" class="am-btn am-btn-success am-btn-sm"><i class="am-icon-google-plus-square am-icon-sm"></i> Google+</a>
      <a href="#" class="am-btn am-btn-primary am-btn-sm"><i class="am-icon-stack-overflow am-icon-sm"></i> stackOverflow</a>
    </div> -->
    

    <form method="post" class="am-form">
      <label for="username"><?php echo $lang['msg']['username']?>:</label>
      <input type="text" name="" id="username" value="<?php echo getVars('username','COOKIE')?>">
      <br>
      <label for="password"><?php echo $lang['msg']['password']?>:</label>
      <input type="password" name="password" id="password" value="">
      <br>
      <label for="remember-me">
        <input id="remember-me" type="checkbox">
        <?php echo $lang['msg']['stay_signed_in']?>
      </label>
      <br />
      <div class="am-cf">
        <input type="submit" name="" value="登 录" class="am-btn am-btn-primary am-btn-sm am-fl">
        <input type="submit" name="" value="忘记密码 ^_^? " class="am-btn am-btn-default am-btn-sm am-fr">
      </div>
    </form>
    <hr>
    <p>© <?php echo  $ablog->config;?></p>
  </div>
</div>
</body>
</html>