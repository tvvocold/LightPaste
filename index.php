<?php

	require("core.php");
	
	$language_mode = "none";
	$paste_code = "";
	
	if(isset($_GET["id"])) {
		$paste_data = db::getPaste($_GET["id"]);
		if(gettype($paste_data) == "array") {
			$paste_code = htmlspecialchars($paste_data["code"]);
			$paste_language = $paste_data["language"];
			$paste_time = date("M d, Y", $paste_data["time"]);
			$paste_md5 = $paste_data["md5"];
			$paste_sha1 = $paste_data["sha1"];
			if($paste_language != "") {
				$language_data = $DATA_LANGUAGES[$paste_language];
				$language_name = $language_data["name"];
				$language_files = $language_data["mode_js_files"];
				$language_mode = $language_data["mode"];
			} else {
				$language_name = "None";
			}
		}
	}
	
?>

<!doctype html>
<html>
	<head>
		<title>Light Paste</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="StyleSheet" href="static/css/main.css" />
		<link rel="StyleSheet" href="static/css/editor.css" />
		<link rel="StyleSheet" href="static/js/codemirror-3.20/lib/codemirror.css" />
		<link rel="StyleSheet" href="static/js/codemirror-3.20/theme/ambiance.css" />
		<script type="text/javascript" src="static/js/codemirror-3.20/lib/codemirror.js"></script>
		<script type="text/javascript" src="static/js/codemirror-3.20/addon/edit/matchbrackets.js"></script>
		<?php if(isset($language_files)) {
			foreach($language_files as $value) {
		?>
		<script type="text/javascript" src="static/js/codemirror-3.20/mode/<?php echo $value; ?>"></script>
		<?php } } ?>
		<script type="text/javascript" src="static/js/main.js"></script>
		<script type="text/javascript" src="static/js/jquery-1.10.2.min.js"></script>
	</head>
	<body>
		<div id="header">
			<div id="header-content">
				<a href=".">Light Paste</a>
			</div>
		</div>
		<a href="#" id="optionspanel-toggle-button" onclick="toggleOptionsPanel();">Show Menu</a>
		<div id="options-panel">
			<?php if(isset($paste_data) and $paste_data) { ?>
			<div class="options-panel-row">
				<span class="options-panel-key">Language:</span> <span class="options-panel-value"><?php echo $language_name; ?></span>
			</div>
			<div class="options-panel-row">
				<span class="options-panel-key">Posted:</span> <span class="options-panel-value"><?php echo $paste_time; ?></span>
			</div>
			<div class="options-panel-row">
				<span class="options-panel-key">MD5:</span> <span class="options-panel-value"><?php echo $paste_md5; ?></span>
			</div>
			<div class="options-panel-row">
				<span class="options-panel-key">SHA1:</span> <span class="options-panel-value"><?php echo $paste_sha1; ?></span>
			</div>
			<div class="options-panel-row" style="border-bottom: none; text-align: center;">
				<a href="#" onclick="toggleOptionsPanel();">Hide Menu</a>
			</div>
			<?php } else { ?>
			<select id="languages" name="language" form="paste_form">
				<option value="none">None</option>
				<?php
					foreach($DATA_LANGUAGES as $key=>$value) {
						$data_files = "";
						foreach($value["mode_js_files"] as $key2=>$file) {
							if($key2 != count($value["mode_js_files"]) - 1) {
								$data_files .= "$file;";
							} else {
								$data_files .= "$file";
							}
						}
						echo "<option value=\"$key\" data-files=\"$data_files\" data-mode=\"$value[mode]\">$value[name]</option>";
					}
				?>
			</select>
			<input type="submit" value="Post" form="paste_form" />
			<?php } ?>
		</div>
		<form action="paste.php" method="post" id="paste_form">
			<textarea id="code" name="code"><?php echo $paste_code; ?></textarea>
		</form>
		<?php if(isset($_SESSION["lightpaste_error"])) { ?>
		<div id="background"></div>
		<div class="panel" id="error-panel">
			<div class="panel-header">
				Error
			</div>
			<div class="panel-content">
				<?php echo $_SESSION["lightpaste_error"]; unset($_SESSION["lightpaste_error"]); ?>
			</div>
			<div class="panel-footer">
				<a href="#" class="panel-button" onclick="toggleCenterPanel('error-panel');">Close</a>
			</div>
		</div>
		<script style="text/javascript">
			$(document).ready(function() {
				toggleCenterPanel("error-panel");
			});
		</script>
		<?php } ?>
		<script type="text/javascript">
			$("#languages").change(function() {
				toggleLanguage($(this).find(":selected").data("files"), $(this).find(":selected").data("mode"));
			});
			var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
				tabMode: "indent",
				theme: "lightpaste",
				mode: "<?php echo $language_mode; ?>",
				matchBrackets: true,
				gutter: true,
				lineNumbers: true,
				readOnly: false,
				indentUnit: 4,
				/*
				extraKeys: {
					"F11": function(cm) {
						ToggleEditorFullscreen();
					},
					"Esc": function(cm) {
						ToggleEditorFullscreen();
					}
				}
				*/
			});
			editor.on("change", function() {
				positionOptionsPanel();
			});
			$(document).ready(function() { 
				resizeEditor(); 
				positionOptionsPanel(); 
			});
			$(window).resize(function() { 
				resizeEditor(); 
				positionOptionsPanel(); 
			});
		</script>
	</body>
</html>