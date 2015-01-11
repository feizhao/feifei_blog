<?php
require './core/run.php';
$core->load();
$action=getVars('act','GET');
$core->checkAction($action);
require $core->corePath .'function'.$core->limiter.'admin.php';
switch ($action) {
	case 'login':
		if ($core->user->id>0 && getVars('redirect','GET')) {
			redirect(getVars('redirect','GET'));
			if ($core->checkAction('admin')) {
				redirect('cmd.php?act=admin');
			}
		}
		
		if ($core->user->id==0 && getVars('redirect','GET')) {
			setcookie("redirect", getVars('redirect','GET'),0);
		}
		redirect('login.php');
		break;
	case 'logout':
		logout();
		redirect('/');
		break;
	case 'admin':
		redirect('admin/?act=admin');
		break;
	case 'verify':
		if(verifyLogin()){
			if ($core->user->id>0 && getVars('redirect','COOKIE')) {
				redirect(getVars('redirect','COOKIE'));
			}
			redirect('feifei.php');
		}else{
			redirect('../');
		}
		break;
	case 'search':
		$q=urlencode(trim(strip_tags(getVars('q','POST'))));
		redirect($core->searchurl . '?q=' . $q);
		break;
	case 'misc':
		require './function/misc.php';
		break;
	case 'cmt':
		if(getVars('isajax','POST')){
			Add_Filter_Plugin('Filter_Plugin_APP_ShowError','RespondError',PLUGIN_EXITSIGNAL_RETURN);
		}
		PostComment();
		$core->BuildModule();
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
	 
	case 'ArticleDel':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		DelArticle();
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=ArticleMng');
		break;
	case 'ArticleMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'ArticlePst':
		PostArticle();
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=ArticleMng');
		break;
	case 'PageEdt':
		redirect('admin/edit.php?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'PageDel':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		DelPage();
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=PageMng');
		break;
	case 'PageMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'PagePst':
		PostPage();
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=PageMng');
		break;
	case 'CategoryMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'saveCate':
		saveCate(getVars('name','POST'),getVars('order','POST'),getVars('intro','POST'),getVars('id','POST'));
		break;
	case 'CategoryPst':
		PostCategory();
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=CategoryMng');
		break;
	case 'CategoryDel':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		DelCategory();
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=CategoryMng');
		break;
	case 'CommentDel':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		DelComment();
		$core->BuildModule();
		$core->SetHint('good');
		redirect($_SERVER["HTTP_REFERER"]);
		break;
	case 'CommentChk':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		CheckComment();
		$core->BuildModule();
		$core->SetHint('good');
		redirect($_SERVER["HTTP_REFERER"]);
		break;
	case 'CommentBat':
		if(isset($_POST['id'])==false)redirect($_SERVER["HTTP_REFERER"]);
		BatchComment();
		$core->BuildModule();
		$core->SetHint('good');
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
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		PostMember();
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=MemberMng');
		break;
	case 'MemberDel':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		if(DelMember()){
			$core->BuildModule();
			$core->SetHint('good');
		}else{
			$core->SetHint('bad');
		}
		redirect('cmd.php?act=MemberMng');
		break;
	case 'UploadMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'UploadPst':
		PostUpload();
		$core->SetHint('good');
		redirect('cmd.php?act=UploadMng');
		break;
	case 'UploadDel':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		DelUpload();
		$core->SetHint('good');
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
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=TagMng');
		break;
	case 'TagDel':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		DelTag();
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=TagMng');
		break;
	case 'PluginMng':
		if(getVars('install','GET')){
			InstallPlugin(getVars('install','GET'));
			$core->BuildModule();
		}
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'PluginDis':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		UninstallPlugin(getVars('name','GET'));
		DisablePlugin(getVars('name','GET'));
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=PluginMng');
		break;
	case 'PluginEnb':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		$install='&install=';
		$install .= EnablePlugin(getVars('name','GET'));
		$core->BuildModule();
		$core->SetHint('good');
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
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=ThemeMng' . $install);
		break;
	case 'SidebarSet':
		SetSidebar();
		$core->BuildModule();
		break;
	case 'ModuleEdt':
		redirect('admin/module_edit.php?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'ModulePst':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		PostModule();
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=ModuleMng');
		break;
	case 'ModuleDel':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		DelModule();
		$core->BuildModule();
		$core->SetHint('good');
		redirect('cmd.php?act=ModuleMng');
		break;
	case 'ModuleMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'SettingMng':
		redirect('admin/?' . getVars('QUERY_STRING','SERVER'));
		break;
	case 'SettingSav':
		if(!$core->ValidToken(getVars('token','GET'))){$core->ShowError(5,__FILE__,__LINE__);die();}
		SaveSetting();
		$core->BuildModule();
		$core->SetHint('good');
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
