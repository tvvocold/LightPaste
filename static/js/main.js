var editor_mode = "";
var modes_loaded = new Array();

function resizeEditor()
{
	var width = $(window).width();
	var height = $(window).height();
	var header_height = $("#header").height();
	$(".CodeMirror").css("position", "absolute");
	$(".CodeMirror").css("top", header_height + "px");
	$(".CodeMirror").css("left", "0px");
	editor.setSize(width, (height - header_height));
}

function positionOptionsPanel(show_toolbar)
{
	var toolbar = $("#toolbar");
	var panel = $("#options-panel");
	var button = $("#optionspanel-toggle-button");
	var top_margin = 10;
	if(show_toolbar) {
		toolbar.show();
		toolbar.css("top", ($("#header").height() + top_margin) + "px");
		toolbar.css("right", "24px");
		top_margin = 48;
	}
	panel.css("top", ($("#header").height() + top_margin) + "px")
	button.css("top", ($("#header").height() + top_margin) + "px")
	if($(".CodeMirror-lines").height() > $(".CodeMirror").height()) {
		toolbar.css("right", "25px");
		panel.css("right", "25px");
		button.css("right", "25px");
	} else {
		toolbar.css("right", "10px");
		panel.css("right", "10px");
		button.css("right", "10px");
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

function toggleOptionsPanel()
{
	var panel = $("#options-panel");
	var button = $("#optionspanel-toggle-button");
	if(panel.is(":hidden")) {
		panel.show();
		button.hide();
	} else {
		panel.hide();
		button.show();
	}
}