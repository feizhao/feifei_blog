<?php if(!defined('__ROOT__')) {exit('error!');}?>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
    <h3>登录</h3>
    <hr>
    <br>

    <form class="am-form" action="./index.php?action=auth" method="post">
      <label for="user">用户名:</label>
       <input type="text" name="user" />
      <br>
      <label for="password">密码:</label>
      <input type="password" name="pw" />
      <br>
      <?php echo $ckcode; ?>
      <br>
      <div class="am-cf">
        <input type="submit" class="am-btn am-btn-primary am-btn-sm am-fl" value="登 录" name="">
        <!-- <input type="submit" class="am-btn am-btn-default am-btn-sm am-fr" value="忘记密码 ^_^? " name=""> -->
      </div>
    </form>
    <hr>
    <p>&copy; 后天 .</p>
  </div>
</div>
 
