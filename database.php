<?php

	/*=======================================================
		class: db
		desc: performs database operations
	=======================================================*/
	class db {
		
		/*=======================================================
			func: query($sql, $data = false)
			desc: performs a database query using a prepared
				  statement
		=======================================================*/
		static function query($sql, $data = NULL)
		{
			global $CONFIG_DATABASE_HOST;
			global $CONFIG_DATABASE_USER;
			global $CONFIG_DATABASE_PASSWORD;
			global $CONFIG_DATABASE_NAME;
			try {
				$dbh = new PDO("mysql:host=$CONFIG_DATABASE_HOST;dbname=$CONFIG_DATABASE_NAME", $CONFIG_DATABASE_USER, $CONFIG_DATABASE_PASSWORD);
			} catch(PDOException $error) {
				$_SESSION["lightpaste_error"] = $error->getMessage();
				header("location: ../");
				exit();
			}
			$statement = $dbh->prepare($sql);
			if(!$statement->execute($data)) {
				$error_data = $statement->errorInfo();
				$_SESSION["lightpaste_error"] = $error_data[2] . " (" . $error_data[1] . ") (" . $error_data[0] . ")";
				header("location: ../");
				exit();
			}
			$rows = array();
			while($row = $statement->fetch()) {
				$rows[] = $row;
			}
			return $rows;
		}
		
		/*=======================================================
			func: insertPaste($paste_code, $paste_language)
			desc: inserts a new paste into the database
		=======================================================*/
		static function insertPaste($paste_code, $paste_language)
		{
			// create an access id for the new paste
			$id_query_rows = self::query("SELECT MAX(id) as id FROM pastes;");
			$max_id = $id_query_rows[0]["id"] or 1;
			$paste_access_id = self::generatePasteID($max_id);
			// create paste hashes
			$paste_md5 = md5($paste_code);
			$paste_sha1 = sha1($paste_code);
			// insert the new paste into the database using a prepared statement
			self::query("INSERT INTO pastes(access_id, code, 
			time, language, md5, sha1) VALUES(?, ?, UNIX_TIMESTAMP(), ?, ?, ?)", array(
					$paste_access_id, 
					$paste_code, 
					$paste_language,
					$paste_md5,
					$paste_sha1
				)
			);
			return $paste_access_id;
		}
		
		/*=======================================================
			func: generatePasteID($max_id)
			desc: generates a new paste id based on the number
				  given
		=======================================================*/
		static function generatePasteID($max_id)
		{
			if(function_exists("openssl_random_pseudo_bytes")) {
				$bytes = bin2hex(openssl_random_pseudo_bytes(5));
				return substr($bytes, 0, 5) . base_convert((2000000 + $max_id), 10, 36) . substr($bytes, 5);
			} else {
				$str = "";
				$chars = "0123456789abcdefghijklmnopqrstuvwxyz";
				for($i=0; $i < 10; $i++) {
					$str .= $chars[mt_rand(0, strlen($chars) - 1)];
				}
				return $str;
			}
		}
		
		/*=======================================================
			func: getPaste($access_id)
			desc: gets a paste form the database
		=======================================================*/
		static function getPaste($access_id)
		{
			$rows = self::query("SELECT code, language, time, views, md5, sha1 FROM pastes WHERE access_id = ?", array($access_id));
			if(count($rows) == 1) {
				return $rows[0];
			} else {
				$_SESSION["lightpaste_error"] = "Paste not found.";
				return false;
			}
		}
		
		static function countView($paste_id)
		{
			global $CONFIG_VIEWCOUNT_DELAY;
			$paste = self::getPaste($paste_id);
			$time = time();
			self::query("DELETE FROM viewlogs WHERE ? > time + ?;", array($time, $CONFIG_VIEWCOUNT_DELAY));
			if(gettype($paste) == "array") {
				$hash = hash("sha256", $paste_id . $_SERVER["REMOTE_ADDR"]);
				$logrows = self::query("SELECT time FROM viewlogs WHERE hash = ?;", array($hash));
				if(count($logrows) == 1) {
					return;
				}
				self::query("UPDATE pastes SET views = views + 1 WHERE access_id = ?;", array($paste_id));
				self::query("INSERT INTO viewlogs (hash, time) VALUES(?, ?);", array($hash, $time));
			}
		}
	}
	
?>