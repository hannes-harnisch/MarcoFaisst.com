<?php

require_once "../Private/DatabaseCredentials.php";

class Database
{
	public const PAINTING_TABLE		= "Painting";
	public const ILLUSTRATION_TABLE	= "Illustration";
	public const DRAWING_TABLE		= "Drawing";
	public const MIN_YEAR			= 0;
	public const MAX_YEAR			= 9999;

	private const DSN				= "mysql:host=marcofaisst.com.mysql;
									   dbname=marcofaisst_com;
									   charset=utf8";
	private const PDO_OPTIONS		=
	[
		PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES		=> false
	];
	private const ARTWORK_TABLES	=
	[
		self::PAINTING_TABLE,
		self::ILLUSTRATION_TABLE,
		self::DRAWING_TABLE
	];

	private static PDO $pdo;

	public static function initialize()
	{
		self::$pdo = new PDO(self::DSN, DatabaseCredentials::USER, DatabaseCredentials::PASSWORD, self::PDO_OPTIONS);
	}

	public static function getIdsAndTitles(string &$table, int $fromYear, int $toYear) : array
	{
		static $queries;
		if($queries === null)
			self::prepareQueriesPerArtworkTable($queries,
<<<SQL
				SELECT id, title FROM %s WHERE year BETWEEN ? AND ? ORDER BY ordinal DESC
SQL);
		$query = $queries[$table];
		$query->execute([$fromYear, $toYear]);
		return $query->fetchAll();
	}

	public static function getTitle(string &$table, string &$id) : ?string
	{
		static $queries;
		if($queries === null)
			self::prepareQueriesPerArtworkTable($queries,
<<<SQL
				SELECT title FROM %s WHERE id = ?
SQL);
		$query = $queries[$table];
		$query->execute([$id]);
		return $query->fetch()["title"];
	}

	public static function artworkExists(string &$table, string &$id) : bool
	{
		static $queries;
		if($queries === null)
			self::prepareQueriesPerArtworkTable($queries,
<<<SQL
				SELECT COUNT(*) FROM %s WHERE id = ?
SQL);
		$query = $queries[$table];
		$query->execute([$id]);
		return $query->rowCount() > 0;
	}

	public static function getOrdinal(string &$table, string &$id) : int
	{
		static $queries;
		if($queries === null)
			self::prepareQueriesPerArtworkTable($queries,
<<<SQL
				SELECT ordinal FROM %s WHERE id = ?
SQL);
		$query = $queries[$table];
		$query->execute([$id]);
		return $query->fetch()["ordinal"];
	}

	public static function getArtworkWithNeighbors(string &$table, int $ordinal, int $fromYear, int $toYear) : array
	{
		static $queries;
		if($queries === null)
			self::prepareQueriesPerArtworkTable($queries,
<<<SQL
				(SELECT * FROM %s WHERE ordinal < ? AND year BETWEEN ? AND ? ORDER BY ordinal DESC LIMIT 1) UNION
				(SELECT * FROM %s WHERE ordinal >= ? AND year BETWEEN ? AND ? ORDER BY ordinal ASC LIMIT 2)
SQL);
		$query = $queries[$table];
		$query->execute([$ordinal, $fromYear, $toYear, $ordinal, $fromYear, $toYear]);
		return $query->fetchAll();
	}

	private static function prepareQueriesPerArtworkTable(?array &$queries, string $sql)
	{
		foreach(self::ARTWORK_TABLES as $table)
		{
			$sqlWithTable = sprintf($sql, $table, $table);
			$queries[$table] = self::$pdo->prepare($sqlWithTable);
		}
	}
}

Database::initialize();

?>