var editor_mode = "";
var modes_loaded = new Array();

function adjustEditor()
{
	var width = $(window).width();
	var height = $(window).height();
	var header_height = $("#header").height();
	var side_panel_width = $("#side-panel").width()
	$(".CodeMirror").css("position", "absolute");
	$(".CodeMirror").css("bottom", "0px");
	$(".CodeMirror").css("right", "0px");
	editor.setSize((width - side_panel_width), (height - header_height));
}

function adjustSidePanel()
{
	var side_panel = $("#side-panel");
	var header = $("#header");
	var height = header.height();
	side_panel.css("top", height + "px");
	side_panel.css("height", ($(window).height() - height + "px"));
}

function toggleCenterPanel(id)
{
	var panel = $("#" + id);
	var background = $("#background");
	if(panel.is(":hidden")) {
		panel.css("top", "50%");
		panel.css("left", "50%");
		panel.css("margin-top", "-" + panel.height()/2 + "px");
		panel.css("margin-left", "-" + panel.width()/2 + "px");
		panel.fadeIn("fast");
		background.fadeIn("fast");
	} else {
		panel.fadeOut("fast", function() {
			panel.hide();
		});
		background.fadeOut("fast", function() {
			background.hide();
		});
	}
}

function toggleLanguage(files, mode, mode_complex)
{
	var data = files.split(";");
	if(!modes_loaded[mode]) {
		for(i=0; i < data.length; i++) {
			if(i == (data.length - 1)) {
				$.getScript("static/js/codemirror-3.21/mode/" + data[i], function() {
					if(mode_complex) {
						editor.setOption("mode", mode_complex);
					} else {
						editor.setOption("mode", mode);
					}
					editor_mode = mode;
					modes_loaded[mode] = true;
				});
			} else {
				$.getScript("static/js/codemirror-3.21/mode/" + data[i]);
			}
		}
	} else {
		if(mode_complex) {
			editor.setOption("mode", mode_complex);
		} else {
			editor.setOption("mode", mode);
		}
		editor_mode = mode;
	}
}

function slidePanel(panel_id, button_id, func)
{
	var panel = $("#" + panel_id);
	var button = $("#" + button_id);
	if(panel.is(":visible")) {
		button.html("<img src=\"static/images/icons/expand.png\">");
	} else {
		button.html("<img src=\"static/images/icons/collapse.png\">");
	}
	panel.slideToggle({
		duration: 140,
		progress: func
	});
}

function setCookie(name, value, expiration)
{
	document.cookie = name + "=" + value + "; expires=" + expiration + "; path=/";
}

$(document).ready(function() {
	$("#languages").change(function() {
		if($(this).find(":selected").data("modecomplex")) {
			toggleLanguage($(this).find(":selected").data("files"), $(this).find(":selected").data("mode"), $(this).find(":selected").data("modecomplex"));
		} else {
			toggleLanguage($(this).find(":selected").data("files"), $(this).find(":selected").data("mode"));
		}
	});
	$("#linenumbers_checkbox").change(function() {
		if($(this).is(":checked")) {
			editor.setOption("lineNumbers", true);
			setCookie("editor_line_numbers", "1", "Mon, 1 Jan 2040 08:00:00 UTC");
		} else {
			editor.setOption("lineNumbers", false);
			setCookie("editor_line_numbers", "0", "Mon, 1 Jan 2040 08:00:00 UTC");
		}
	});
	$("#wordwrap_checkbox").change(function() {
		if($(this).is(":checked")) {
			editor.setOption("lineWrapping", true);
			setCookie("editor_line_wrapping", "1", "Mon, 1 Jan 2040 08:00:00 UTC");
		} else {
			editor.setOption("lineWrapping", false);
			setCookie("editor_line_wrapping", "0", "Mon, 1 Jan 2040 08:00:00 UTC");
		}
	});
	$("#smartindent_checkbox").change(function() {
		if($(this).is(":checked")) {
			editor.setOption("smartIndent", true);
			setCookie("editor_smart_indent", "1", "Mon, 1 Jan 2040 08:00:00 UTC");
		} else {
			editor.setOption("smartIndent", false);
			setCookie("editor_smart_indent", "0", "Mon, 1 Jan 2040 08:00:00 UTC");
		}
	});
	$("#matchbrackets_checkbox").change(function() {
		if($(this).is(":checked")) {
			editor.setOption("matchBrackets", true);
			setCookie("editor_match_brackets", "1", "Mon, 1 Jan 2040 08:00:00 UTC");
		} else {
			editor.setOption("matchBrackets", false);
			setCookie("editor_match_brackets", "0", "Mon, 1 Jan 2040 08:00:00 UTC");
		}
	});
	$("#tabsize_selector").keyup(function() {
		var tabsize = parseInt($(this).val());
		if(tabsize > 30) {
			tabsize = 30;
		} else if(tabsize < 0) {
			tabsize = 0;
		}
		editor.setOption("tabSize", tabsize);
		setCookie("editor_tab_size", tabsize, "Mon, 1 Jan 2040 08:00:00 UTC");
	});
	$("#blinkrate_editor").keyup(function() {
		var blinkrate = $(this).val();
		editor.setOption("cursorBlinkRate", parseInt(blinkrate));
		setCookie("editor_cursor_blinkrate", blinkrate, "Mon, 1 Jan 2040 08:00:00 UTC");
	});
	$("#editor_fontsize_selector").change(function() {
		var size = $(this).find(":selected").val();
		$(".CodeMirror").css("font-size", size + "px");
		editor.refresh();
		setCookie("editor_font_size", size, "Mon, 1 Jan 2040 08:00:00 UTC");
	});
	$("#editor_fontselector").change(function() {
		var font = $(this).find(":selected").text();
		$(".CodeMirror").css("font-family", font);
		editor.refresh();
		setCookie("editor_font", font, "Mon, 1 Jan 2040 08:00:00 UTC");
	});
});