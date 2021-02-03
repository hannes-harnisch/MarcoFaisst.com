<?php

require_once "Constants.php";
require_once "Database.php";
require_once "Page.php";

class Gallery extends Page
{
	private string $table;

	public function __construct(private string $pageArg)
	{
		$this->table = matchPageToTable($pageArg);
	}

	public function renderTitle() : string
	{
		return ucfirst($this->pageArg);
	}

	public function renderBody() : string
	{
		$count = 1;
		$body = "<div class='row'>";

		foreach($this->queryArtworks() as $work)
		{
			$title = $work["title"];
			$uri = $work["uri"];
			$body .=			
<<<HTML
				<div class='col-12 col-sm-4 artwork-grid'>
					<span class='vertical-aligner'></span>
					<a href='/$this->pageArg/$uri'>
						<img src='/Assets/{$this->table}Preview/$uri.jpg' alt='$title'>
					</a>
				</div>
HTML;			
			if($count % 3 == 0)
				$body .="</div><div class='row'>";

			$count++;
		}

		return $body."</div>";
	}

	private function queryArtworks() : array
	{
		return match($this->table)
		{
			PAINTING_TABLE		=> $this->queryPaintingsByYear(),
			ILLUSTRATION_TABLE	=> Database::getIllustrationTitlesAndUris(),
			DRAWING_TABLE		=> Database::getDrawingTitlesAndUris()
		};
	}

	private function queryPaintingsByYear() : array
	{
		$years = explode(YEAR_GROUP_SEPARATOR, $this->pageArg);
		return Database::getPaintingTitlesAndUrisRanged($years[0], $years[1]);
	}
}

?>