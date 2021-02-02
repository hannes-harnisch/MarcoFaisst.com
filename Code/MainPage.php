<?php

class MainPage extends Page
{
	public function getTitle() : string
	{
		return "";
	}

	public function getBody() : string
	{
		return file_get_contents("../Docs/Start.htm");
	}
}

?>