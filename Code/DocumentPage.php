<?php

class DocumentPage extends Page
{
	private string $docTitle;

	public function __construct(string $pageArg)
	{
		$this->docTitle = ucfirst($pageArg);
	}

	public function getTitle() : string
	{
		return $this->docTitle;
	}

	public function getBody() : string
	{
		return file_get_contents("../Docs/$this->docTitle.htm");
	}
}

?>