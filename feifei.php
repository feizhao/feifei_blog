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
		$act='Admin_ArticleMng';
		$blogtitle=$lang['msg']['article_manage'];
		break;

	case 'admin':
		$act='admin_index';
		$blogtitle=$lang['msg']['dashboard'];
		break;
	default:
		die('操作错误');
		break;
}

require $core->userDir . 'admin/tpl/header.php';
require $core->userDir . 'admin/tpl/top.php';
require $core->userDir . 'admin/tpl/left.php';
show($act);
require $core->userDir . 'admin/tpl/footer.php';
runTime();
?>
