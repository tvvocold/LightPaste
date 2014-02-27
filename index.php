<?php

	// load f3
	$f3 = require("lib/base.php");
	$f3->config("config.ini");
	
	require("core/data.php");
	require("core/classes/third-party/hashids.php");
	require("core/classes/database.php");
	require("core/classes/util.php");
	require("core/api.php");
	
	$f3->set("editor_font_sizes", $DATA_FONTSIZES);
	$f3->set("editor_fonts", $DATA_FONTS);
	$f3->set("site_themes", $DATA_THEMES);
	
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
			util::clearCommonSessionData();
		}
	);
	
	// paste route
	$f3->route("GET /paste/@id",
		function($f3)
		{
			database::connect();
			global $DATA_LANGUAGES;
			util::countView($f3->get("PARAMS.id"));
			$result = util::getPaste($f3->get("PARAMS.id"));
			if(count($result) == 0) {
				header("location: /");
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
			util::clearCommonSessionData();
		}
	);
	
	// paste mode route
	$f3->route("GET /paste/@id/@mode",
		function($f3)
		{
			global $DATA_LANGUAGES;
			database::connect();
			$result = util::getPaste($f3->get("PARAMS.id"));
			if(count($result) == 0) {
				header("location: /");
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
				$file = tempnam(NULL, "txt");
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
				header("location: /");
				exit();
			} elseif($f3->get("PARAMS.mode") and $f3->get("PARAMS.mode") == "report") {
				$logcheck = util::checkIPLogs($f3->get("IP"), "report_time");
				if(gettype($logcheck) != "boolean") {
					$f3->set("SESSION.error", "You must wait $logcheck seconds before reporting another paste.");
					header("location: /paste/" . $f3->get("PARAMS.id"));
					exit();
				}
				util::logIP($f3->get("IP"), "report_time", $f3->get("REPORT_DELAY"));
				database::query(array("UPDATE pastes SET reported = '1' WHERE access_id = ?;"), array(array(1 => $f3->get("PARAMS.id"))));
				$f3->set("SESSION.message", "The paste you specified has been reported.");
				$f3->set("SESSION.message_title", "Success");
				header("location: /paste/" . $f3->get("PARAMS.id"));
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
			$result = util::processNewPasteData($f3);
			if(gettype($result) == "array") {
				$f3->set("SESSION.error", $result[1]);
				header("location: /");
			} else {
				header("location: /paste/$result");
			}
		}
	);
	
	$f3->set("ONERROR", function($f3) {
		if(substr($f3->get("SERVER.REQUEST_URI"), 0, 5) == "/api/") {
			header("Content-Type: application/json; charset=utf-8");
			echo json_encode(array("error" => true, "error_message" => $f3->get("ERROR.status")), JSON_PRETTY_PRINT);
		} else {
			$f3->set("page_title", "Light Paste / Error");
			util::getEditorSettings($f3);
			$template = new Template;
			echo $template->render("templates/error.html");
		}
	});
	
	$f3->run();
	
?>