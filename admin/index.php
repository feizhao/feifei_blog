<?php
/**
 * @author zhaofei
 * @copyright ablog
 */
error_reporting(E_ALL);
ini_set('display_errors',1);
require '../ablog/run.php';
require 'admin.php';

$ablog->Load();

$action=GetVars('act','GET');

if(($action=='')||($action==null)){$action='admin';}
if (!$ablog->checkAction($action)) {$ablog->error(6,__FILE__,__LINE__);die();}

$f=null;
switch ($action) {
	case 'ArticleMng':
		$f='Admin_ArticleMng';
		$blogtitle=$lang['msg']['article_manage'];
		break;

	case 'admin':
		$f='admin_index';
		$blogtitle=$lang['msg']['dashboard'];
		break;
	default:
		die('操作错误');
		break;
}

require $blogpath . 'admin/template/header.php';
require $blogpath . 'admin/template/top.php';
require $blogpath . 'admin/template/left.php';
?>
<div id="divMain">
<?php
$f();
?>
</div>
<?php
require $blogpath . 'admin/template/footer.php';


?>
