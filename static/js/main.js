var editor = null;
var editor_mode = "";
var prev_selected_line = -1;
var site_static = "";
var site_theme = "";

/* ==================================================
	begin functions
================================================== */

function adjustEditor()
{
	var width = $(window).width();
	var height = $(window).height();
	var header_height = $("#header").height();
	var side_panel_width = $("#side-panel").width();
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

function adjustEditorFullscreenInfobox()
{
	var panel = $("#editor-fullscreen-infobox");
	panel.css("top", "10px");
	if($(".CodeMirror-lines").height() > $(".CodeMirror-scroll").height()) {
		panel.css("right", "26px");
	} else {
		panel.css("right", "10px");
	}
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

function toggleLanguage(mode, mode_complex)
{
	if(mode_complex) {
		editor.setOption("mode", mode_complex);
	} else {
		editor.setOption("mode", mode);
	}
	editor_mode = mode;
}

function slidePanel(panel_id, button_id, func)
{
	var panel = $("#" + panel_id);
	var button = $("#" + button_id);
	if(panel.is(":visible")) {
		button.html("<img src=\""  + site_static + "/themes/" + site_theme + "/images/expand.png\" alt=\"Expand icon\">");
	} else {
		button.html("<img src=\"" + site_static + "/themes/" + site_theme + "/images/collapse.png\" alt=\"Collapse icon\">");
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

function toggleToolTip(tooltip_id, object_id)
{
	var tooltip = $("#" + tooltip_id);
	var tooltip_width = tooltip.width();
	var tooltip_height = tooltip.height();
	var object = $("#" + object_id);
	var object_width = object.width();
	var object_height = object.height();
	var object_position = object.offset();
	if(tooltip.is(":hidden")) {
		tooltip.css("top", ((object_position.top - tooltip_height) - 5) + "px");
		tooltip.css("left", ((object_position.left + (object_width/2)) - (tooltip_width/2)) + "px");
		tooltip.show();
		if(tooltip.offset().left < 0) {
			tooltip.css("left", "5px");
		}
	} else {
		tooltip.hide();
	}
}

function toggleFullscreenEditor()
{
	if(!editor.getOption("fullScreen")) {
		editor.setOption("fullScreen", true);
		editor.focus();
		$("#editor-fullscreen-infobox").show();
		$("#fullscreen_checkbox").prop("checked", true);
		adjustEditorFullscreenInfobox();
	} else {
		editor.setOption("fullScreen", false);
		editor.focus();
		$("#editor-fullscreen-infobox").hide();
		$("#fullscreen_checkbox").prop("checked", false);
		adjustEditor();
	}
}

/* ==================================================
	begin page operations
================================================== */

$(document).ready(function() {
	$("<img/>")[0].src = site_static + "/themes/" + site_theme + "/images/expand.png";
	$("<img/>")[0].src = site_static + "/themes/" + site_theme + "/images/collapse.png";
	$("#languages").change(function() {
		if($(this).find(":selected").data("modecomplex")) {
			toggleLanguage($(this).find(":selected").data("mode"), $(this).find(":selected").data("modecomplex"));
		} else {
			toggleLanguage($(this).find(":selected").data("mode"));
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
	$("#matchtags_checkbox").change(function() {
		if($(this).is(":checked")) {
			editor.setOption("matchTags", {bothTags: true});
			setCookie("editor_match_tags", "1", "Mon, 1 Jan 2040 08:00:00 UTC");
		} else {
			editor.setOption("matchTags", false);
			setCookie("editor_match_tags", "0", "Mon, 1 Jan 2040 08:00:00 UTC");
		}
	});
	$("#highlight_active_line_checkbox").change(function() {
		if($(this).is(":checked")) {
			editor.setOption("styleActiveLine", true);
			setCookie("editor_highlight_active_line", "1", "Mon, 1 Jan 2040 08:00:00 UTC");
		} else {
			editor.setOption("styleActiveLine", false);
			setCookie("editor_highlight_active_line", "0", "Mon, 1 Jan 2040 08:00:00 UTC");
		}
	});
	$("#highlight_occurrences_checkbox").change(function() {
		if($(this).is(":checked")) {
			editor.setOption("highlightSelectionMatches", {showToken: /\w/});
			setCookie("editor_highlight_occurrences", "1", "Mon, 1 Jan 2040 08:00:00 UTC");
		} else {
			editor.setOption("highlightSelectionMatches", false);
			setCookie("editor_highlight_occurrences", "0", "Mon, 1 Jan 2040 08:00:00 UTC");
		}
	});
	$("#vertical_ruler_checkbox").change(function() {
		if($(this).is(":checked")) {
			editor.setOption("rulers", [{column: 90, className: "editor-ruler"}]);
			setCookie("editor_vertical_ruler", "1", "Mon, 1 Jan 2040 08:00:00 UTC");
		} else {
			editor.setOption("rulers", false);
			setCookie("editor_vertical_ruler", "0", "Mon, 1 Jan 2040 08:00:00 UTC");
		}
	});
	$("#fullscreen_checkbox").change(function() {
		toggleFullscreenEditor();
	});
	$("#editor_fontselector").change(function() {
		var font = $(this).find(":selected").text();
		$(".CodeMirror").css("font-family", font);
		editor.refresh();
		setCookie("editor_font", font, "Mon, 1 Jan 2040 08:00:00 UTC");
	});
	$("#theme-selector").change(function() {
		var theme = $(this).find(":selected").val();
		site_theme = theme;
		setCookie("site_theme", theme, "Mon, 1 Jan 2040 08:00:00 UTC");
		location.reload();
	});
	if(editor != null) {
		adjustSidePanel();
		adjustEditor();
		editor.on("change", function() {
			adjustSidePanel();
			if(editor.getOption("fullScreen")) {
				adjustEditorFullscreenInfobox();
			}
		});
		$(window).resize(function() {
			adjustSidePanel();
			if(!editor.getOption("fullScreen")) {
				adjustEditor();
			}
		});
		// check url for selected line
		var hash = window.location.hash
		if(hash.search("line-") != -1) {
			var line = parseInt(hash.substring(6));
			if(line != NaN) {
				if(line != 0) {
					line = line - 1;
					prev_selected_line = line;
					editor.addLineClass(line, "background", "cm-selectedline");
					editor.scrollIntoView({line: line, ch: 0}, 0);
				}
			}
		}
		// check for line selection via gutter clicks
		// only do this when the editor is in read-only mode
		if(editor.getOption("readOnly")) {
			editor.on("gutterClick", function(cm, line, gutter) {
				if(prev_selected_line != -1) {
					cm.removeLineClass(prev_selected_line, "background", "cm-selectedline");
				}
				if(prev_selected_line != line) {
					cm.addLineClass(line, "background", "cm-selectedline");
					prev_selected_line = line;
					window.location.hash = "#line-" + (line + 1);
				} else {
					prev_selected_line = -1;
					window.location.hash = "";
				}
			});
		}
	}
	$(".tooltip-object").on("mouseenter", function(obj) {
		var newobj = $("#" + obj.currentTarget.id);
		toggleToolTip(newobj.data("tooltip"), newobj.attr("id"));
		console.log(newobj.data("tooltip"));
	}).on("mouseleave", function(obj) {
		var newobj = $("#" + obj.currentTarget.id);
		toggleToolTip(newobj.data("tooltip"), newobj.attr("id"));
	});
	var error_panel = $("#error-panel");
	if(error_panel.length > 0) {
		toggleCenterPanel("error-panel");
	}
	var message_panel = $("#message-panel");
	if(message_panel.length > 0) {
		toggleCenterPanel("message-panel");
	}
});