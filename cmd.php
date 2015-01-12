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
	case 'saveTag':
		saveTag(getVars('name','POST'),getVars('order','POST'),getVars('id','POST'));
		break;
	case 'saveActicle':
		saveActicle();
		break;
	           
	 
	default:
		# code...
		break;
}
