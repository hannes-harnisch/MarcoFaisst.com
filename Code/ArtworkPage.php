<?php

require_once "Constants.php";
require_once "Database.php";
require_once "Page.php";

class ArtworkPage extends Page
{
	private string $table;

	public function __construct(private string $pageArg,
								private string $uriArg)
	{
		$this->table = matchPageToTable($pageArg);
	}

	public function getTitle() : string
	{
		return Database::getArtworkTitle($this->table, $this->uriArg);
	}

	public function getBody() : string
	{

	}
}

?>