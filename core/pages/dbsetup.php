<?php

	/*
		Light Paste
		Copyright (c) 2014 Kenny Shields
	*/
	
	$f3->route("GET /dbsetup", function($f3) {
		if(!$f3->get("SITE_DBSETUP_ENABLED")) {
			echo "The dbsetup script has been disabled.";
			exit();
		}
		$template = new Template;
		echo $template->render("templates/dbsetup.html");
	});
	
	$f3->route("GET /dbsetup/success", function($f3) {
		if(!$f3->get("SITE_DBSETUP_ENABLED")) {
			echo "The dbsetup script has been disabled.";
			exit();
		}
		$template = new Template;
		echo $template->render("templates/dbsetup_success.html");
	});
	
	$f3->route("POST /dbsetup", function($f3) {
		if(!$f3->get("SITE_DBSETUP_ENABLED")) {
			header("location: /dbsetup");
			exit();
		}
		database::connect();
		if(strpos($f3->get("DATABASE_DSN"), "sqlite") !== false) {
			$files = scandir("core/schemas/sqlite");
			foreach($files as $value) {
				if($value != "." and $value != "..") {
					database::query(file_get_contents("core/schemas/sqlite/$value"));
				}
			}
		} else {
			database::query(file_get_contents("core/schemas/mysql.txt"));
		}
		header("location: /dbsetup/success");
	});
	
?>
