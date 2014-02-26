<?php

	class database
	{
		public static $connection = NULL;
		
		static function connect()
		{
			global $f3;
			$host = $f3->get("DATABASE_HOST");
			$port = $f3->get("DATABASE_PORT");
			$name = $f3->get("DATABASE_NAME");
			$user = $f3->get("DATABASE_USER");
			$pass = $f3->get("DATABASE_PASS");
			self::$connection = new DB\SQL("mysql:host=$host;port=$port;dbname=$name", $user, $pass);
		}
		
		static function query($sql, $args = NULL)
		{
			if(self::$connection != NULL) {
				return self::$connection->exec($sql, $args);
			} else {
				return false;
			}
		}
	}
	
?>