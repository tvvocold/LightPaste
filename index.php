<?php

	require("core.php");
	
	$language_mode = "none";
	$language_website = false;
	$paste_code = "";
	$read_only = "false";
	
	if(isset($_GET["id"])) {
		if(!isset($_GET["mode"])) {
			db::countView($_GET["id"]);
		}
		$paste_data = db::getPaste($_GET["id"]);
		if(gettype($paste_data) == "array") {
			if(isset($_GET["mode"]) and $_GET["mode"] == "raw") {
				header("Content-Type: text/plain; charset=utf-8");
				echo $paste_data["code"];
				exit();
			}
			$read_only = "true";
			$paste_code = htmlspecialchars($paste_data["code"]);
			$paste_language = $paste_data["language"];
			$paste_time = date("M d, Y", $paste_data["time"]);
			$paste_views = number_format($paste_data["views"]);
			$paste_md5 = $paste_data["md5"];
			$paste_sha1 = $paste_data["sha1"];
			$paste_size = util::formatDataSize(strlen($paste_code));
			if($paste_language != "") {
				$language_data = $DATA_LANGUAGES[$paste_language];
				$language_name = $language_data["name"];
				$language_website = $language_data["website"];
				$language_files = $language_data["mode_js_files"];
				$language_mode = $language_data["mode"];
				if(isset($language_data["mode_complex"])) {
					$language_mode_complex = $language_data["mode_complex"];
				}
			} else {
				$language_name = "None";
			}
			if(isset($_GET["mode"]) and $_GET["mode"] == "copy") {
				$_SESSION["lightpaste_code"] = $paste_code;
				if($language_mode != "none") {
					$_SESSION["lightpaste_mode"] = $language_mode;
					if(isset($language_files)) {
						$_SESSION["lightpaste_files"] = $language_files;
					}
					if(isset($language_mode_complex)) {
						$_SESSION["lightpaste_mode_complex"] = $language_mode_complex;
					}
				}
				header("location: .");
				exit();
			}
		}
	} else {
		if(isset($_SESSION["lightpaste_code"])) {
			$paste_code = $_SESSION["lightpaste_code"];
			unset($_SESSION["lightpaste_code"]);
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
		<link rel="StyleSheet" href="static/js/codemirror-3.21/lib/codemirror.css" />
		<script type="text/javascript" src="static/js/codemirror-3.21/lib/codemirror.js"></script>
		<script type="text/javascript" src="static/js/codemirror-3.21/addon/edit/matchbrackets.js"></script>
		<?php if(isset($language_files)) {
			foreach($language_files as $value) {
		?>
		<script type="text/javascript" src="static/js/codemirror-3.21/mode/<?php echo $value; ?>"></script>
		<?php } } ?>
		<script type="text/javascript" src="static/js/main.js"></script>
		<script type="text/javascript" src="static/js/jquery-1.10.2.min.js"></script>
	</head>
	<body>
		<div id="header">
			<div id="header-content">
				<a href=".">Light Paste</a>
				<a style="margin-left: 5px; font-size: 14px;" href="#" onclick="toggleCenterPanel('about-panel');">About</a>
			</div>
		</div>
		<?php if(isset($paste_language)) { ?>
		<div id="toolbar">
			<a href="?id=<?php echo $_GET["id"]; ?>&mode=copy"><img src="static/images/icons/silk/page_paste.png"></a>
			<a href="?id=<?php echo $_GET["id"]; ?>&mode=raw"><img src="static/images/icons/silk/page_white.png"></a>
		</div>
		<?php } ?>
		<a href="#" id="optionspanel-toggle-button" onclick="toggleOptionsPanel();">Show Menu</a>
		<div id="options-panel">
			<?php if(isset($paste_data) and $paste_data) { ?>
			<div class="options-panel-row">
				<span class="options-panel-key">Language</span>
				<?php if($language_website) { ?>
				<span class="options-panel-value"><a href="<?php echo $language_website; ?>"><?php echo $language_name; ?></a></span>
				<?php } else { ?>
				<span class="options-panel-value"><?php echo $language_name; ?></span>
				<?php } ?>
			</div>
			<div class="options-panel-row">
				<span class="options-panel-key">Posted</span> 
				<span class="options-panel-value"><?php echo $paste_time; ?></span>
			</div>
			<div class="options-panel-row">
				<span class="options-panel-key">Views</span> 
				<span class="options-panel-value"><?php echo $paste_views; ?></span>
			</div>
			<div class="options-panel-row">
				<span class="options-panel-key">Size</span> 
				<span class="options-panel-value"><?php echo $paste_size; ?></span>
			</div>
			<div class="options-panel-row">
				<span class="options-panel-key">MD5</span> 
				<span class="options-panel-value"><input type="text" readonly="readonly" value="<?php echo $paste_md5; ?>"></span>
			</div>
			<div class="options-panel-row">
				<span class="options-panel-key">SHA1</span> 
				<span class="options-panel-value"><input type="text" readonly="readonly" value="<?php echo $paste_sha1; ?>"></span>
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
							echo "<option value=\"$key\" data-files=\"$data_files\" data-mode=\"$value[mode]\" data-modecomplex=$value[mode_complex]>$value[name]</option>";
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
			<div class="panel-header" style="color: #000000;">
				Light Paste - Version <?php echo $CONFIG_VERSION; ?>
			</div>
			<div class="panel-content">
				<div style="background: #f2f2f2; padding: 5px; margin: 10px; margin-bottom: 0px; font-size: 14px;">
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
				readOnly: <?php echo $read_only; ?>,
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
				<?php if(isset($paste_language)) { echo "positionOptionsPanel(true);"; } else { echo "positionOptionsPanel();"; } ?>
			});
			$(document).ready(function() { 
				resizeEditor(); 
				<?php if(isset($paste_language)) { echo "positionOptionsPanel(true);"; } else { echo "positionOptionsPanel();"; } ?>
			});
			$(window).resize(function() { 
				resizeEditor(); 
				<?php if(isset($paste_language)) { echo "positionOptionsPanel(true);"; } else { echo "positionOptionsPanel();"; } ?>
			});
			<?php
				if(isset($_SESSION["lightpaste_mode"])) {
					$files = implode(";", $_SESSION["lightpaste_files"]);
					if(isset($_SESSION["lightpaste_mode_complex"])) {
						echo "toggleLanguage('$files', '$_SESSION[lightpaste_mode]', $_SESSION[lightpaste_mode_complex]);";
						unset($_SESSION["lightpaste_mode_complex"]);
					} else {
						echo "toggleLanguage('$files', '$_SESSION[lightpaste_mode]')";
					}
					unset($_SESSION["lightpaste_files"]);
					unset($_SESSION["lightpaste_mode"]);
				}
			?>
		</script>
	</body>
</html>