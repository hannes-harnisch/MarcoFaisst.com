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
		$this->renderDesktopNavigators($body, $prevId, $nextId);
		$this->renderMobileNavigators($body, $prevId, $nextId);

		$body .= $this->getImage($artwork["id"]);
		$this->renderArtworkInfo($body, $artwork);
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

	private function renderDesktopNavigators(string &$body, ?string &$prevId, ?string &$nextId)
	{
		if($prevId !== null)
			$body .= $this->getLeftDesktopNavigator($prevId);

		$body .= $this->getDesktopReturnNavigator();

		if($nextId !== null)
			$body .= $this->getRightDesktopNavigator($nextId);
	}

	private function getLeftDesktopNavigator(string &$prevId) : string
	{
		return <<<HTML
					<a class='navigator-icon navigator-left' href='/$this->page/$prevId'>
						<i class='fa fa-chevron-left'></i>
					</a>
HTML;
	}

	private function getDesktopReturnNavigator() : string
	{
		return <<<HTML
					<a class='navigator-icon navigator-return' href='/$this->page'>
						<i class='fa fa-close'></i>
					</a>
HTML;
	}

	private function getRightDesktopNavigator(string &$nextId) : string
	{
		return <<<HTML
					<a class='navigator-icon navigator-right' href='/$this->page/$nextId'>
						<i class='fa fa-chevron-right'></i>
					</a>
HTML;
	}

	private function renderMobileNavigators(string &$body, ?string &$prevId, ?string &$nextId)
	{
		$body .= "<div class='navigator-row'>";

		if($prevId !== null)
			$body .= $this->getLeftMobileNavigator($prevId);

		$body .= $this->getMobileReturnNavigator();

		if($nextId !== null)
			$body .= $this->getRightMobileNavigator($nextId);

		$body .= "</div>";
	}

	private function getLeftMobileNavigator(string &$prevId) : string
	{
		return <<<HTML
					<span>
						<a href='/$this->page/$prevId'>
							<i class='fa fa-chevron-left'></i>
						</a>
					</span>
HTML;
	}

	private function getMobileReturnNavigator() : string
	{
		return <<<HTML
					<span class='centered-span'>
						<a href='/$this->page'>
							<i class='fa fa-close'></i>
						</a>
					</span>
HTML;
	}

	private function getRightMobileNavigator(string &$nextId) : string
	{
		return <<<HTML
					<span>
						<a href='/$this->page/$nextId'>
							<i class='fa fa-chevron-right'></i>
						</a>
					</span>
HTML;
	}

	private function getImage(string &$id) : string
	{
		return <<<HTML
					<div class='row'>
						<div class='artwork'>
							<a id='modal-link' href='#' data-toggle='modal' data-target='#artwork-modal'>
								<img src='/Assets/$this->table/$id.jpg' alt='$id'>
							</a>
						</div>
					</div>
					<div id='artwork-modal' class='modal fade'>
						<div class='modal-dialog'>
							<img src='/Assets/$this->table/$id.jpg' alt='$id'>
						</div>
					</div>
HTML;
	}

	private function renderArtworkInfo(string &$body, array &$artwork)
	{
		$title = $artwork["title"] ?? UNTITLED;
		$year = $artwork["year"];
		$medium = $artwork["medium"];

		$body .=
<<<HTML
				<div class='row'>
					<div class='artwork'>
						<div class='artwork-info'>
							<strong class='artwork-title'>$title</strong>
							<br id='artwork-info-linebreak'>
							<div id='artwork-misc-info' class='float-right'>$year, $medium
HTML;

		$this->renderBaseAndSize($body, $artwork);
		$body .= "</div></div></div></div>";
	}

	private function renderBaseAndSize(string &$body, array &$artwork)
	{
		$base = $artwork["base"] ?? null;
		$height = $artwork["height"];
		$width = $artwork["width"];

		if($base !== null)
			$body .= " auf $base";

		if($height != 0 && $width != 0)
		{
			$height = str_replace(".", ",", $height);
			$width = str_replace(".", ",", $width);
			$body .= ", $height × $width cm";
		}
	}
}

?>