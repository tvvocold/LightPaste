<?php

	$f3->route("GET /api", function($f3) {
		util::getClientSettings($f3);
		$f3->set("page_title", "Light Paste / API");
		$template = new Template;
		echo $template->render("templates/api.html");
	});
	
?>