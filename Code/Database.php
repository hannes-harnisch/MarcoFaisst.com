<?php

require_once "../Private/DatabaseCredentials.php";

class Database
{
	public const PAINTING_TABLE		= "painting";
	public const ILLUSTRATION_TABLE	= "illustration";
	public const DRAWING_TABLE		= "drawing";
	public const MIN_YEAR			= 0;
	public const MAX_YEAR			= 9999;

	private const DB				= "marcofaisst_com";
	private const ARTWORK_TABLES	=
	[
		self::PAINTING_TABLE,
		self::ILLUSTRATION_TABLE,
		self::DRAWING_TABLE
	];

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

	public static function getIdsAndTitles(string &$table, int $fromYear, int $toYear) : array
	{
		static $queries;
		if($queries === null)
		{
			$sql = "SELECT id, title FROM ".self::DB.".%s WHERE year BETWEEN ? AND ? ORDER BY ordinal DESC";
			self::prepareQueriesPerArtworkTable($queries, $sql);
		}

		$query = $queries[$table];
		$query->execute([$fromYear, $toYear]);
		return $query->fetchAll();
	}

	public static function getTitle(string &$table, string &$id) : string
	{
		static $queries;
		if($queries === null)
		{
			$sql = "SELECT title FROM ".self::DB.".%s WHERE id = ?";
			self::prepareQueriesPerArtworkTable($queries, $sql);
		}

		$query = $queries[$table];		
		$query->execute([$id]);
		return $query->fetch()["title"];
	}

	public static function artworkExists(string &$table, string &$id) : bool
	{
		static $queries;
		if($queries === null)
		{
			$sql = "SELECT COUNT(*) FROM ".self::DB.".%s WHERE id = ?";
			self::prepareQueriesPerArtworkTable($queries, $sql);
		}

		$query = $queries[$table];
		$query->execute([$id]);
		return $query->rowCount() > 0;
	}

	public static function getOrdinal(string &$table, string &$id) : int
	{
		static $queries;
		if($queries === null)
		{
			$sql = "SELECT ordinal FROM ".self::DB.".%s WHERE id = ?";
			self::prepareQueriesPerArtworkTable($queries, $sql);
		}

		$query = $queries[$table];		
		$query->execute([$id]);
		return $query->fetch()["ordinal"];
	}

	public static function getArtworkWithNeighbors(string &$table, int $ordinal, int $fromYear, int $toYear) : array
	{
		static $queries;
		if($queries === null)
		{
			$sql = "(SELECT * FROM ".self::DB.".%s
					WHERE ordinal < ? AND year BETWEEN ? AND ? ORDER BY ordinal DESC LIMIT 1) UNION
					(SELECT * FROM ".self::DB.".%s
					WHERE ordinal >= ? AND year BETWEEN ? AND ? ORDER BY ordinal ASC LIMIT 2)";
			self::prepareQueriesPerArtworkTable($queries, $sql);
		}
		
		$query = $queries[$table];		
		$query->execute([$ordinal, $fromYear, $toYear, $ordinal, $fromYear, $toYear]);
		return $query->fetchAll();
	}

	private static function prepareQueriesPerArtworkTable(?array &$queries, string &$sql)
	{
		foreach(self::ARTWORK_TABLES as $table)
			$queries[$table] = self::$pdo->prepare(sprintf($sql, $table, $table));
	}
}

Database::initialize();

?>