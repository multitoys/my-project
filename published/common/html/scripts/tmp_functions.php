<?php

	function addUploadedFile($file, $type='attachments') {
		$_SESSION['upload_'.$type][$file['name']] = $file;
	}

	function getUploadedFilesSize() {
		$size = 0;
		foreach($_SESSION['upload_attachments'] as $file)
			$size += $file['size'];
		foreach($_SESSION['upload_images'] as $file)
			$size += $file['size'];
		return $size;
	}

	function clearUploadedFiles() {
		$_SESSION['upload_attachments'] = array();
		$_SESSION['upload_images'] = array();
	}

	function getUploadedFilesList($type='attachments') {
		$list = array();
		$i = 0;
		foreach($_SESSION['upload_'.$type] as $file) {
			$list[$i]['name'] = $file['name'];
			$list[$i]['size'] = formatFileSize($file['size']);
			$i++;
		}
		return $list;
	}

	function deleteUploadedFile($file_name, $type='attachments') {
		unset($_SESSION['upload_'.$type][$file_name]);
		return getUploadedfilesList($type);
	}

	function getUploadedFile($file_name, $type='attachments') {
		return $_SESSION['upload_'.$type][$file_name];
	}

	function getUploadedFileBody($file_name, $type='attachments') {
		return $_SESSION['upload_'.$type][$file_name]['body'];
	}

	function getUploads($type='attachments') {
		return $_SESSION['upload_'.$type];
	}

	function getUploadsInfo($type='attachments') {
		$info = array();
		if(!empty($_SESSION['upload_'.$type]))
			foreach($_SESSION['upload_'.$type] as $file)
			$info[] = array(
				'name' => $file['name'],
				'type' => $file['type'],
				'size' => $file['size'],
				'path' => false
			);
		return $info;
	}

	function formatFileSize($fileSize) {
		if(!strlen($fileSize))
			return null;

		global $kernelStrings;
		if(count($kernelStrings))
		{
			if ( !$fileSize )
				return sprintf( $kernelStrings['aa_kb_style'], "0.00");
			if ( $fileSize < 1024 )
				$fileSize = 1024;
			if ( $fileSize >= GIGABYTE_SIZE_RELATIVE )
				return sprintf( $kernelStrings['aa_gb_style'], round(ceil($fileSize)/GIGABYTE_SIZE_RELATIVE, 2) );
			elseif ( $fileSize >= MEGABYTE_SIZE )
				return sprintf( $kernelStrings['aa_mb_style'], round(ceil($fileSize)/MEGABYTE_SIZE, 2) );
			else
				return sprintf( $kernelStrings['aa_kb_style'], round(ceil($fileSize)/1024, 2) );
		} else {
			if($fileSize < 1024)
				return $fileSize.' b';
			else if($fileSize >= 1024 && $fileSize < 1024*1024)
				return sprintf('%01.2f', $fileSize/1024.0).' Kb';
			else
				return sprintf('%01.2f',$fileSize/(1024.0*1024)).' Mb';
		}
	}

	function reloadFile($file, $type='attachments') {
		if(is_array($file))
			$_SESSION['upload_'.$type][$file['name']] = $file;
	}

?>