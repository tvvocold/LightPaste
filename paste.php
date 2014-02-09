<?php

	require("core.php");
	
	if(isset($_POST["code"])) {
		$language = "";
		if(isset($_POST["language"])) {
			if(array_key_exists($_POST["language"], $DATA_LANGUAGES)) {
				$language = $_POST["language"];
			}
		}
		if(trim($_POST["code"]) == "") {
			$_SESSION["lightpaste_error"] = "No paste data specified.";
			header("location: .");
			exit();
		}
		if(isset($_POST["private"])) {
			$private = 1;
		} else {
			$private = 0;
		}
		$result = db::insertPaste($_POST["code"], $language, $private);
		if(gettype($result) == "string") {
			header("location: .?id=$result");
		} else {
			header("location: .");
		}
	}
	
?>