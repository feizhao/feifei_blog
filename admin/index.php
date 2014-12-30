<?php
/**
 * @author zhaofei
 * @copyright feifeiblog
 */
error_reporting(E_ALL);
ini_set('display_errors',1);
require '../ablog/base.php';
require 'admin.php';

$ablog->Load();

$action=GetVars('act','GET');

if(($action=='')||($action==null)){$action='admin';}


$f=null;
switch ($action) {
	case 'ArticleMng':
		$f='Admin_ArticleMng';
		$blogtitle=$lang['msg']['article_manage'];
		break;
	case 'PageMng':
		$f='Admin_PageMng';
		$blogtitle=$lang['msg']['page_manage'];
		break;
	case 'CategoryMng':
		$f='Admin_CategoryMng';
		$blogtitle=$lang['msg']['category_manage'];
		break;
	case 'CommentMng':
		$f='Admin_CommentMng';
		$blogtitle=$lang['msg']['comment_manage'];
		break;
	case 'MemberMng':
		$f='Admin_MemberMng';
		$blogtitle=$lang['msg']['member_manage'];
		break;
	case 'UploadMng':
		$f='Admin_UploadMng';
		$blogtitle=$lang['msg']['upload_manage'];
		break;
	case 'TagMng':
		$f='Admin_TagMng';
		$blogtitle=$lang['msg']['tag_manage'];
		break;
	case 'PluginMng':
		$f='Admin_PluginMng';
		$blogtitle=$lang['msg']['plugin_manage'];
		break;
	case 'ThemeMng':
		$f='Admin_ThemeMng';
		$blogtitle=$lang['msg']['theme_manage'];
		break;
	case 'ModuleMng':
		$f='Admin_ModuleMng';
		$blogtitle=$lang['msg']['module_manage'];
		break;
	case 'SettingMng':
		$f='Admin_SettingMng';
		$blogtitle=$lang['msg']['settings'];
		break;
	case 'admin':
		$f='Admin_SiteInfo';
		$blogtitle=$lang['msg']['dashboard'];
		break;
	default:
		die('操作错误');
		break;
}

require $blogpath . 'admin/template/header.php';
require $blogpath . 'admin/template/top.php';

?>
<div id="divMain">
<?php
exit($f);
$f();
?>
</div>
<?php
require $blogpath . 'admin/template/footer.php';


RunTime();
?>