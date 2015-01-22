<?php 
/**
 * 页面底部信息
 */
if(!defined('__ROOT__')) {exit('error!');} 
?>
</div> <!--end #left  right content-->
<footer class="blog-footer">
  <p><?php echo $blogname; ?><br/>
    <small>© <?php echo $icp; ?></a> <?php echo $footer_info; ?></small>
    <?php doAction('index_footer'); ?>
  </p>
</footer>

<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="<?php echo TEMPLATE_URL; ?>assets/js/polyfill/rem.min.js"></script>
<script src="<?php echo TEMPLATE_URL; ?>assets/js/polyfill/respond.min.js"></script>
<script src="<?php echo TEMPLATE_URL; ?>assets/js/amazeui.legacy.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="<?php echo TEMPLATE_URL; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo TEMPLATE_URL; ?>assets/js/amazeui.min.js"></script>
<script>prettyPrint();</script>
<!--<![endif]-->

</body>
</html>