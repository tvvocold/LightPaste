<?php

	/*
		Light Paste
		Copyright (c) 2014 Kenny Shields
	*/

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
			func: insertPaste($text, $language, $private, $expiration, $snap)
			desc: inserts a new paste into the database
		=======================================================*/
		static function insertPaste($text, $language, $private, $expiration, $snap)
		{
			global $f3;
			$result = database::query("SELECT MAX(id) AS id FROM pastes;");
			$max_id = $result[0]["id"];
			if($max_id == NULL) {
				$max_id = 1;
			}
			$access_id = self::generatePasteID($max_id);
			$md5 = md5($text);
			$sha1 = sha1($text);
			// insert the new paste into the database using a prepared statement
			database::query(array("INSERT INTO pastes(access_id, text,
				time, language, md5, sha1, views, private, reported, ipaddress,
				expiration, snap) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"),
				array(array(
					1 => $access_id,
					2 => $text,
					3 => time(),
					4 => $language,
					5 => $md5,
					6 => $sha1,
					7 => 0,
					8 => $private,
					9 => 0,
					10 => $f3->get("IP"),
					11 => $expiration,
					12 => $snap
				))
			);
			return $access_id;
		}

		/*=======================================================
			func: getPaste($id)
			desc: gets a paste from the database
		=======================================================*/
		static function getPaste($id)
		{
			return database::query(array("SELECT access_id, text,
				language, time, views, md5, sha1, views, private,
				expiration, snap, hits FROM pastes WHERE access_id = ?"),
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
			func: readSetting($setting)
			desc: reads a setting that is expected to have a
				  value of either 0 or 1
		=======================================================*/
		static function readSetting($setting)
		{
			global $f3;
			if(isset($_COOKIE[$setting])) {
				if($_COOKIE[$setting] == 1) {
					$f3->set($setting, "true");
				} else {
					$f3->set($setting, "false");
				}
			} else {
				$f3->set($setting, "true");
			}
		}

		/*=======================================================
			func: getClientSettings($f3)
			desc: gets settings stored in client cookies
		=======================================================*/
		static function getClientSettings($f3)
		{
			global $DATA_FONTS;
			global $DATA_THEMES;
			// parse toggle settings
			self::readSetting("editor_line_numbers");
			self::readSetting("editor_line_wrapping");
			self::readSetting("editor_smart_indent");
			self::readSetting("editor_match_brackets");
			self::readSetting("editor_match_tags");
			self::readSetting("editor_highlight_active_line");
			self::readSetting("editor_highlight_occurrences");
			self::readSetting("editor_vertical_ruler");
			// set editor tab size
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
			// set editor blink rate
			if(isset($_COOKIE["editor_cursor_blinkrate"])) {
				$f3->set("editor_cursor_blinkrate", intval($_COOKIE["editor_cursor_blinkrate"]));
			} else {
				$f3->set("editor_cursor_blinkrate", $f3->get("EDITOR_DEFAULT_BLINKRATE"));
			}
			// set editor font size
			if(isset($_COOKIE["editor_font_size"])) {
				$f3->set("editor_font_size", intval($_COOKIE["editor_font_size"]));
			} else {
				$f3->set("editor_font_size", $f3->get("EDITOR_DEFAULT_FONTSIZE"));
			}
			// set editor font
			if(isset($_COOKIE["editor_font"])) {
				if(array_key_exists($_COOKIE["editor_font"], $DATA_FONTS)) {
					$f3->set("editor_font", $DATA_FONTS[$_COOKIE["editor_font"]]);
				} else {
					$f3->set("editor_font", $DATA_FONTS[$f3->get("EDITOR_DEFAULT_FONT")]);
				}
			} else {
				$f3->set("editor_font", $DATA_FONTS[$f3->get("EDITOR_DEFAULT_FONT")]);
			}
			if(isset($_COOKIE["editor_vbarpos"])) {
				$pos = intval($_COOKIE["editor_vbarpos"]);
				$min = $f3->get("EDITOR_MINIMUM_VBARPOS");
				$max = $f3->get("EDITOR_MAXIMUM_VBARPOS");
				if($pos > $max) {
					$pos = $max;
				} elseif($pos < $min) {
					$pos = $min;
				}
				$f3->set("editor_vbarpos", $pos);
			} else {
				$f3->set("editor_vbarpos", $f3->get("EDITOR_DEFAULT_VBARPOS"));
			}
			// set site theme
			if(isset($_COOKIE["site_theme"])) {
				if(array_key_exists($_COOKIE["site_theme"], $DATA_THEMES)) {
					$f3->set("site_theme", $_COOKIE["site_theme"]);
					$f3->set("editor_theme", $DATA_THEMES[$_COOKIE["site_theme"]]["editor_theme"]);
				} else {
					$f3->set("site_theme", "default");
					$f3->set("editor_theme", "lightpaste");
				}
			} else {
				$f3->set("site_theme", "default");
				$f3->set("editor_theme", "lightpaste");
			}
		}

		/*=======================================================
			func: countView($paste_id)
			desc: increments the total number of views for a
				  specific paste
		=======================================================*/
		static function countView($paste_id)
		{
			global $f3;
			$delay = $f3->get("VIEWCOUNT_DELAY");
			$paste = self::getPaste($paste_id);
			$time = time();
			database::query(array("DELETE FROM viewlogs WHERE ? > time + ?;"), array(array(1 => $time, 2 => $delay)));
			if(gettype($paste) == "array") {
				if($paste[0]["snap"] == 1 and $paste[0]["hits"] == 3) {
					return;
				}
				database::query("UPDATE pastes SET hits = ? WHERE access_id = ?;", array(1 => $paste[0]["hits"] + 1, 2 => $paste_id));
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

		/*=======================================================
			func: logIP($ip, $type, $modifier)
			desc: logs an action performed by the client
		=======================================================*/
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
			database::query("DELETE FROM iplogs WHERE paste_time < :time AND report_time < :time;", array(":time" => time()));
		}

		/*=======================================================
			func: checkIPLogs($ip, $field)
			desc: checks the ip logs for relevant data
		=======================================================*/
		static function checkIPLogs($ip, $field)
		{
			$result = database::query(array("SELECT $field FROM iplogs WHERE ipaddress = ?;"), array(array(1 => $ip)));
			if(gettype($result) == "array" and count($result) == 1) {
				$time = time();
				if($result[0][$field] > $time) {
					return $wait_time = $result[0][$field] - $time;
				}
			}
			return true;
		}

		/*=======================================================
			func: clearCommonSessionData()
			desc: clears common data stored in the client's
				  session
		=======================================================*/
		static function clearCommonSessionData()
		{
			global $f3;
			$f3->clear("SESSION.copy_text");
			$f3->clear("SESSION.error");
			$f3->clear("SESSION.message");
			$f3->clear("SESSION.message_title");
		}

		/*=======================================================
			func: processNewPasteData($f3)
			desc: processes new paste data received from the
				  client
		=======================================================*/
		static function processNewPasteData($f3)
		{
			global $DATA_LANGUAGES;
			global $DATA_EXPIRATIONS;
			database::connect();
			$logcheck = util::checkIPLogs($f3->get("IP"), "paste_time");
			if(gettype($logcheck) != "boolean") {
				return array("403 Forbidden", "You must wait $logcheck seconds before creating another paste");
			}
			if($f3->get("POST.text")) {
				$text = $f3->get("POST.text");
				$language = "";
				$private = 0;
				$expiration = 0;
				$snap = 0;
				if($f3->get("POST.language")) {
					if(array_key_exists($f3->get("POST.language"), $DATA_LANGUAGES)) {
						$language = $f3->get("POST.language");
					}
				}
				if($f3->get("POST.visibility")) {
					if($f3->get("POST.visibility") == "private") {
						$private = 1;
					}
				}
				if($f3->get("POST.expiration")) {
					if(array_key_exists($f3->get("POST.expiration"), $DATA_EXPIRATIONS)) {
						$expiration = time() + $DATA_EXPIRATIONS[$f3->get("POST.expiration")];
					}
				}
				if($f3->get("POST.snap")) {
					if($f3->get("POST.snap") == "true") {
						$snap = 1;
					}
				}
				$result = util::insertPaste($text, $language, $private, $expiration, $snap);
				if(gettype($result) == "string") {
					util::logIP($f3->get("IP"), "paste_time", $f3->get("PASTE_DELAY"));
					return $result;
				} else {
					return array("500 Internal Server Error", "An error occurred while trying to create a new paste");
				}
			} else {
				return array("400 Bad Request", "No paste text was specified");
			}
		}

		static function deletePasteText($paste_id)
		{
			database::query("UPDATE pastes SET text = '' WHERE access_id = ?;", array(1 => $paste_id));
		}
	}

?>
