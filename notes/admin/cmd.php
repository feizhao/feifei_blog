<?php
require '../ablog/run.php';
$ablog->load();
$action=getVars('act','GET');
if($ablog->checkAction($action)){$ablog->error(6,__FILE__,__LINE__);die();}
// exit($action);
switch ($action) {
	case 'login':
		if ($ablog->user->id>0 && getVars('redirect','GET')) {
			redirect(getVars('redirect','GET'));
		}
		if ($ablog->checkAction('admin')) {
			redirect('cmd.php?act=admin');
		}
		if ($ablog->user->id==0 && getVars('redirect','GET')) {
			setcookie("redirect", getVars('redirect','GET'),0,$ablog->cookiespath);
		}

		redirect('tpl/login.php');
		break;
	case 'logout':
		logout();
		redirect('../');
		break;
	case 'admin':
		redirect('admin/?act=admin');
		break;
	case 'verify':
		if(verifyLogin()){
			if ($ablog->user->ID>0 && getVars('redirect','COOKIE')) {
				redirect(getVars('redirect','COOKIE'));
			}
			redirect('admin/?act=admin');
		}else{
			redirect('../');
		}
		break;
	case 'search':
		$q=urlencode(trim(strip_tags(getVars('q','POST'))));
		redirect($ablog->searchurl . '?q=' . $q);
		break;
	case 'misc':
		require './function/misc.php';
		break;
	case 'cmt':
		if(getVars('isajax','POST')){
			Add_Filter_Plugin('Filter_Plugin_APP_ShowError','RespondError',PLUGIN_EXITSIGNAL_RETURN);
		}
		PostComment();
		$ablog->BuildModule();
		if(getVars('isajax','POST')){
			die();
		}else{
			redirect(getVars('HTTP_REFERER','SERVER'));
		}
		break;
	case 'getcmt':
		ViewComments((int)getVars('postid','GET'),(int)getVars('page','GET'));
		die();
		break;
	case 'ArticleEdt':
		redirect('admin/edit.php?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'ArticleDel':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		DelArticle();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=ArticleMng');
		break;
	case 'ArticleMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'ArticlePst':
		PostArticle();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=ArticleMng');
		break;
	case 'PageEdt':
		redirect('admin/edit.php?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'PageDel':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		DelPage();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=PageMng');
		break;
	case 'PageMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'PagePst':
		PostPage();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=PageMng');
		break;
	case 'CategoryMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'CategoryEdt':
		redirect('admin/category_edit.php?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'CategoryPst':
		PostCategory();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=CategoryMng');
		break;
	case 'CategoryDel':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		DelCategory();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=CategoryMng');
		break;
	case 'CommentDel':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		DelComment();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect($_SERVER["HTTP_REFERER"]);
		break;
	case 'CommentChk':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		CheckComment();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect($_SERVER["HTTP_REFERER"]);
		break;
	case 'CommentBat':
		if(isset($_POST['id'])==false)redirect($_SERVER["HTTP_REFERER"]);
		BatchComment();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect($_SERVER["HTTP_REFERER"]);
		break;
	case 'CommentMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'MemberMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'MemberEdt':
		redirect('admin/member_edit.php?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'MemberNew':
		redirect('admin/member_edit.php?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'MemberPst':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		PostMember();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=MemberMng');
		break;
	case 'MemberDel':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		if(DelMember()){
			$ablog->BuildModule();
			$ablog->SetHint('good');
		}else{
			$ablog->SetHint('bad');
		}
		redirect('cmd.php?act=MemberMng');
		break;
	case 'UploadMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'UploadPst':
		PostUpload();
		$ablog->SetHint('good');
		redirect('cmd.php?act=UploadMng');
		break;
	case 'UploadDel':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		DelUpload();
		$ablog->SetHint('good');
		redirect('cmd.php?act=UploadMng');
		break;
	case 'TagMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'TagEdt':
		redirect('admin/tag_edit.php?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'TagPst':
		PostTag();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=TagMng');
		break;
	case 'TagDel':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		DelTag();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=TagMng');
		break;
	case 'PluginMng':
		if(getVars('install','GET')){
			InstallPlugin(getVars('install','GET'));
			$ablog->BuildModule();
		}
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'PluginDis':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		UninstallPlugin(getVars('name','GET'));
		DisablePlugin(getVars('name','GET'));
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=PluginMng');
		break;
	case 'PluginEnb':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		$install='&install=';
		$install .= EnablePlugin(getVars('name','GET'));
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=PluginMng' . $install);
		break;
	case 'ThemeMng':
		if(getVars('install','GET')){
			InstallPlugin(getVars('install','GET'));
		}
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'ThemeSet':
		$install='&install=';
		$install .=SetTheme(getVars('theme','POST'),getVars('style','POST'));
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=ThemeMng' . $install);
		break;
	case 'SidebarSet':
		SetSidebar();
		$ablog->BuildModule();
		break;
	case 'ModuleEdt':
		redirect('admin/module_edit.php?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'ModulePst':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		PostModule();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=ModuleMng');
		break;
	case 'ModuleDel':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		DelModule();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=ModuleMng');
		break;
	case 'ModuleMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'SettingMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'SettingSav':
		if(!$ablog->ValidToken(getVars('token','GET'))){$ablog->ShowError(5,__FILE__,__LINE__);die();}
		SaveSetting();
		$ablog->BuildModule();
		$ablog->SetHint('good');
		redirect('cmd.php?act=SettingMng');
		break;
	case 'ajax':
		foreach ($GLOBALS['Filter_Plugin_Cmd_Ajax'] as $fpname => &$fpsignal) {
			$fpname(getVars('src','GET'));
		}
		break;
	default:
		# code...
		break;
}
