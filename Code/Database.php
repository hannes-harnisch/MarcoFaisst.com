<?php

require_once "../Private/DatabaseCredentials.php";

class Database
{
	private const HOST = "marcofaisst.com.mysql";
	private const NAME = "marcofaisst_com";

	private static PDO $pdo;

	public static function initialize()
	{
		self::$pdo = new PDO("mysql:host=".self::HOST.";dbname=".self::NAME.";charset=utf8",
							 DatabaseCredentials::USER,
							 DatabaseCredentials::PASSWORD,
				    		 [
								 PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
								 PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
								 PDO::ATTR_EMULATE_PREPARES		=> false,
							 ]);
	}

	public static function getArtworkTitle(string $table, string $uri) : string
	{
		$query = self::$pdo->prepare("SELECT title FROM ".self::NAME.".$table WHERE uri = ?");		
		$query->execute([$uri]);
		return $query->fetch()["title"];
	}

	public static function getPaintingTitlesAndUrisInYearRange(int $from, int $to) : array
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT title, uri FROM ".self::NAME.".painting
										 WHERE year BETWEEN ? AND ? ORDER BY id DESC");
		
		$query->execute([$from, $to]);
		return $query->fetchAll();
	}

	public static function getTitlesAndUris(string $table) : array
	{
		$query = self::$pdo->prepare("SELECT title, uri FROM ".self::NAME.".$table ORDER BY id DESC");
		$query->execute();
		return $query->fetchAll();
	}
}

Database::initialize();

?>