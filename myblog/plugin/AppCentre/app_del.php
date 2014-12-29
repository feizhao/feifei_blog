<?php 
require '../../../system/function/base.php';

require '../../../system/function/admin.php';

require 'function.php';

$zbp->Load();

$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}

if (!$zbp->CheckPlugin('AppCentre')) {$zbp->ShowError(48);die();}


function rrmdir($dir) { 
	if (is_dir($dir)) { 
		$objects = scandir($dir); 
		foreach ($objects as $object) { 
			if ($object != '.' && $object != '..') { 
				if (filetype($dir.'/'.$object) == 'dir') rrmdir($dir.'/'.$object); else unlink($dir.'/'.$object); 
			} 
		}
		reset($objects);
		rmdir($dir);
	} 
} 


rrmdir($zbp->usersdir . $_GET['type'] . '/' . $_GET['id']);

Redirect($_SERVER["HTTP_REFERER"]);