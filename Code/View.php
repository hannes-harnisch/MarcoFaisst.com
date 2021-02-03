<?php

require_once "Constants.php";
require_once "Database.php";

abstract class View
{
	abstract public function renderTitle() : string;
	abstract public function renderBody() : string;

	protected static function matchViewToTable(string &$page) : string
	{
		return match($page)
		{
			default			=> Database::PAINTING_TABLE,
			NAVBAR_LINKS[0]	=> Database::ILLUSTRATION_TABLE,
			NAVBAR_LINKS[1]	=> Database::DRAWING_TABLE
		};
	}
}

?>