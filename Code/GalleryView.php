<?php

require_once "Constants.php";
require_once "Database.php";
require_once "View.php";

class GalleryView extends View
{
	private string $table;

	public function __construct(private string $page)
	{
		$this->table = parent::matchViewToTable($page);
	}

	public function renderTitle() : string
	{
		return ucfirst($this->page);
	}

	public function renderBody() : string
	{
		$count = 1;
		$body = "<div class='row'>";

		foreach($this->queryArtworks() as $work)
		{
			$id = $work["id"];
			$title = $work["title"];
			$body .=
<<<HTML
				<div class='col-12 col-sm-4 artwork-grid'>
					<span class='vertical-aligner'></span>
					<a href='/$this->page/$id'>
						<img src='/Assets/{$this->table}Preview/$id.jpg' alt='$title'>
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
		$years;
		if($this->table == Database::PAINTING_TABLE)
			$years = explode(YEAR_SEPARATOR, $this->page);
		else
			$years = [Database::MIN_YEAR, Database::MAX_YEAR];

		return Database::getIdsAndTitles($this->table, $years[0], $years[1]);
	}
}

?>