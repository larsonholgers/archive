<?php
//make sure we have the setup file
include("setup.php");

//remove the folder i.e. "xxx.xxx.x.xxx/archive/"
if(($key = array_search($site_folder, $args)) !== false) {
    unset($args[$key]);
}

if (count($args) > 0) {
	$tpl = $args[0].".tpl";	
} else {
	$tpl = '';
}

if (file_exists($template_dir.'/'.$tpl) && $tpl != '') {
	$smarty->assign('tpl',$tpl);
} else {
	$smarty->assign('tpl','index.tpl');
}

//tpl
$smarty->display('content.tpl');
?>