<?php

require_once "View.php";

class HomeView extends View
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