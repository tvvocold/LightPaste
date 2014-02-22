<?php

	// load f3
	$f3 = require("lib/base.php");
	$f3->config("config.ini");
	
	require("data.php");
	require("classes/third-party/hashids.php");
	require("classes/database.php");
	require("classes/util.php");
	
	$f3->set("editor_font_sizes", $DATA_FONTSIZES);
	$f3->set("editor_fonts", $DATA_FONTS);
	
	// main page route
	$f3->route("GET /",
		function($f3)
		{
			global $DATA_LANGUAGES;
			$langs = array();
			ksort($DATA_LANGUAGES);
			foreach($DATA_LANGUAGES as $key=>$value) {
				$data = array();
				$data["mode"] = $value["mode"];
				$data["name"] = $value["name"];
				if(isset($value["mode_complex"])) {
					$data["mode_complex"] = $value["mode_complex"];
				}
				$langs[$key] = $data;
			}
			util::getEditorSettings($f3);
			$f3->set("editor_mode", "'none'");
			$f3->set("editor_readonly", "false");
			$f3->set("page_title", "Light Paste");
			$f3->set("languages", $langs);
			$template = new Template;
			echo $template->render("templates/main.html");
		}
	);
	
	// paste route
	$f3->route("GET /@id",
		function($f3)
		{
			database::connect();
			global $DATA_LANGUAGES;
			util::countView($f3->get("PARAMS.id"));
			$result = util::getPaste($f3->get("PARAMS.id"));
			if(count($result) == 0) {
				header("location: .");
				exit();
			}
			$f3->set("page_title", "Light Paste / " . $f3->get("PARAMS.id"));
			util::getEditorSettings($f3);
			if($result[0]["language"] != "") {
				$language_data = $DATA_LANGUAGES[$result[0]["language"]];
				$f3->set("language_website", $language_data["website"]);
				$f3->set("language_name", $language_data["name"]);
				if(isset($language_data["mode_complex"])) {
					$f3->set("editor_mode", str_replace('"', "'", $language_data["mode_complex"]));
				} else {
					$f3->set("editor_mode", "'$language_data[mode]'");
				}
			} else {
				$f3->set("language_name", "None");
				$f3->set("editor_mode", "'none'");
			}
			$f3->set("paste_date", date("M d, Y", $result[0]["time"]));
			$f3->set("paste_time", date("g:i A", $result[0]["time"]));
			$f3->set("paste_views", number_format($result[0]["views"]));
			$f3->set("paste_size", util::formatDataSize(strlen($result[0]["text"])));
			$f3->set("paste_md5", $result[0]["md5"]);
			$f3->set("paste_sha1", $result[0]["sha1"]);
			$f3->set("editor_readonly", "true");
			$f3->set("editor_text", $result[0]["text"]);
			$f3->set("paste_id", $result[0]["access_id"]);
			$f3->set("paste_private", $result[0]["private"]);
			$template = new Template;
			echo $template->render("templates/paste.html");
		}
	);
	
	// paste mode route
	$f3->route("GET /@id/@mode",
		function($f3)
		{
			global $DATA_LANGUAGES;
			database::connect();
			$result = util::getPaste($f3->get("PARAMS.id"));
			if(count($result) == 0) {
				header("location: .");
				exit();
			}
			if($f3->get("PARAMS.mode") and $f3->get("PARAMS.mode") == "raw") {
				header("Content-Type: text/plain; charset=utf-8");
				echo $result[0]["text"];
				exit();
			} elseif($f3->get("PARAMS.mode") and $f3->get("PARAMS.mode") == "download") {
				$extension = "txt";
				$access_id = $result[0]["access_id"];
				if($result[0]["language"] != "") {
					$language_data = $DATA_LANGUAGES[$result[0]["language"]];
					if(isset($language_data["file_extension"])) {
						$extension = $language_data["file_extension"];
					}
				}
				$file = tempnam("tempfiles/", "txt");
				$handle = fopen($file, "w");
				fwrite($handle, $result[0]["text"]);
				fclose($handle);
				header("Content-Type: text/*");
				header("Content-Length: " . filesize($file));
				header("Content-Disposition: attachment; filename=\"$access_id.$extension\"");
				readfile($file);
				unlink($file);
				exit();
			} elseif($f3->get("PARAMS.mode") and $f3->get("PARAMS.mode") == "copy") {
				$f3->set("SESSION.copy_text", $result[0]["text"]);
				header("location: ../");
				exit();
			} elseif($f3->get("PARAMS.mode") and $f3->get("PARAMS.mode") == "report") {
				$result = util::checkIPLogs($f3->get("IP"), "report_time");
				if(gettype($result) == "array") {
					if(count($result) == 1) {
						$time = time();
						if($result[0]["report_time"] > $time) {
							$wait_time = $result[0]["report_time"] - $time;
							$f3->set("SESSION.error", "You must wait $wait_time seconds before reporting another paste.");
							header("location: ./");
							exit();
						}
					}
				}
				util::logIP($f3->get("IP"), "report_time", $f3->get("REPORT_DELAY"));
				database::query(array("UPDATE pastes SET reported = '1' WHERE access_id = ?;"), array(array(1 => $f3->get("PARAMS.id"))));
				$f3->set("SESSION.message", "The paste you specified has been reported.");
				$f3->set("SESSION.message_title", "Success");
				header("location: ./");
				exit();
			} else {
				$f3->error(404);
			}
		}
	);
	
	// new paste route
	$f3->route("POST /new",
		function($f3)
		{
			global $DATA_LANGUAGES;
			database::connect();
			$result = util::checkIPLogs($f3->get("IP"), "paste_time");
			if(gettype($result) == "array") {
				if(count($result) == 1) {
					$time = time();
					if($result[0]["paste_time"] > $time) {
						$wait_time = $result[0]["paste_time"] - $time;
						$f3->set("SESSION.error", "You must wait $wait_time seconds before creating another paste.");
						header("location: .");
						exit();
					}
				}
			}
			if($f3->get("POST.text")) {
				$text = $f3->get("POST.text");
				$language = "";
				if($f3->get("POST.language")) {
					if(array_key_exists($f3->get("POST.language"), $DATA_LANGUAGES)) {
						$language = $f3->get("POST.language");
					}
				}
				if($f3->get("POST.private")) {
					$private = 1;
				} else {
					$private = 0;
				}
				$result = util::insertPaste($text, $language, $private);
				if(gettype($result) == "string") {
					util::logIP($f3->get("IP"), "paste_time", $f3->get("PASTE_DELAY"));
					header("location: ./$result");
				} else {
					header("location: .");
				}
			} else {
				header("location: .");
			}
		}
	);
	
	$f3->run();
	$f3->set("SESSION.copy_text", NULL);
	$f3->set("SESSION.error", NULL);
	$f3->set("SESSION.message", NULL);
	$f3->set("SESSION.message_title", NULL);
	
?>