<?php
//form input
foreach ($_POST['fields'] as $field_id) {
	//DROPDOWNS
	if ($_POST['dropdown'][$field_id] != '') {
		$field_data[$field_id] = $_POST['dropdown'][$field_id];
	}
	
	//TEXTLINES
	if ($_POST['textline'][$field_id] != '') {
		
		//check to see if this exists
		$lookup_sql = "SELECT `value_id` FROM `field_values` WHERE `field_id` = '".$field_id."' AND `value` = '".$_POST['textline'][$field_id]."'";
		$value_id = $db->GetOne($lookup_sql);
		
		//if it doesn't insert it
		if ($value_id == 0) {
			$insert_sql = "INSERT INTO `field_values` (`field_id`, `value`) VALUES ('".$field_id."','".$_POST['textline'][$field_id]."')";
			$db->Execute($insert_sql);
			$value_id = $db->Insert_ID();
		}
		
		$field_data[$field_id] = $value_id;
		
	}
}

//resets..  yes this is ghetto
$field_id = '';
$value_id = '';

//INSERT ENTRY
$add_entry_sql = "INSERT INTO `entry` (`record_id`, `entry_description`, `year`) VALUES ('".$_POST['record_id']."','".$_POST['entry_description']."','".$_POST['year']."')";

$db->Execute($add_entry_sql);
$entry_id = $db->Insert_ID();

//INSERT FIELD INFO
if (is_array($field_data)) {
	foreach($field_data as $field_id => $value_id) {
		$insert_value_sql = "INSERT INTO `entry_field_link` (`entry_id`, `field_id`, `value_id`) VALUES ('".$entry_id."', '".$field_id."', '".$value_id."')";
		$db->Execute($insert_value_sql);
	}
}

//IMAGE UPLOAD
if ($_FILES['image_upload']['name'] != "") {
	$img_upload = uploadFile($_FILES['image_upload']);
	$image_id = $img_upload['uploaded_id'];
}
if ($image_id > 0) {
	$image_sql = "INSERT INTO `entry_image_link` (`entry_id`, `image_id`) VALUES ('".$entry_id."', '".$image_id."')";
	$db->Execute($image_sql);
}
?>