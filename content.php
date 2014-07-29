<?php
//make sure we have the setup file
include("setup.php");

//remove the folder i.e. "xxx.xxx.x.xxx/archive/"
if(($key = array_search($site_folder, $args)) !== false) {
    unset($args[$key]);
    $args = array_values($args);
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

//fields
$all_fields = $db->GetAll("SELECT * FROM `field` ORDER BY `table_display` ASC, `table_order` ASC");
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

//display entries
if ($args[0] != 'edit') {
	$entries = $db->GetAll("SELECT * FROM `entry`");
	foreach ($entries as $k => $e) {
		$entries[$k]['values'] = $db->GetAssoc("SELECT `field_id`, `value_id` FROM `entry_field_link` WHERE `entry_id` = '".$e['entry_id']."'");
		$entries[$k]['images'] = $db->GetCol("SELECT `image_id` FROM `entry_image_link` WHERE `entry_id` = '".$e['entry_id']."'");
	}
	$smarty->assign('entries',$entries);	
}

//edit
if ($args[0] == 'edit') {
	$entry = $db->GetRow("SELECT * FROM `entry` WHERE `entry_id` = '".$args[1]."'");
	$entry['fields'] = $db->GetAssoc("SELECT `field_id`, `value_id` FROM `entry_field_link` WHERE `entry_id` = '".$args[1]."'");
	$entry['images'] = $db->GetCol("SELECT `image_id` FROM `entry_image_link` WHERE `entry_id` = '".$args[1]."'");
	$smarty->assign('entry',$entry);
}

$years = array_combine(range(1973,date('Y')), range(1973,date('Y')));

$smarty->assign('years',$years);
$smarty->assign('values',$values);
$smarty->assign('fields',$fields);

//tpl
$smarty->display('content.tpl');
?>