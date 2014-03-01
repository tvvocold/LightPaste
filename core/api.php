<?php

	# =============================================
	#	/paste GET routes
	# =============================================
	
	// route with paste id
	$f3->route("GET /api/paste/@id", function($f3) {
		header("Content-Type: application/json; charset=utf-8");
		$id = $f3->get("PARAMS.id");
		if($id != NULL) {
			database::connect();
			$result = database::query(array("SELECT access_id, text, 
				language, time, views, md5, sha1, private 
				FROM pastes WHERE access_id = ?"),
				array(array(1 => $id))
			);
			if(gettype($result) == "array" and count($result) == 1) {
				$data = json_encode(array(
					"id" => $result[0]["access_id"],
					"text" => base64_encode($result[0]["text"]),
					"language" => $result[0]["language"],
					"time" => $result[0]["time"],
					"views" => $result[0]["views"],
					"md5" => $result[0]["md5"],
					"sha1" => $result[0]["sha1"]
				), JSON_PRETTY_PRINT);
				echo $data;
			} else {
				$f3->error(404);
			}
		} else {
			$f3->error(400);
		}
	});
	
	// route with unwanted data
	$f3->route("GET /api/paste/@id/*", function($f3) {
		$f3->error(404);
	});
	
	// route with no paste id
	$f3->route("GET /api/paste", function($f3) {
		$f3->error(400);
	});
	
	# =============================================
	#	/paste POST routes
	# =============================================
	
	// new paste route
	$f3->route("POST /api/paste", function($f3, $params) {
		header("Content-Type: application/json; charset=utf-8");
		$result = util::processNewPasteData($f3);
		if(gettype($result) == "array") {
			header("HTTP/1.1 " . $result[0]);
			echo json_encode(array(
				"error" => true,
				"error_message" => $result[1]
			), JSON_PRETTY_PRINT);
		} else {
			header("HTTP/1.1 201 Created");
			echo json_encode(array(
				"paste_id" => $result
			), JSON_PRETTY_PRINT);
		}
	});
	
?>