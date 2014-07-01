<?php
function smarty_function_image($params, &$smarty) {
	extract($params);
	$db = newDB();
	
	if ($output == '') {
		$output = "echo";
	}
	
	if ($w > 0) { $width = "width=\"".$w."\""; }
	if ($h > 0) { $height = "height=\"".$h."\""; }
	
	$image = $db->GetRow("SELECT image.* FROM image WHERE image_id = '".$image_id."'");
	
	if ($output == "echo") {
		echo "<img src=\"".$image['dir'].$image['file'].$image['ext']."\" ".$width." ".$height." />";
	}
	
	if ($output == "var") {
		$return['embed'] = "<img src=\"".$image['dir'].$image['file'].$image['ext']."\" ".$width." ".$height." />";
		$return['url'] = $image['dir'].$image['file'].$image['ext'];
		$return['size'] = getimagesize($_SERVER['DOCUMENT_ROOT'].$return['url']);
		$smarty->assign($assign,$return);
	}
	
	
}

?>
