<?php

require_once "Page.php";

class DocumentPage extends Page
{
	private string $docTitle;

	public function __construct(string $pageArg)
	{
		$this->docTitle = ucfirst($pageArg);
	}

	public function renderTitle() : string
	{
		return $this->docTitle;
	}

	public function renderBody() : string
	{
		return file_get_contents("../Docs/$this->docTitle.htm");
	}
}

?>