<?php

require_once "Constants.php";
require_once "View.php";

class DocumentView extends View
{
	private string $docTitle;

	public function __construct(string $page)
	{
		$this->docTitle = ucfirst($page);
	}

	public function renderTitle() : string
	{
		return $this->docTitle;
	}

	public function renderBody() : string
	{
		$body = file_get_contents("../Docs/$this->docTitle.htm");
		foreach(KEYWORDS as $keyword => $replacement)
			$body = str_replace($keyword, $replacement, $body);
		return $body;
	}
}

?>