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

function positionOptionsPanel()
{
	var panel = $("#options-panel");
	var button = $("#optionspanel-toggle-button");
	panel.css("top", ($("#header").height() + 8) + "px")
	button.css("top", ($("#header").height() + 8) + "px")
	if($(".CodeMirror-lines").height() > $(".CodeMirror").height()) {
		panel.css("right", "24px");
		button.css("right", "24px");
	} else {
		panel.css("right", "5px");
		button.css("right", "5px");
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
				$.getScript("static/js/codemirror-3.20/mode/" + data[i], function() {
					if(mode_complex) {
						editor.setOption("mode", eval(mode_complex));
						alert(mode_complex);
					} else {
						editor.setOption("mode", mode);
					}
					editor_mode = mode;
					modes_loaded[mode] = true;
				});
			} else {
				$.getScript("static/js/codemirror-3.20/mode/" + data[i]);
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