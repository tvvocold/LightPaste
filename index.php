<?php

	// load f3
	$f3 = require("lib/base.php");
	$f3->set("DEBUG", 1);
	
	require("data.php");
	require("classes/util.php");
	
	$f3->route("GET /",
		function($f3)
		{
			global $DATA_LANGUAGES;
			$langs = array();
			ksort($DATA_LANGUAGES);
			foreach($DATA_LANGUAGES as $key=>$value) {
				$data_files = "";
				foreach($value["mode_js_files"] as $key2=>$file) {
					if($key2 != count($value["mode_js_files"]) - 1) {
						$data_files .= "$file;";
					} else {
						$data_files .= "$file";
					}
				}
				$data = array();
				$data["files"] = $data_files;
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
			$f3->set("SESSION.copy_text", NULL);
		}
	);
	
	$f3->route("GET /@id",
		function($f3)
		{
			global $DATA_LANGUAGES;
			$result = util::getPaste($f3->get("PARAMS.id"));
			if(count($result) == 0) {
				header("location: .");
				exit();
			}
			util::getEditorSettings($f3);
			if($result[0]["language"] != "") {
				$language_data = $DATA_LANGUAGES[$result[0]["language"]];
				$f3->set("language_website", $language_data["website"]);
				$f3->set("language_name", $language_data["name"]);
				$f3->set("editor_mode_files", $language_data["mode_js_files"]);
				if(isset($language_data["mode_complex"])) {
					$f3->set("editor_mode", $language_data["mode_complex"]);
				} else {
					$f3->set("editor_mode", "'$language_data[mode]'");
				}
			} else {
				$f3->set("language_name", "None");
				$f3->set("editor_mode", "'none'");
			}
			$f3->set("paste_time", date("M d, Y", $result[0]["time"]));
			$f3->set("paste_views", number_format($result[0]["views"]));
			$f3->set("paste_size", util::formatDataSize(strlen($result[0]["code"])));
			$f3->set("paste_md5", $result[0]["md5"]);
			$f3->set("paste_sha1", $result[0]["sha1"]);
			$f3->set("editor_readonly", "true");
			$f3->set("editor_text", $result[0]["code"]);
			$f3->set("page_title", "Light Paste");
			$f3->set("paste_id", $result[0]["access_id"]);
			$template = new Template;
			echo $template->render("templates/paste.html");
		}
	);
	
	$f3->route("GET /@id/@mode",
		function($f3)
		{
			$result = util::getPaste($f3->get("PARAMS.id"));
			if(count($result) == 0) {
				header("location: .");
				exit();
			}
			if($f3->get("PARAMS.mode") and $f3->get("PARAMS.mode") == "raw") {
				header("Content-Type: text/plain; charset=utf-8");
				echo $result[0]["code"];
				exit();
			} elseif($f3->get("PARAMS.mode") and $f3->get("PARAMS.mode") == "download") {
				$file = tempnam("tempfiles/", "txt");
				$handle = fopen($file, "w");
				fwrite($handle, $result[0]["code"]);
				fclose($handle);
				header("Content-Type: text/plain");
				header("Content-Length: " . filesize($file));
				header("Content-Disposition: attachment; filename=\"paste.txt\"");
				readfile($file);
				unlink($file);
				exit();
			} elseif($f3->get("PARAMS.mode") and $f3->get("PARAMS.mode") == "copy") {
				$f3->set("SESSION.copy_text", $result[0]["code"]);
				header("location: ../");
				exit();
			} else {
				$f3->error(404);
			}
		}
	);
	
	$f3->route("POST /new",
		function($f3)
		{
			global $DATA_LANGUAGES;
			if($f3->get("POST.code")) {
				$code = $f3->get("POST.code");
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
				$result = util::insertPaste($code, $language, $private);
				if(gettype($result) == "string") {
					header("location: ./$result");
				} else {
					header("location: .");
				}
			}
		}
	);
	
	$f3->run();
	
?>