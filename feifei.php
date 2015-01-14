<?php
@ini_set('display_errors',1);
require './core/run.php';
$core->load();
require $core->corePath .'function'.$core->limiter.'admin.php';
$action=getVars('act','GET');
if(($action=='')||($action==null)){$action='admin';}
$core->checkAction($action);
$f=null;
switch ($action) {
	case 'ArticleMng':
		$act='articleMng';
		$blogtitle=$core->lang['msg']['article_manage'];
		break;
	case 'newArticle':
		$act='newArticle';
		$blogtitle=$core->lang['msg']['article_manage'];
		break;
	case 'admin':
		$act='tpl/index';
		$blogtitle=$core->lang['msg']['dashboard'];
		break;
	default:
		die('操作错误');
		break;
}

require $core->userDir . 'admin/tpl/header.php';
require $core->userDir . 'admin/tpl/top.php';
require $core->userDir . 'admin/tpl/left.php';
?>
<div class="am-cf admin-main">
<?php 
show($act);
?>
</div>
<?php
require $core->userDir . 'admin/tpl/footer.php';
runTime();
?>
