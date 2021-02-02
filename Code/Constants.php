<?php

const YEAR_GROUP_SEPARATOR = "-";

const YEAR_GROUPS =
[
	"2019".YEAR_GROUP_SEPARATOR."2020",
	"2017".YEAR_GROUP_SEPARATOR."2018",
	"2015".YEAR_GROUP_SEPARATOR."2016",
	"2013".YEAR_GROUP_SEPARATOR."2014",
	"2010".YEAR_GROUP_SEPARATOR."2012"
];

const NAVBAR_PAGES =
[
	"grafiken",
	"zeichnungen",
	"ausstellungen",
	"vita",
	"kontakt"
];

const NAVBAR_GALLERIES =
[
	NAVBAR_PAGES[0],
	NAVBAR_PAGES[1]
];

const ILLUSTRATION_TABLE = "illustration";
const DRAWING_TABLE = "drawing";
const PAINTING_TABLE = "painting";

function matchPageToTable(string $page) : string
{
	return match($page)
	{
		NAVBAR_PAGES[0]	=> ILLUSTRATION_TABLE,
		NAVBAR_PAGES[1]	=> DRAWING_TABLE,
		default			=> PAINTING_TABLE
	};
}

?>