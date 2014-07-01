<?php
//make sure we have the setup file
include("setup.php");

if (count($args) > 0) {
	$tpl = $args[0].".tpl";	
} else {
	$tpl = '';
}

echo $tpl;

if (file_exists($template_dir.'/'.$tpl) && $tpl != '') {
	$smarty->assign('tpl',$tpl);
} else {
	$smarty->assign('tpl','index.tpl');
}

//tpl
$smarty->display('content.tpl');
?>