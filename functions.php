<?php
function findexts ($filename) {
	$filename = strtolower($filename) ;
	$exts = split("[/\\.]", $filename) ;
	$n = count($exts)-1;
	$exts = $exts[$n];
	return $exts;
}

function uploadFile($file,$update="", $upload_to="/images/entries/") {	
	
	// START UPLOAD
	//This function separates the extension from the rest of the file name and returns it
	
	//This applies the function to our file
	$ext = findexts ($file['name']) ; 
	
	$db = newDB();
	
	//This assigns the subdirectory you want to save into... make sure it exists!
	$cwd = getcwd();
	$target = $cwd.$upload_to;
	$file_ext = ".".$ext;
	
	//path to image directory for DB
	$upload_to_db = str_replace($_SERVER['DOCUMENT_ROOT'], "", getcwd()).$upload_to;
	
	//look up in DB and create file number
	$sql_cnt=$db->GetRow('SELECT file FROM image ORDER BY file DESC LIMIT 1');
	
	echo $sql_cnt['file'];
	
	//the new file will be one more then the last	
	$file_id = $sql_cnt['file']+1;	
	
	echo $file_id;
	
	if ($update > 0) {
		
		//image to update
		$update_db = $db->GetRow("SELECT * FROM image WHERE `image_id` = '".$update."'");
		
		//temporarily rename it
		$update_img = $update_db['file'].$update_db['ext'];
		$update_img_tmp = $update_db['file']."_delete".$update_db['ext'];
		rename($target.$update_img, $target.$update_img_tmp);
		
	}
	
	//make target be the full path
	$target = $target.$file_id.$file_ext;
	
	if (move_uploaded_file($file['tmp_name'], $target)) {
		//insert in the DB
		
		if ($update == "") {
			$sql = "INSERT INTO image (image_id, file, ext, dir) VALUES (NULL, ".$file_id.", '".$file_ext."', '".$upload_to_db."')";
			$db->Execute($sql);
			$callback['uploaded_id'] = $db->Insert_ID();
		} else {
			$sql = "UPDATE image SET file = '".$file_id."', ext = '".$file_ext."', dir = '".$upload_to_db."' WHERE image_id = '".$update."'";
			$db->Execute($sql);
			$callback['uploaded_id'] = $update;
		}

		$callback['uploaded'] = TRUE;		
		
	} else {
	
		$callback['uploaded'] = FALSE;
		
	}
	
	return $callback;
}

function cleanInput($input) {
 
	$search = array(
	'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
	'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
	'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
	'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);

	$output = preg_replace($search, '', $input);
	return $output;
	
}
  
function sanitize($input) {

	if (is_array($input)) {
	    foreach($input as $var=>$val) {
	        $output[$var] = sanitize($val);
	    }
	} else {
	    $input = stripslashes($input);
		$input = htmlentities($input);
		$input = strip_tags($input);
		$output = $input;
	}
	return $output;
	
}

function insertSQL ($table, $data) {
	
	//UPDATE SQL
	$column_sql = "(";
	$data_sql 	= "(";			
	foreach ($data as $c => $d) {
		
		if ($d != "") {
			$column_sql .= "`".$c."`,";					
			$data_sql 	.= "'".$d."',";
		}
		
	}			
	$column_sql = substr_replace($column_sql,"",-1);
	$data_sql = substr_replace($data_sql,"",-1);
	$column_sql .= ")";
	$data_sql 	.= ")";
	
	$sql = "INSERT INTO ".$table." ".$column_sql." VALUES ".$data_sql;
	
	return $sql;
	
}

function updateSQL ($table, $data, $key) {
	
	//DUPLICATE SQL
	$sql_update = '';
	foreach ($data as $k => $v) {
		$sql_update .= "`$k` = '$v',";
	}
	$sql_update = substr_replace($sql_update ,"",-1);
	
	$sql_where = '';
	foreach ($key as $k => $v) {
		$sql_where .= "`$k` = '$v' AND ";
	}
	$sql_where = substr_replace($sql_where,"",-5);
	
	$sql = "UPDATE ".$table." SET ".$sql_update." WHERE ".$sql_where;
	
	return $sql;
	
}

function logWrite ($file,$message) {
	if (file_exists($file)) {
	  $fh = fopen($file, 'a');
	  fwrite($fh, $message."\n");
	} else {
	  $fh = fopen($file, 'w');
	  fwrite($fh, $message."\n");
	}
	fclose($fh);
}
?>