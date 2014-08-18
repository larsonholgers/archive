<?php
//form input
foreach ($_POST['entry_ids'] as $entry_id) {
	
	//delete entry
	$sql[] = "DELETE FROM `entry` WHERE `entry`.`entry_id` = '".$entry_id."'";
	
	//delete fields
	$sql[] = "DELETE FROM `entry_field_link` WHERE `entry_field_link`.`entry_id` = '".$entry_id."'";
	
	
	//delete images
		//get images first
		$images = $db->GetAssoc("SELECT `entry_image_link`.`image_id`, CONCAT(`image`.`dir`,`image`.`file`,`image`.`ext`) FROM `entry_image_link`, `image` WHERE `entry_image_link`.`entry_id` = '".$entry_id."' AND `entry_image_link`.`image_id` = `image`.`image_id`");
		
		foreach ($images as $image_id => $image) {
			$sql[] = "DELETE FROM `image` WHERE `image_id` = '".$image_id."'";
			$imgs_to_remove[] = substr_replace($site_base ,"",-1).$image;
		}
		
		$sql[] = "DELETE FROM `entry_image_link` WHERE `entry_image_link`.`entry_id` = '".$entry_id."'";
	
}

//loop through all the deletes
foreach ($sql as $s) {
	$db->Execute($s);
}

//get rid of the images
$imgs_to_remove = array_unique($imgs_to_remove);
foreach ($imgs_to_remove as $i) {
	if (unlink($i)) {
		$message .= $i." removed<br />";
	} else {
		$message .= $i." not removed<br />";
	}
}

?>