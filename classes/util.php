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
		
		/*=======================================================
			func: insertPaste($paste_text, $paste_language, $paste_private)
			desc: inserts a new paste into the database
		=======================================================*/
		static function insertPaste($paste_text, $paste_language, $paste_private)
		{
			$db = new DB\SQL("mysql:host=localhost;port=3306;dbname=lightpaste", "root", "");
			$result = $db->exec("SELECT MAX(id) AS id FROM pastes;");
			$max_id = $result[0]["id"];
			if($max_id == NULL) {
				$max_id = 1;
			}
			$paste_access_id = self::generatePasteID($max_id);
			$paste_md5 = md5($paste_text);
			$paste_sha1 = sha1($paste_text);
			// insert the new paste into the database using a prepared statement
			$db->exec(
				array(
					"INSERT INTO pastes(access_id, text, 
					time, language, md5, sha1, private) 
					VALUES(?, ?, UNIX_TIMESTAMP(), ?, ?, ?, ?)"
				), 
				array(
					array(
						1 => $paste_access_id, 
						2 => $paste_text,
						3 => $paste_language,
						4 => $paste_md5,
						5 => $paste_sha1,
						6 => $paste_private
					)
				)
			);
			return $paste_access_id;
		}
		
		/*=======================================================
			func: getPaste($id)
			desc: gets a paste from the database
		=======================================================*/
		static function getPaste($id)
		{
			$db = new DB\SQL("mysql:host=localhost;port=3306;dbname=lightpaste", "root", "");
			$result = $db->exec(array("SELECT access_id, text, 
				language, time, views, md5, sha1 
				FROM pastes WHERE access_id = ?"),
				array(array(1 => $id))
			);
			return $result;
		}
		
		/*=======================================================
			func: generatePasteID($id)
			desc: generate a new paste id
		=======================================================*/
		static function generatePasteID($id)
		{
			$salt = bin2hex(openssl_random_pseudo_bytes(35));
			$hashids = new Hashids\Hashids($salt, 5);
			return $hashids->encrypt($id);
		}
		
		/*=======================================================
			func: getEditorSettings($f3)
			desc: gets editor settings stored in client cookies
		=======================================================*/
		static function getEditorSettings($f3)
		{
			global $DATA_FONTS;
			if(isset($_COOKIE["editor_line_numbers"])) {
				if($_COOKIE["editor_line_numbers"] == 1) {
					$f3->set("editor_line_numbers", "true");
				} else {
					$f3->set("editor_line_numbers", "false");
				}
			} else {
				$f3->set("editor_line_numbers", "true");
			} 
			if(isset($_COOKIE["editor_line_wrapping"])) {
				if($_COOKIE["editor_line_wrapping"] == 1) {
					$f3->set("editor_line_wrapping", "true");
				} else {
					$f3->set("editor_line_wrapping", "false");
				}
			} else {
				$f3->set("editor_line_wrapping", "false");
			}
			if(isset($_COOKIE["editor_smart_indent"])) {
				if($_COOKIE["editor_smart_indent"] == 1) {
					$f3->set("editor_smart_indent", "true");
				} else {
					$f3->set("editor_smart_indent", "false");
				}
			} else {
				$f3->set("editor_smart_indent", 'true');
			}
			if(isset($_COOKIE["editor_tab_size"])) {
				$f3->set("editor_tab_size", intval($_COOKIE["editor_tab_size"]));
			} else {
				$f3->set("editor_tab_size", 4);
			}
			if(isset($_COOKIE["editor_cursor_blinkrate"])) {
				$f3->set("editor_cursor_blinkrate", intval($_COOKIE["editor_cursor_blinkrate"]));
			} else {
				$f3->set("editor_cursor_blinkrate", 530);
			}
			if(isset($_COOKIE["editor_font_size"])) {
				$f3->set("editor_font_size", intval($_COOKIE["editor_font_size"]));
			} else {
				$f3->set("editor_font_size", 14);
			}
			if(isset($_COOKIE["editor_font"])) {
				if(array_key_exists($_COOKIE["editor_font"], $DATA_FONTS)) {
					$f3->set("editor_font", $DATA_FONTS[$_COOKIE["editor_font"]]);
				} else {
					$f3->set("editor_font", $DATA_FONTS["Courier New"]);
				}
			} else {
				$f3->set("editor_font", $DATA_FONTS["Courier New"]);
			}
		}
	}
	
?>