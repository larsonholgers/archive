<?php

$image = $db->GetOne("SELECT CONCAT(`image`.`dir`,`image`.`file`,`image`.`ext`) FROM`image` WHERE `image`.`image_id` = '".$_GET['image_id']."'");

$sql[] = "DELETE FROM `image` WHERE `image_id` = '".$_GET['image_id']."'";
$sql[] = "DELETE FROM `entry_image_link` WHERE `entry_image_link`.`entry_id` = '".$_GET['entry_id']."' AND `image_id` = '".$_GET['image_id']."'";
$imgs_to_remove[] = substr_replace($site_base ,"",-1).$image;

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