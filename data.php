<?php

	$DATA_LANGUAGES = array(
		"c" => array(
			"name" => "C",
			"website" => "http://en.wikipedia.org/wiki/C_%28programming_language%29",
			"file_extension" => "c",
			"mode" => "text/x-csrc"
		),
		"c++" => array(
			"name" => "C++",
			"website" => "http://isocpp.org/",
			"file_extension" => "cpp",
			"mode" => "text/x-c++src"
		),
		"c#" => array(
			"name" => "C#",
			"website" => "http://en.wikipedia.org/wiki/C_Sharp_%28programming_language%29",
			"file_extension" => "cs",
			"mode" => "text/x-csharp"
		),
		"php" => array(
			"name" => "PHP",
			"website" => "http://php.net/",
			"file_extension" => "php",
			"mode" => "application/x-httpd-php"
		),
		"lua" => array(
			"name" => "Lua",
			"website" => "http://lua.org/",
			"file_extension" => "lua",
			"mode" => "lua"
		),
		"javascript" => array(
			"name" => "JavaScript",
			"website" => "http://en.wikipedia.org/wiki/JavaScript",
			"file_extension" => "js",
			"mode" => "javascript"
		),
		"java" => array(
			"name" => "Java",
			"website" => "http://www.java.com/en/about/",
			"file_extension" => "java",
			"mode" => "text/x-java"
		),
		"ruby" => array(
			"name" => "Ruby",
			"website" => "https://www.ruby-lang.org/",
			"file_extension" => "rb",
			"mode" => "text/x-ruby"
		),
		"html" => array(
			"name" => "HTML",
			"website" => "http://www.w3.org/html/",
			"file_extension" => "html",
			"mode" => "html",
			"mode_complex" => json_encode(array("name" => "xml", "htmlMode" => true))
		),
		"python" => array(
			"name" => "Python",
			"website" => "http://www.python.org/",
			"file_extension" => "py",
			"mode" => "",
			"mode_complex" => json_encode(array("name" => "python", "version" => 2, "singleLineStringErrors" => false))
		),
		"css" => array(
			"name" => "CSS",
			"website" => "http://en.wikipedia.org/wiki/Cascading_Style_Sheets",
			"file_extension" => "css",
			"mode" => "text/css"
		),
		"scss" => array(
			"name" => "SCSS",
			"website" => "http://sass-lang.com/",
			"file_extension" => "scss",
			"mode" => "text/x-scss"
		),
		"less" => array(
			"name" => "LESS",
			"website" => "http://lesscss.org/",
			"file_extension" => "less",
			"mode" => "text/x-less"
		),
		"sql" => array(
			"name" => "SQL",
			"website" => "http://en.wikipedia.org/wiki/SQL",
			"file_extension" => "sql",
			"mode" => "text/x-sql"
		),
		"mysql" => array(
			"name" => "MySQL",
			"website" => "http://www.mysql.com/",
			"file_extension" => "sql",
			"mode" => "text/x-mysql"
		),
		"go" => array(
			"name" => "Go",
			"website" => "http://golang.org/",
			"file_extension" => "go",
			"mode" => "text/x-go"
		),
		"markdown" => array(
			"name" => "Markdown",
			"website" => "http://daringfireball.net/projects/markdown/",
			"file_extension" => "md",
			"mode" => "markdown"
		),
		"yaml" => array(
			"name" => "YAML",
			"website" => "http://www.yaml.org/",
			"file_extension" => "yaml",
			"mode" => "text/x-yaml"
		),
		"coffeescript" => array(
			"name" => "CoffeeScript",
			"website" => "http://coffeescript.org/",
			"file_extension" => ".coffee",
			"mode" => "text/x-coffeescript"
		),
		"apl" => array(
			"name" => "APL",
			"website" => "http://en.wikipedia.org/wiki/APL_%28programming_language%29",
			"file_extension" => "txt",
			"mode" => "text/apl"
		),
		"clojure" => array(
			"name" => "Clojure",
			"website" => "http://clojure.org/",
			"file_extension" => "txt",
			"mode" => "text/x-clojure"
		),
		"cobol" => array(
			"name" => "COBOL",
			"website" => "http://en.wikipedia.org/wiki/COBOL",
			"file_extension" => "txt",
			"mode" => "text/x-cobol"
		),
		"commonlisp" => array(
			"name" => "Common Lisp",
			"website" => "http://common-lisp.net/",
			"file_extension" => "lisp",
			"mode" => "text/x-common-lisp"
		),
		"d" => array(
			"name" => "D",
			"website" => "http://dlang.org/",
			"file_extension" => "d",
			"mode" => "text/x-d"
		),
		"dtd" => array(
			"name" => "DTD",
			"website" => "http://en.wikipedia.org/wiki/Document_type_definition",
			"file_extension" => "dtd",
			"mode" => "",
			"mode_complex" => json_encode(array("name" => "dtd", "alignCDATA" => true))
		),
		"ecl" => array(
			"name" => "ECL",
			"website" => "http://hpccsystems.com/",
			"file_extension" => "ecl",
			"mode" => "text/x-ecl"
		),
		"eiffel" => array(
			"name" => "Eiffel",
			"website" => "http://en.wikipedia.org/wiki/Eiffel_%28programming_language%29",
			"file_extension" => "txt",
			"mode" => "text/x-eiffel"
		),
		"erlang" => array(
			"name" => "Erlang",
			"website" => "http://www.erlang.org/",
			"file_extension" => "erl",
			"mode" => "text/x-erlang"
		),
		"fortran" => array(
			"name" => "Fortran",
			"website" => "http://en.wikipedia.org/wiki/Fortran",
			"file_extension" => "txt",
			"mode" => "text/x-fortran"
		),
		"f#" => array(
			"name" => "F#",
			"website" => "http://fsharp.org/",
			"file_extension" => "fs",
			"mode" => "text/x-fsharp"
		),
		"groovy" => array(
			"name" => "Groovy",
			"website" => "http://groovy.codehaus.org/",
			"file_extension" => "groovy",
			"mode" => "text/x-groovy"
		),
		"haskell" => array(
			"name" => "Haskell",
			"website" => "http://www.haskell.org/",
			"file_extension" => "hs",
			"mode" => "text/x-haskell"
		),
		"smarty" => array(
			"name" => "Smarty",
			"website" => "http://www.smarty.net/",
			"file_extension" => "tpl",
			"mode" => "smarty"
		),
		"mirc" => array(
			"name" => "mIRC",
			"website" => "http://www.mirc.com/",
			"file_extension" => "mrc",
			"mode" => "text/mirc"
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
		"Courier New" => "Courier New, Courier New, monospace",
		"Lucida Console" => "Lucida Console, Monaco, monospace",
		"Lucida Sans Unicode" => "Lucida Sans Unicode, Lucida Grande, sans-serif",
		"Tahoma" => "Tahoma, Geneva, sans-serif",
		"Times New Roman" => "Times New Roman, Times New Roman, Times, serif",
		"Trebuchet MS" => "Trebuchet MS, Trebuchet MS, sans-serif",
		"Verdana" => "Verdana, Verdana, Geneva, sans-serif"
	);
	
	$DATA_THEMES = array(
		"default" => array(
			"name" => "Default",
			"editor_theme" => "lightpaste"
		),
		"ambiance" => array(
			"name" => "Ambiance",
			"editor_theme" => "ambiance"
		),
		"monokai" => array(
			"name" => "Monokai",
			"editor_theme" => "monokai"
		),
		"base16-dark" => array(
			"name" => "Base16-Dark",
			"editor_theme" => "base16-dark"
		)
	);

?>