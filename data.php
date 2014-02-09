<?php

	$DATA_LANGUAGES = array(
		"c" => array(
			"name" => "C",
			"website" => "http://en.wikipedia.org/wiki/C_%28programming_language%29",
			"mode" => "text/x-csrc",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"c++" => array(
			"name" => "C++",
			"website" => "http://isocpp.org/",
			"mode" => "text/x-c++src",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"c#" => array(
			"name" => "C#",
			"website" => "http://en.wikipedia.org/wiki/C_Sharp_%28programming_language%29",
			"mode" => "text/x-csharp",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"php" => array(
			"name" => "PHP",
			"website" => "http://php.net/",
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
			"website" => "http://lua.org/",
			"mode" => "lua",
			"mode_js_files" => array(
				"lua/lua.js"
			)
		),
		"javascript" => array(
			"name" => "JavaScript",
			"website" => "http://en.wikipedia.org/wiki/JavaScript",
			"mode" => "javascript",
			"mode_js_files" => array(
				"javascript/javascript.js"
			)
		),
		"java" => array(
			"name" => "Java",
			"website" => "http://www.java.com/en/about/",
			"mode" => "text/x-java",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"ruby" => array(
			"name" => "Ruby",
			"website" => "https://www.ruby-lang.org/",
			"mode" => "text/x-ruby",
			"mode_js_files" => array(
				"ruby/ruby.js"
			)
		),
		"html" => array(
			"name" => "HTML",
			"website" => "http://www.w3.org/html/",
			"mode" => "html",
			"mode_complex" => json_encode(array("name" => "xml", "htmlMode" => true)),
			"mode_js_files" => array(
				"xml/xml.js"
			)
		),
		"python" => array(
			"name" => "Python",
			"website" => "http://www.python.org/",
			"mode" => "",
			"mode_complex" => json_encode(array("name" => "python", "version" => 2, "singleLineStringErrors" => false)),
			"mode_js_files" => array(
				"python/python.js"
			)
		),
		"css" => array(
			"name" => "CSS",
			"website" => "http://en.wikipedia.org/wiki/Cascading_Style_Sheets",
			"mode" => "text/css",
			"mode_js_files" => array(
				"css/css.js"
			)
		),
		"scss" => array(
			"name" => "SCSS",
			"website" => "http://sass-lang.com/",
			"mode" => "text/x-scss",
			"mode_js_files" => array(
				"css/css.js"
			)
		),
		"less" => array(
			"name" => "LESS",
			"website" => "http://lesscss.org/",
			"mode" => "text/x-less",
			"mode_js_files" => array(
				"css/css.js"
			)
		),
		"SQL" => array(
			"name" => "SQL",
			"website" => "http://en.wikipedia.org/wiki/SQL",
			"mode" => "text/x-sql",
			"mode_js_files" => array(
				"sql/sql.js"
			)
		),
		"MySQL" => array(
			"name" => "MySQL",
			"website" => "http://www.mysql.com/",
			"mode" => "text/x-mysql",
			"mode_js_files" => array(
				"sql/sql.js"
			)
		),
		"go" => array(
			"name" => "Go",
			"website" => "http://golang.org/",
			"mode" => "text/x-go",
			"mode_js_files" => array(
				"go/go.js"
			)
		),
		"markdown" => array(
			"name" => "Markdown",
			"website" => "http://daringfireball.net/projects/markdown/",
			"mode" => "markdown",
			"mode_js_files" => array(
				"xml/xml.js",
				"markdown/markdown.js"
			)
		),
		"yaml" => array(
			"name" => "YAML",
			"website" => "http://www.yaml.org/",
			"mode" => "text/x-yaml",
			"mode_js_files" => array(
				"yaml/yaml.js"
			)
		),
		"coffeescript" => array(
			"name" => "CoffeeScript",
			"website" => "http://coffeescript.org/",
			"mode" => "text/x-coffeescript",
			"mode_js_files" => array(
				"coffeescript/coffeescript.js"
			)
		)
	);

?>