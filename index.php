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
				if(isset($language_data["mode_complex"])) {
					$language_mode_complex = $language_data["mode_complex"];
				}
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
				<a style="position: absolute; right: 10px; bottom: 5px; font-size: 14px;" href="#" onclick="toggleCenterPanel('about-panel');">About</a>
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
						if(isset($value["mode_complex"])) {
							echo "<option value=\"$key\" data-files=\"$data_files\" data-mode=\"$value[mode]\" data-modecomplex=\"$value[mode_complex]\">$value[name]</option>";
						}  else {
							echo "<option value=\"$key\" data-files=\"$data_files\" data-mode=\"$value[mode]\">$value[name]</option>";
						}
					}
				?>
			</select>
			<input type="submit" value="Post" form="paste_form" />
			<?php } ?>
		</div>
		<form action="paste.php" method="post" id="paste_form">
			<textarea id="code" name="code"><?php echo $paste_code; ?></textarea>
		</form>
		<div id="background"></div>
		<div class="panel" id="about-panel">
			<div class="panel-header" style="background: #4D79FF;">
				Light Paste - Version <?php echo $CONFIG_VERSION; ?>
			</div>
			<div class="panel-content">
				<div style="background: #f2f2f2; padding: 5px; margin: 10px; margin-bottom: 0px;">
					<strong>zlib/libpng License</strong><br/>
					Copyright (c) 2014 Kenny Shields <br/> <br/>

					This software is provided 'as-is', without any express or implied warranty. 
					In no event will the authors be held liable for any damages arising from the use of this software.
					
					<br/> <br/>

					Permission is granted to anyone to use this software for any purpose, 
					including commercial applications, and to alter it and redistribute it freely, 
					subject to the following restrictions:
					
					<ol>
						<li>The origin of this software must not be misrepresented; 
						you must not claim that you wrote the original software. 
						If you use this software in a product, an acknowledgment in 
						the product documentation would be appreciated but is not required.</li>
						<br/>
						<li>Altered source versions must be plainly marked as such, and must 
						not be misrepresented as being the original software.</li>
						<br/>
						<li>This notice may not be removed or altered from any source distribution.</li>
					</ol>
				</div>
			</div>
			<div class="panel-footer">
				<a href="#" class="panel-button" onclick="toggleCenterPanel('about-panel');">Close</a>
			</div>
		</div>
		<?php if(isset($_SESSION["lightpaste_error"])) { ?>
		<div class="panel" id="error-panel">
			<div class="panel-header" style="background: #ff0000;">
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
				if($(this).find(":selected").data("modecomplex")) {
					toggleLanguage($(this).find(":selected").data("files"), $(this).find(":selected").data("mode"), $(this).find(":selected").data("modecomplex"));
				} else {
					toggleLanguage($(this).find(":selected").data("files"), $(this).find(":selected").data("mode"));
				}
			});
			var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
				tabMode: "indent",
				theme: "lightpaste",
				mode: <?php if(isset($language_mode_complex)) { echo $language_mode_complex; } else { echo '"' . $language_mode . '"'; } ?>,
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