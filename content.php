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

//actions
switch ($_POST['action']){
	case 'add_entry':
		include('actions/add_entry.php');
	break;
	case 'edit_entry':
		include('actions/edit_entry.php');
	break;
	case 'delete_entry':
		include('actions/delete_entry.php');
	break;
	default:
	break;
}

//fields
$all_fields = $db->GetAll("SELECT * FROM `field` ORDER BY `table_display` ASC, `table_order` ASC");
//values
foreach ($all_fields as $k => $field) {
	$fields[$field['field_id']] = $field;
	$fields[$field['field_id']]['input_textline'] = "textline[".$field['field_id']."]";
	$fields[$field['field_id']]['input_dropdown'] = "dropdown[".$field['field_id']."]";
	$field_values = $db->GetAssoc("SELECT `value_id`, `value` FROM `field_values` WHERE `field_id` = '".$field['field_id']."' ORDER BY `value` ASC");
	$fields[$field['field_id']]['values'] = $field_values;
	
	//get all values
	foreach ($field_values as $id => $v) {
		$values[$id] = $v;
	}
}

//display entries
if ($args[0] != 'edit' || $args[0] != 'page') {

	//default sorting arguments
	$order_by = array('entry_id', 'DESC');
	$where = '';
	$limit = array(0,24);
	
	if ($args[1] != '') {
		$limit = explode("-", $args[1]);
	}
	
	//sorting argument sql
	if (is_array($order_by)) {
		$sql_order_by = "ORDER BY `".$order_by[0]."` ".$order_by[1];
	}
	if (is_array($where)) {
		$sql_where = "WHERE `".$where[0]."` = '".$where[1]."'";
	}
	if ($limit > 0) {
		$sql_limit = "LIMIT ".$limit[0].", ".$limit[1];
	}

	$entries_total_sql = "SELECT COUNT(*) FROM `entry` $sql_where $sql_order_by";
	$entries_sql = "SELECT * FROM `entry` $sql_where $sql_order_by $sql_limit";
	$entries_total = $db->GetOne($entries_total_sql);
	
	$entries = $db->GetAll($entries_sql);
	foreach ($entries as $k => $e) {
		$entries[$k]['values'] = $db->GetAssoc("SELECT `field_id`, `value_id` FROM `entry_field_link` WHERE `entry_id` = '".$e['entry_id']."'");
		$entries[$k]['images'] = $db->GetCol("SELECT `image_id` FROM `entry_image_link` WHERE `entry_id` = '".$e['entry_id']."'");
	}
	$smarty->assign('entries',$entries);	

	//pagination
	$page['total_items'] = $entries_total;
	$page['total_pages'] = ceil($entries_total/$limit[1]);
	$pages = range(1, $page['total_pages']);
	$start = 0;
	foreach ($pages as $p) {
		$page['pages'][$p] = $start."-".$limit[1];
		$start = $start+$limit[1];
	}
	$smarty->assign('page',$page);
	
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

$link_root = str_replace('content.php', '', $_SERVER['PHP_SELF']);
$smarty->assign('link_root', $link_root);

//messages
$smarty->assign('message',$message);

//tpl
$smarty->display('content.tpl');
?>