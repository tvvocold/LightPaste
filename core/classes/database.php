<?php

	/*
		Light Paste
		Copyright (c) 2014 Kenny Shields
	*/
	
	class database
	{
		public static $connection = NULL;
		
		static function connect()
		{
			global $f3;
			$dsn = $f3->get("DATABASE_DSN");
			$user = $f3->get("DATABASE_USER");
			$pass = $f3->get("DATABASE_PASS");
			self::$connection = new DB\SQL($dsn, $user, $pass);
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
