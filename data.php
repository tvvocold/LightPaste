<?php

	$DATA_LANGUAGES = array(
		"c" => array(
			"name" => "C",
			"website" => "http://en.wikipedia.org/wiki/C_%28programming_language%29",
			"file_extension" => "c",
			"mode" => "text/x-csrc",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"c++" => array(
			"name" => "C++",
			"website" => "http://isocpp.org/",
			"file_extension" => "cpp",
			"mode" => "text/x-c++src",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"c#" => array(
			"name" => "C#",
			"website" => "http://en.wikipedia.org/wiki/C_Sharp_%28programming_language%29",
			"file_extension" => "cs",
			"mode" => "text/x-csharp",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"php" => array(
			"name" => "PHP",
			"website" => "http://php.net/",
			"file_extension" => "php",
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
			"file_extension" => "lua",
			"mode" => "lua",
			"mode_js_files" => array(
				"lua/lua.js"
			)
		),
		"javascript" => array(
			"name" => "JavaScript",
			"website" => "http://en.wikipedia.org/wiki/JavaScript",
			"file_extension" => "js",
			"mode" => "javascript",
			"mode_js_files" => array(
				"javascript/javascript.js"
			)
		),
		"java" => array(
			"name" => "Java",
			"website" => "http://www.java.com/en/about/",
			"file_extension" => "java",
			"mode" => "text/x-java",
			"mode_js_files" => array(
				"clike/clike.js"
			)
		),
		"ruby" => array(
			"name" => "Ruby",
			"website" => "https://www.ruby-lang.org/",
			"file_extension" => "rb",
			"mode" => "text/x-ruby",
			"mode_js_files" => array(
				"ruby/ruby.js"
			)
		),
		"html" => array(
			"name" => "HTML",
			"website" => "http://www.w3.org/html/",
			"file_extension" => "html",
			"mode" => "html",
			"mode_complex" => json_encode(array("name" => "xml", "htmlMode" => true)),
			"mode_js_files" => array(
				"xml/xml.js"
			)
		),
		"python" => array(
			"name" => "Python",
			"website" => "http://www.python.org/",
			"file_extension" => "py",
			"mode" => "",
			"mode_complex" => json_encode(array("name" => "python", "version" => 2, "singleLineStringErrors" => false)),
			"mode_js_files" => array(
				"python/python.js"
			)
		),
		"css" => array(
			"name" => "CSS",
			"website" => "http://en.wikipedia.org/wiki/Cascading_Style_Sheets",
			"file_extension" => "css",
			"mode" => "text/css",
			"mode_js_files" => array(
				"css/css.js"
			)
		),
		"scss" => array(
			"name" => "SCSS",
			"website" => "http://sass-lang.com/",
			"file_extension" => "scss",
			"mode" => "text/x-scss",
			"mode_js_files" => array(
				"css/css.js"
			)
		),
		"less" => array(
			"name" => "LESS",
			"website" => "http://lesscss.org/",
			"file_extension" => "less",
			"mode" => "text/x-less",
			"mode_js_files" => array(
				"css/css.js"
			)
		),
		"sql" => array(
			"name" => "SQL",
			"website" => "http://en.wikipedia.org/wiki/SQL",
			"file_extension" => "sql",
			"mode" => "text/x-sql",
			"mode_js_files" => array(
				"sql/sql.js"
			)
		),
		"mysql" => array(
			"name" => "MySQL",
			"website" => "http://www.mysql.com/",
			"file_extension" => "sql",
			"mode" => "text/x-mysql",
			"mode_js_files" => array(
				"sql/sql.js"
			)
		),
		"go" => array(
			"name" => "Go",
			"website" => "http://golang.org/",
			"file_extension" => "go",
			"mode" => "text/x-go",
			"mode_js_files" => array(
				"go/go.js"
			)
		),
		"markdown" => array(
			"name" => "Markdown",
			"website" => "http://daringfireball.net/projects/markdown/",
			"file_extension" => "md",
			"mode" => "markdown",
			"mode_js_files" => array(
				"xml/xml.js",
				"markdown/markdown.js"
			)
		),
		"yaml" => array(
			"name" => "YAML",
			"website" => "http://www.yaml.org/",
			"file_extension" => "yaml",
			"mode" => "text/x-yaml",
			"mode_js_files" => array(
				"yaml/yaml.js"
			)
		),
		"coffeescript" => array(
			"name" => "CoffeeScript",
			"website" => "http://coffeescript.org/",
			"file_extension" => ".coffee",
			"mode" => "text/x-coffeescript",
			"mode_js_files" => array(
				"coffeescript/coffeescript.js"
			)
		),
		"apl" => array(
			"name" => "APL",
			"website" => "http://en.wikipedia.org/wiki/APL_%28programming_language%29",
			"file_extension" => "txt",
			"mode" => "text/apl",
			"mode_js_files" => array(
				"apl/apl.js"
			)
		),
		"clojure" => array(
			"name" => "Clojure",
			"website" => "http://clojure.org/",
			"file_extension" => "txt",
			"mode" => "text/x-clojure",
			"mode_js_files" => array(
				"clojure/clojure.js"
			)
		),
		"cobol" => array(
			"name" => "COBOL",
			"website" => "http://en.wikipedia.org/wiki/COBOL",
			"file_extension" => "txt",
			"mode" => "text/x-cobol",
			"mode_js_files" => array(
				"cobol/cobol.js"
			)
		),
		"commonlisp" => array(
			"name" => "Common Lisp",
			"website" => "http://common-lisp.net/",
			"file_extension" => "lisp",
			"mode" => "text/x-common-lisp",
			"mode_js_files" => array(
				"commonlisp/commonlisp.js"
			)
		),
		"d" => array(
			"name" => "D",
			"website" => "http://dlang.org/",
			"file_extension" => "d",
			"mode" => "text/x-d",
			"mode_js_files" => array(
				"d/d.js"
			)
		),
		"dtd" => array(
			"name" => "DTD",
			"website" => "http://en.wikipedia.org/wiki/Document_type_definition",
			"file_extension" => "dtd",
			"mode" => "",
			"mode_complex" => json_encode(array("name" => "dtd", "alignCDATA" => true)),
			"mode_js_files" => array(
				"dtd/dtd.js"
			)
		),
		"ecl" => array(
			"name" => "ECL",
			"website" => "http://hpccsystems.com/",
			"file_extension" => "ecl",
			"mode" => "text/x-ecl",
			"mode_js_files" => array(
				"ecl/ecl.js"
			)
		),
		"eiffel" => array(
			"name" => "Eiffel",
			"website" => "http://en.wikipedia.org/wiki/Eiffel_%28programming_language%29",
			"file_extension" => "txt",
			"mode" => "text/x-eiffel",
			"mode_js_files" => array(
				"eiffel/eiffel.js"
			)
		),
		"erlang" => array(
			"name" => "Erlang",
			"website" => "http://www.erlang.org/",
			"file_extension" => "erl",
			"mode" => "text/x-erlang",
			"mode_js_files" => array(
				"erlang/erlang.js"
			)
		),
		"fortran" => array(
			"name" => "Fortran",
			"website" => "http://en.wikipedia.org/wiki/Fortran",
			"file_extension" => "txt",
			"mode" => "text/x-fortran",
			"mode_js_files" => array(
				"fortran/fortran.js"
			)
		),
		"f#" => array(
			"name" => "F#",
			"website" => "http://fsharp.org/",
			"file_extension" => "fs",
			"mode" => "text/x-fsharp",
			"mode_js_files" => array(
				"mllike/mllike.js"
			)
		),
		"groovy" => array(
			"name" => "Groovy",
			"website" => "http://groovy.codehaus.org/",
			"file_extension" => "groovy",
			"mode" => "text/x-groovy",
			"mode_js_files" => array(
				"groovy/groovy.js"
			)
		),
		"haskell" => array(
			"name" => "Haskell",
			"website" => "http://www.haskell.org/",
			"file_extension" => "hs",
			"mode" => "text/x-haskell",
			"mode_js_files" => array(
				"haskell/haskell.js"
			)
		)
	);
	
	$DATA_FONTSIZES = array(
		10,
		11,
		12,
		13,
		14,
		15,
		16,
		17,
		18,
		19,
		20,
		21,
		22,
		23,
		24,
		25,
		26,
		27,
		28
	);
	
	$DATA_FONTS = array(
		"Arial" => "Arial, Arial, Helvetica, sans-serif",
		"Arial Black" => "Arial Black, Gadget, sans-serif",
		"Courier New" => "Courier New, Courier New, monospace",
		"Lucida Console" => "Lucida Console, Monaco, monospace",
		"Lucida Sans Unicode" => "Lucida Sans Unicode, Lucida Grande, sans-serif",
		"Tahoma" => "Tahoma, Geneva, sans-serif",
		"Times New Roman" => "Times New Roman, Times New Roman, Times, serif",
		"Trebuchet MS" => "Trebuchet MS, Trebuchet MS, sans-serif",
		"Verdana" => "Verdana, Verdana, Geneva, sans-serif"
	);

?>