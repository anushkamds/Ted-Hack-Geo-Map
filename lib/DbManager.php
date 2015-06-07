<?php

class DbManager {
	
	static $dbConnection = null;
	
	public static getConnection() {
		if (is_null(self::$dbConnection)) {
			$host = DatabaseConfig::HOST;
			$username = DatabaseConfig::USER_NAME;
			$password = DatabaseConfig::PASSWORD;
			$dbName = DatabaseConfig::DB_NAME;
			self::$dbConnection = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
			// set the PDO error mode to exception
			self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$dbConnection;
	}
}
