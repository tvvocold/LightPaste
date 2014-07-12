<?php
	
	/*
		Light Paste
		Copyright (c) 2014 Kenny Shields
	*/
	
	$f3->route("GET /api", function($f3) {
		global $DATA_LANGUAGES;
		global $DATA_EXPIRATIONS;
		util::getClientSettings($f3);
		$f3->set("page_title", "Light Paste / API");
		$f3->set("api_languages", $DATA_LANGUAGES);
		$f3->set("api_expirations", $DATA_EXPIRATIONS);
		$template = new Template;
		echo $template->render("templates/api.html");
	});
	
?>