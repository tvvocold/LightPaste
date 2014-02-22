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
			global $f3;
			$result = database::query("SELECT MAX(id) AS id FROM pastes;");
			$max_id = $result[0]["id"];
			if($max_id == NULL) {
				$max_id = 1;
			}
			$paste_access_id = self::generatePasteID($max_id);
			$paste_md5 = md5($paste_text);
			$paste_sha1 = sha1($paste_text);
			// insert the new paste into the database using a prepared statement
			database::query(array("INSERT INTO pastes(access_id, text, 
				time, language, md5, sha1, private, ipaddress) 
				VALUES(?, ?, UNIX_TIMESTAMP(), ?, ?, ?, ?, ?)"), 
				array(array(
					1 => $paste_access_id, 
					2 => $paste_text,
					3 => $paste_language,
					4 => $paste_md5,
					5 => $paste_sha1,
					6 => $paste_private,
					7 => $f3->get("IP"))
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
			return database::query(array("SELECT access_id, text, 
				language, time, views, md5, sha1, views, private 
				FROM pastes WHERE access_id = ?"),
				array(array(1 => $id)));
		}
		
		/*=======================================================
			func: generatePasteID($id)
			desc: generate a new paste id
		=======================================================*/
		static function generatePasteID($id)
		{
			$salt = bin2hex(openssl_random_pseudo_bytes(35));
			$hashids = new Hashids\Hashids($salt, 8);
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
			if(isset($_COOKIE["editor_match_brackets"])) {
				if($_COOKIE["editor_match_brackets"] == 1) {
					$f3->set("editor_match_brackets", "true");
				} else {
					$f3->set("editor_match_brackets", "false");
				}
			} else {
				$f3->set("editor_match_brackets", 'true');
			}
			if(isset($_COOKIE["editor_highlight_active_line"])) {
				if($_COOKIE["editor_highlight_active_line"] == 1) {
					$f3->set("editor_highlight_active_line", "true");
				} else {
					$f3->set("editor_highlight_active_line", "false");
				}
			} else {
				$f3->set("editor_highlight_active_line", "true");
			}
			if(isset($_COOKIE["editor_tab_size"])) {
				$tabsize = intval($_COOKIE["editor_tab_size"]);
				$min = $f3->get("EDITOR_MINIMUM_TABSIZE");
				$max = $f3->get("EDITOR_MAXIMUM_TABSIZE");
				if($tabsize > $max) {
					$tabsize = $max;
				} elseif($tabsize < $min) {
					$tabsize = $min;
				}
				$f3->set("editor_tab_size", $tabsize);
			} else {
				$f3->set("editor_tab_size", $f3->get("EDITOR_DEFAULT_TABSIZE"));
			}
			if(isset($_COOKIE["editor_cursor_blinkrate"])) {
				$f3->set("editor_cursor_blinkrate", intval($_COOKIE["editor_cursor_blinkrate"]));
			} else {
				$f3->set("editor_cursor_blinkrate", $f3->get("EDITOR_DEFAULT_BLINKRATE"));
			}
			if(isset($_COOKIE["editor_font_size"])) {
				$f3->set("editor_font_size", intval($_COOKIE["editor_font_size"]));
			} else {
				$f3->set("editor_font_size", $f3->get("EDITOR_DEFAULT_FONTSIZE"));
			}
			if(isset($_COOKIE["editor_font"])) {
				if(array_key_exists($_COOKIE["editor_font"], $DATA_FONTS)) {
					$f3->set("editor_font", $DATA_FONTS[$_COOKIE["editor_font"]]);
				} else {
					$f3->set("editor_font", $DATA_FONTS[$f3->get("EDITOR_DEFAULT_FONT")]);
				}
			} else {
				$f3->set("editor_font", $DATA_FONTS[$f3->get("EDITOR_DEFAULT_FONT")]);
			}
		}
		
		static function countView($paste_id)
		{
			global $f3;
			$delay = $f3->get("VIEWCOUNT_DELAY");
			$paste = self::getPaste($paste_id);
			$time = time();
			database::query(array("DELETE FROM viewlogs WHERE ? > time + ?;"), array(array(1 => $time, 2 => $delay)));
			if(gettype($paste) == "array") {
				$hash = hash("sha256", $paste_id . $_SERVER["REMOTE_ADDR"]);
				$logrows = database::query(array("SELECT time FROM viewlogs WHERE hash = ?;"), array(array(1=> $hash)));
				if(count($logrows) == 1) {
					return;
				}
				database::query(array("UPDATE pastes SET views = views + 1 WHERE access_id = ?;", 
					"INSERT INTO viewlogs (hash, time) VALUES(?, ?);"), 
					array(array(1 => $paste_id), 
					array(1 => $hash, 2 => $time))
				);
			}
		}
		
		static function logIP($ip, $type, $modifier)
		{
			$time = time();
			$result = database::query(array("SELECT ipaddress FROM iplogs WHERE ipaddress = ?;"), array(array(1 => $ip)));
			if(gettype($result) == "array") {
				if(count($result) == 1) {
					database::query(array("UPDATE iplogs SET $type = ? WHERE ipaddress = ?;"), array(array(1 => ($time + $modifier), 2 => $ip)));
				} else {
					database::query(array("INSERT INTO iplogs (ipaddress, $type) VALUES(?, ?);"), array(array(1 => $ip, 2 => ($time + $modifier))));
				}
			}
			database::query("DELETE FROM iplogs WHERE paste_time < UNIX_TIMESTAMP() AND report_time < UNIX_TIMESTAMP();");
		}
		
		static function checkIPLogs($ip, $field)
		{
			return database::query(array("SELECT $field FROM iplogs WHERE ipaddress = ?;"), array(array(1 => $ip)));
		}
	}
	
?>