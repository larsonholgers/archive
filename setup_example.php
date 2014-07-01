<?php
//base dir
$base_dir = "[PATH TO BASE DIR]";
$shared_dir = "[PATH TO SHARED RESOURCES]";
$template_dir = $base_dir.'templates';

//smarty
// put full path to Smarty.class.php
require($shared_dir.'smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir($template_dir);
$smarty->setCompileDir($shared_dir.'smarty/templates_c');
$smarty->setCacheDir($shared_dir.'smarty/cache');
$smarty->setConfigDir($shared_dir.'smarty/configs');
$smarty->setPluginsDir (array($shared_dir.'smarty/plugins',$template_dir.'/plugins'));

//adodb
$path_to_adodb = $shared_dir.'adodb5/adodb.inc.php';
if ($path_to_adodb != "" && $path_to_adodb != "PATH_TO_ADODB") {
	include($path_to_adodb);
}

//database
function newDB() {
	$conn = ADONewConnection('mysqli');
	if (!$conn->Connect("localhost","root","root","archive")) {
		exit;
		return false;
	} else {
		return $conn;
	}
}
$db = newDB();

//functions
include($base_dir."functions.php");

//args
$args = explode('/',$_SERVER['REQUEST_URI']);
foreach ($args as $k => $a) {
	if (strstr($a, "?")) {
		unset($args[$k]);
	}
}
array_shift($args);
$args = array_filter($args);

//sanitize stuff
$_POST = sanitize($_POST);

if ($_POST['action'] == 'add_entry') {

	include('actions/add_entry.php');
	
}

//fields
$all_fields = $db->GetAll("SELECT * FROM `field`");
//values
foreach ($all_fields as $k => $field) {
	$fields[$field['field_id']] = $field;
	$fields[$field['field_id']]['input_textline'] = "textline[".$field['field_id']."]";
	$fields[$field['field_id']]['input_dropdown'] = "dropdown[".$field['field_id']."]";
	$field_values = $db->GetAssoc("SELECT `value_id`, `value` FROM `field_values` WHERE `field_id` = '".$field['field_id']."'");
	$fields[$field['field_id']]['values'] = $field_values;
	
	//get all values
	foreach ($field_values as $id => $v) {
		$values[$id] = $v;
	}
}

//get all entries
$entries = $db->GetAll("SELECT * FROM `entry`");
foreach ($entries as $k => $e) {
	$entries[$k]['values'] = $db->GetAssoc("SELECT `field_id`, `value_id` FROM `entry_field_link` WHERE `entry_id` = '".$e['entry_id']."'");
	$entries[$k]['images'] = $db->GetCol("SELECT `image_id` FROM `entry_image_link` WHERE `entry_id` = '".$e['entry_id']."'");
}

$years = array_combine(range(1973,date('Y')), range(1973,date('Y')));

$smarty->assign('years',$years);
$smarty->assign('entries',$entries);
$smarty->assign('values',$values);
$smarty->assign('fields',$fields);
?>