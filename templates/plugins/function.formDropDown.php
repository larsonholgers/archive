<?php
function smarty_function_formDropDown($params, &$smarty) {
	
	# required: $name, $options #
	# optional: $selected, $no_selection #
	
	extract($params);
	
	if ($yn) {
		$options = array(
			'Y' => 'Yes',
			'N' => 'No'
		);
	}
	
	if ($ny) {
		$options = array(
			'N' => 'No',
			'Y' => 'Yes'
		);
	}
	
	$return .= "<select name=\"".$name."\" class=\"form-control\">";
	if ($no_selection != "") {
		$return .= "<option value=\"\">".$no_selection."</option>";
	}
	
	foreach ($options as $k => $v) {
		if ($restrict != '') {
			if ($k < $restrict) {
				$s = '';
				if ($selected == $k) { $s = "selected=\"selected\""; }
				$return .= "<option value=\"".$k."\" ".$s.">".$v."</option>\n";		
			}
		} else {
			$s = '';
			if ($selected == $k) { $s = "selected=\"selected\""; }
			$return .= "<option value=\"".$k."\" ".$s.">".$v."</option>\n";				
		}
	}
	$return .= "</select>";
	
	return $return;
}
/* vim: set expandtab: */
?>