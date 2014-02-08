<?php

	$DATA_LANGUAGES = array(
		"c" => array(
			"name" => "C",
			"mode" => "text/x-csrc",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"c++" => array(
			"name" => "C++",
			"mode" => "text/x-c++src",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"c#" => array(
			"name" => "C#",
			"mode" => "text/x-csharp",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"php" => array(
			"name" => "PHP",
			"mode" => "application/x-httpd-php",
			"mode_js_files" => array(
				"php/php.js",
				"hmtlmixed/htmlmixed.js",
				"xml/xml.js",
				"javascript/javascript.js",
				"css/css.js",
				"clike/clike.js"
			)
		),
		"lua" => array(
			"name" => "Lua",
			"mode" => "lua",
			"mode_js_files" => array(
				"lua/lua.js"
			)
		),
		"javascript" => array(
			"name" => "JavaScript",
			"mode" => "javascript",
			"mode_js_files" => array(
				"javascript/javascript.js"
			)
		),
		"java" => array(
			"name" => "Java",
			"mode" => "text/x-java",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"ruby" => array(
			"name" => "Ruby",
			"mode" => "text/x-ruby",
			"mode_js_files" => array(
				"ruby/ruby.js"
			)
		),
		"html" => array(
			"name" => "HTML",
			"mode" => "html",
			"mode_complex" => json_encode(array("name" => "xml", "htmlMode" => true)),
			"mode_js_files" => array(
				"xml/xml.js"
			)
		),
		"python" => array(
			"name" => "Python",
			"mode" => "",
			"mode_complex" => json_encode(array("name" => "python", "version" => 2, "singleLineStringErrors" => false)),
			"mode_js_files" => array(
				"python/python.js"
			)
		),
		"css" => array(
			"name" => "CSS",
			"mode" => "text/css",
			"mode_js_files" => array(
				"css/css.js"
			)
		),
		"scss" => array(
			"name" => "SCSS",
			"mode" => "text/x-scss",
			"mode_js_files" => array(
				"css/css.js"
			)
		),
		"less" => array(
			"name" => "LESS",
			"mode" => "text/x-less",
			"mode_js_files" => array(
				"css/css.js"
			)
		),
		"SQL" => array(
			"name" => "SQL",
			"mode" => "text/x-sql",
			"mode_js_files" => array(
				"sql/sql.js"
			)
		),
		"MySQL" => array(
			"name" => "MySQL",
			"mode" => "text/x-mysql",
			"mode_js_files" => array(
				"sql/sql.js"
			)
		)
	);

?>