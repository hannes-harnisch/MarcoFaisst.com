<?php

require_once "Constants.php";
require_once "Database.php";
require_once "Page.php";

class ArtworkPage extends Page
{
	private string $table;

	public function __construct(private string $pageArg,
								private string $uriArg)
	{
		$this->table = matchPageToTable($pageArg);
	}

	public function renderTitle() : string
	{
		return match($this->table)
		{
			PAINTING_TABLE		=> Database::getPaintingTitle($this->uriArg),
			ILLUSTRATION_TABLE	=> Database::getIllustrationTitle($this->uriArg),
			DRAWING_TABLE		=> Database::getDrawingTitle($this->uriArg)
		};
	}

	public function renderBody() : string
	{
		$this->ensureArtworkExists();

		$id = $this->queryId();
		$prevUri;
		$work;
		$nextUri;
		foreach($this->queryArtwork($id) as $row)
		{
		 	if($row["id"] < $id)
		 		$nextUri = $row["uri"];
		 	if($row["id"] == $id)
		 		$work = $row;
		 	if($row["id"] > $id)
		 		$prevUri = $row["uri"];
		}

		$body = "";
		$this->renderNavigators($body, $prevUri, $nextUri);
		$this->renderArtwork($body, $work);
		return $body;
	}

	private function renderNavigators(string &$body, ?string &$prevUri, ?string &$nextUri)
	{
		$body .=
<<<HTML
				<a class='navigator-icon navigator-close' href='/$this->pageArg'>
		 			<i class='fa fa-close'></i>
				 </a>
HTML;
		
		if(isset($prevUri))
			$body .=
<<<HTML
				<a class='navigator-icon navigator-left' href='/$this->pageArg/$prevUri'>
		 			<i class='fa fa-chevron-left'></i>
				</a>
HTML;
		
		if(isset($nextUri))
			$body .=
<<<HTML
				<a class='navigator-icon navigator-right' href='/$this->pageArg/$nextUri'>
		 			<i class='fa fa-chevron-right'></i>
				 </a>
HTML;
		$body .= "<div class='navigator-row'><span>";

		if(isset($prevUri))
			$body .=
<<<HTML
					<a href='/$this->pageArg/$prevUri'>
						<i class='fa fa-chevron-left'></i>
					</a>
HTML;

		$body .=
<<<HTML
					</span><span class='centered-span'>
						<a href='/$this->pageArg'>
							<i class='fa fa-close'></i>
						</a>
					</span><span>
HTML;
		
		if(isset($nextUri))
			 $body .=
<<<HTML
					<a href='/$this->pageArg/$nextUri'>
		 				<i class='fa fa-chevron-right'></i>
					</a>
HTML;

		$body .= "</span></div>";
	}
	
	private function renderArtwork(string &$body, array &$work)
	{
		$title = $work["title"];
		$year = $work["year"];
		$medium = $work["medium"];
		$base = $work["base"] ?? null;
		$uri = $work["uri"];
		$height = $work["height"];
		$width = $work["width"];

		$body .=
<<<HTML
					<div class='row'><div class='artwork'>
		 				<a id='modal-link' href='#' data-toggle='modal' data-target='#work-display-modal'>
		 					<img src='/Assets/$this->table/$uri.jpg' alt='$title'>
		 				</a>
		 			</div></div>
		 			<div class='row'><div class='artwork'>
		 				<div class='artwork-info'>
							<strong class='artwork-title'>$title</strong>$year, $medium
HTML;
		 
		if(isset($base))
			$body .= " auf $base";

		if($height != 0 && $width != 0)
		{
			$height = str_replace(".", ",", $height);
			$width = str_replace(".", ",", $width);
			$body .= ", $height × $width cm";
		}

		$body .=
<<<HTML
					</div></div></div>
					<div class='modal fade' id='work-display-modal'>
		 				<div class='modal-dialog'>
		 					<img src='/Assets/$this->table/$uri.jpg' alt='$title'>
						</div>
					</div>
HTML;
	}

	private function ensureArtworkExists()
	{
		$artworkExists = match($this->table)
		{
			PAINTING_TABLE		=> Database::paintingExists($this->uriArg),
			ILLUSTRATION_TABLE	=> Database::illustrationExists($this->uriArg),
			DRAWING_TABLE		=> Database::drawingExists($this->uriArg)
		};
		if(!$artworkExists)
			throw new Exception();
	}

	private function queryId() : int
	{
		return match($this->table)
		{
			PAINTING_TABLE		=> Database::getPaintingId($this->uriArg),
			ILLUSTRATION_TABLE	=> Database::getIllustrationId($this->uriArg),
			DRAWING_TABLE		=> Database::getDrawingId($this->uriArg)
		};
	}

	private function queryArtwork(int $id) : array
	{
		return match($this->table)
		{
			PAINTING_TABLE		=> $this->queryPaintingByYear($id),
			ILLUSTRATION_TABLE	=> Database::getIllustrationWithNeighbors($id),
			DRAWING_TABLE		=> Database::getDrawingWithNeighbors($id)
		};
	}

	private function queryPaintingByYear(int $id) : array
	{
		$years = explode(YEAR_GROUP_SEPARATOR, $this->pageArg);
		return Database::getPaintingWithConstrainedNeighbors($id, $years[0], $years[1]);
	}
}

?>