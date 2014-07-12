<?php

	/*
		Light Paste
		Copyright (c) 2014 Kenny Shields
	*/
	
	$f3->route("GET /about", function($f3) {
		util::getClientSettings($f3);
		$f3->set("page_title", "Light Paste / About");
		$template = new Template;
		echo $template->render("templates/about.html");
	});
	
?>