<?php

const YEAR_SEPARATOR	= "-";
const YEAR_GROUPS		=
[
	"2021".YEAR_SEPARATOR."2023",
	"2019".YEAR_SEPARATOR."2020",
	"2017".YEAR_SEPARATOR."2018",
	"2015".YEAR_SEPARATOR."2016",
	"2013".YEAR_SEPARATOR."2014",
	"2010".YEAR_SEPARATOR."2012"
];

const HOME_VIEW			= "start";
const ILLUSTRATION_VIEW = "grafiken";
const DRAWING_VIEW		= "zeichnungen";
const ERROR_VIEW		= "fehler";

const NAVBAR_LINKS		=
[
	ILLUSTRATION_VIEW,
	DRAWING_VIEW,
	"ausstellungen",
	"vita",
	"kontakt"
];

const NAVBAR_GALLERIES	=
[
	ILLUSTRATION_VIEW,
	DRAWING_VIEW
];

const UNTITLED			= "ohne Titel";

const KEYWORDS			=
[
	"%%EMAIL%%"			=> "mojkag@bluemail.ch",
	"%%PHONENUMBER%%"	=> "+49 176 78420538",
	"%%ADDRESS%%"		=>
<<<HTML
	<p>
		Marco Faisst<br>
		Böblinger Straße 51<br>
		70199 Stuttgart
	</p>
HTML
];

?>