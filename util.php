<?php

	class util {
		/*=======================================================
			func: formatDataSize($bytes)
			desc: formats the given data size into a string
			source: http://stackoverflow.com/questions/5501427/php-filesize-mb-kb-conversion
		=======================================================*/
		static function formatDataSize($bytes)
		{
			if($bytes >= 1073741824) {
				$bytes = number_format($bytes / 1073741824, 2) . ' GB';
			} elseif($bytes >= 1048576) {
				$bytes = number_format($bytes / 1048576, 2) . ' MB';
			} elseif($bytes >= 1024) {
				$bytes = number_format($bytes / 1024, 2) . ' KB';
			} elseif($bytes > 1) {
				$bytes = $bytes . ' bytes';
			} elseif($bytes == 1) {
				$bytes = $bytes . ' byte';
			} else {
				$bytes = '0 bytes';
			}
			return $bytes;
		}
	}
	
?>