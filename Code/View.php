<?php

require_once "Constants.php";
require_once "Database.php";

abstract class View
{
	abstract public function renderTitle() : string;
	abstract public function renderBody() : string;

	public static function relatesToGallery(string $page) : bool
	{
		return in_array($page, YEAR_GROUPS) || in_array($page, NAVBAR_GALLERIES);
	}

	protected static function matchViewToTable(string $page) : string
	{
		return match($page)
		{
			default				=> Database::PAINTING_TABLE,
			ILLUSTRATION_VIEW	=> Database::ILLUSTRATION_TABLE,
			DRAWING_VIEW		=> Database::DRAWING_TABLE
		};
	}
}

?>