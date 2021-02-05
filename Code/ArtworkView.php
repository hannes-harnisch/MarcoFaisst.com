<?php

require_once "Constants.php";
require_once "Database.php";
require_once "View.php";

class ArtworkView extends View
{
	private string $table;

	public function __construct(private string $page, private string $artId)
	{
		$this->table = parent::matchViewToTable($page);
	}

	public function renderTitle() : string
	{
		return Database::getTitle($this->table, $this->artId) ?? UNTITLED;
	}

	public function renderBody() : string
	{
		$this->ensureArtworkExists();

		$prevId; $artwork; $nextId;
		$this->assignArtworkDataAndNeighborIds($prevId, $artwork, $nextId);

		$body = "";
		$this->renderNavigators($body, $prevId, $nextId);
		$this->renderArtwork($body, $artwork);
		return $body;
	}

	private function ensureArtworkExists()
	{
		$artworkExists = Database::artworkExists($this->table, $this->artId);
		if(!$artworkExists)
			throw new InvalidArgumentException();
	}

	private function assignArtworkDataAndNeighborIds(?string &$prevId, ?string &$artwork, ?string &$nextId)
	{
		$ordinal = Database::getOrdinal($this->table, $this->artId);
		$artworkAndNeighbors = $this->queryArtworkWithNeighbors($ordinal);
		foreach($artworkAndNeighbors as $row)
		{
			if($row["ordinal"] < $ordinal)
				$nextId = $row["id"];
			if($row["ordinal"] == $ordinal)
				$artwork = $row;
			if($row["ordinal"] > $ordinal)
				$prevId = $row["id"];
		}
	}

	private function queryArtworkWithNeighbors(int $ordinal) : array
	{
		$years;
		if($this->table == Database::PAINTING_TABLE)
			$years = explode(YEAR_SEPARATOR, $this->page);
		else
			$years = [Database::MIN_YEAR, Database::MAX_YEAR];

		return Database::getArtworkWithNeighbors($this->table, $ordinal, $years[0], $years[1]);
	}

	private function renderNavigators(string &$body, ?string &$prevId, ?string &$nextId)
	{
		$body .=
<<<HTML
				<a class='navigator-icon navigator-close' href='/$this->page'>
					<i class='fa fa-close'></i>
				</a>
HTML;

		if($prevId !== null)
			$body .=
<<<HTML
					<a class='navigator-icon navigator-left' href='/$this->page/$prevId'>
						<i class='fa fa-chevron-left'></i>
					</a>
HTML;

		if($nextId !== null)
			$body .=
<<<HTML
					<a class='navigator-icon navigator-right' href='/$this->page/$nextId'>
						<i class='fa fa-chevron-right'></i>
					</a>
HTML;
		$body .= "<div class='navigator-row'><span>";

		if($prevId !== null)
			$body .=
<<<HTML
					<a href='/$this->page/$prevId'>
						<i class='fa fa-chevron-left'></i>
					</a>
HTML;

		$body .=
<<<HTML
				</span><span class='centered-span'>
					<a href='/$this->page'>
						<i class='fa fa-close'></i>
					</a>
				</span><span>
HTML;

		if($nextId !== null)
			$body .=
<<<HTML
					<a href='/$this->page/$nextId'>
						<i class='fa fa-chevron-right'></i>
					</a>
HTML;

		$body .= "</span></div>";
	}

	private function renderArtwork(string &$body, array &$artwork)
	{
		$title = $artwork["title"] ?? UNTITLED;
		$year = $artwork["year"];
		$medium = $artwork["medium"];
		$base = $artwork["base"] ?? null;
		$id = $artwork["id"];
		$height = $artwork["height"];
		$width = $artwork["width"];

		$body .=
<<<HTML
				<div class='row'><div class='artwork'>
					<a id='modal-link' href='#' data-toggle='modal' data-target='#work-display-modal'>
						<img src='/Assets/$this->table/$id.jpg' alt='$title'>
					</a>
				</div></div>
				<div class='row'><div class='artwork'>
					<div class='artwork-info'>
						<strong class='artwork-title'>$title</strong><br>
						<div class='float-right'>$year, $medium
HTML;

		if($base !== null)
			$body .= " auf $base";

		if($height != 0 && $width != 0)
		{
			$height = str_replace(".", ",", $height);
			$width = str_replace(".", ",", $width);
			$body .= ", $height × $width cm";
		}

		$body .=
<<<HTML
				</div></div></div></div>
				<div id='work-display-modal' class='modal fade'>
					<div class='modal-dialog'>
						<img src='/Assets/$this->table/$id.jpg' alt='$title'>
					</div>
				</div>
HTML;
	}
}

?>