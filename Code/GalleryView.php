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

		foreach($this->queryArtworkIds() as $id)
		{
			$body .= $this->getGalleryCell($id["id"]);
			if($count % 3 == 0)
				$body .= "</div><div class='row'>";

			$count++;
		}

		return $body . "</div>";
	}

	private function queryArtworkIds() : array
	{
		$years;
		if($this->table == Database::PAINTING_TABLE)
			$years = explode(YEAR_SEPARATOR, $this->page);
		else
			$years = [Database::MIN_YEAR, Database::MAX_YEAR];

		$from = $years[0];
		if(count($years) == 1)
			$to = $years[0];
		else
			$to = $years[1];
		return Database::getIdsWithinYears($this->table, $from, $to);
	}

	private function getGalleryCell(string &$id) : string
	{
		return <<<HTML
					<div class='gallery-cell col-12 col-sm-4'>
						<span class='vertical-aligner'></span>
						<a href='/$this->page/$id'>
							<img src='/Assets/{$this->table}Preview/$id.jpg' alt='$id'>
						</a>
					</div>
HTML;
	}
}

?>