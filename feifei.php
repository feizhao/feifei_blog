<?php
require './zb_system//run.php';
$zbp->CheckGzip();
$zbp->Load();
ViewIndex(); 
RunTime();
?>