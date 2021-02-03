<?php

require_once "Page.php";

class MainPage extends Page
{
	public function renderTitle() : string
	{
		return "";
	}

	public function renderBody() : string
	{
		return file_get_contents("../Docs/Start.htm");
	}
}

?>