<?php
require './core/run.php';
$core->load();
require $core->corePath .'function'.$core->limiter.'index.php';
show('home.php'); 
runTime();
?>