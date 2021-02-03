<?php

require_once "../Private/DatabaseCredentials.php";

class Database
{
	private const DB = "marcofaisst_com";

	private static PDO $pdo;

	public static function initialize()
	{
		$dsn = "mysql:host=marcofaisst.com.mysql;dbname=".self::DB.";charset=utf8";
		$options = 
		[
			PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES		=> false
		];
		self::$pdo = new PDO($dsn, DatabaseCredentials::USER, DatabaseCredentials::PASSWORD, $options);
	}

	public static function getPaintingTitlesAndUrisRanged(int $from, int $to) : array
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT title, uri FROM ".self::DB.".painting
										 WHERE year BETWEEN ? AND ? ORDER BY id DESC");
		$query->execute([$from, $to]);
		return $query->fetchAll();
	}

	public static function getIllustrationTitlesAndUris() : array
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT title, uri FROM ".self::DB.".illustration ORDER BY id DESC");
		
		$query->execute();
		return $query->fetchAll();
	}

	public static function getDrawingTitlesAndUris() : array
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT title, uri FROM ".self::DB.".drawing ORDER BY id DESC");
		
		$query->execute();
		return $query->fetchAll();
	}

	public static function getPaintingTitle(string &$uri) : string
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT title FROM ".self::DB.".painting WHERE uri = ?");		
		return self::queryTitle($query, $uri);
	}

	public static function getIllustrationTitle(string &$uri) : string
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT title FROM ".self::DB.".illustration WHERE uri = ?");		
		return self::queryTitle($query, $uri);
	}

	public static function getDrawingTitle(string &$uri) : string
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT title FROM ".self::DB.".drawing WHERE uri = ?");		
		return self::queryTitle($query, $uri);
	}

	private static function queryTitle(PDOStatement &$query, string &$uri) : string
	{
		$query->execute([$uri]);
		return $query->fetch()["title"];
	}

	public static function paintingExists(string &$uri) : bool
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT COUNT(*) FROM ".self::DB.".painting WHERE uri = ?");
		return self::queryExistence($query, $uri);
	}

	public static function illustrationExists(string &$uri) : bool
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT COUNT(*) FROM ".self::DB.".illustration WHERE uri = ?");
		return self::queryExistence($query, $uri);
	}

	public static function drawingExists(string &$uri) : bool
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT COUNT(*) FROM ".self::DB.".drawing WHERE uri = ?");
		return self::queryExistence($query, $uri);
	}

	private static function queryExistence(PDOStatement &$query, string &$uri) : bool
	{
		$query->execute([$uri]);
		return $query->rowCount() > 0;
	}

	public static function getPaintingId(string &$uri) : int
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT id FROM ".self::DB.".painting WHERE uri = ?");
		return self::queryId($query, $uri);
	}

	public static function getIllustrationId(string &$uri) : int
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT id FROM ".self::DB.".illustration WHERE uri = ?");
		return self::queryId($query, $uri);
	}

	public static function getDrawingId(string &$uri) : int
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("SELECT id FROM ".self::DB.".drawing WHERE uri = ?");
		return self::queryId($query, $uri);
	}

	private static function queryId(PDOStatement &$query, string &$uri) : int
	{
		$query->execute([$uri]);
		return $query->fetch()["id"];
	}

	public static function getPaintingWithConstrainedNeighbors(int $id, int $from, int $to) : array
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("(SELECT * FROM ".self::DB.".painting WHERE id < ?
										  AND year BETWEEN ? AND ? ORDER BY id DESC LIMIT 1)
										  UNION (SELECT * FROM ".self::DB.".painting WHERE id >= ?
										  AND year BETWEEN ? AND ? LIMIT 2)");
		$query->execute([$id, $from, $to, $id, $from, $to]);
		return $query->fetchAll();
	}

	public static function getIllustrationWithNeighbors(int $id) : array
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("(SELECT * FROM ".self::DB.".illustration WHERE id < ?
										  ORDER BY id DESC LIMIT 1) UNION
										  (SELECT * FROM ".self::DB.".illustration WHERE id >= ? LIMIT 2)");
		$query->execute([$id, $id]);
		return $query->fetchAll();
	}

	public static function getDrawingWithNeighbors(int $id) : array
	{
		static $query;
		if(!isset($query))
			$query = self::$pdo->prepare("(SELECT * FROM ".self::DB.".drawing WHERE id < ?
										  ORDER BY id DESC LIMIT 1) UNION
										  (SELECT * FROM ".self::DB.".drawing WHERE id >= ? LIMIT 2)");
		$query->execute([$id, $id]);
		return $query->fetchAll();
	}
}

Database::initialize();

?>