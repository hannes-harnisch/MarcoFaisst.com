<?php

require_once "ArtworkView.php";
require_once "Constants.php";
require_once "DocumentView.php";
require_once "GalleryView.php";

set_error_handler(function($errno, $errstr, $errfile, $errline)
{
	throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});

$page = $_GET["page"] ?? null;
$artId = $_GET["artId"] ?? null;

$view;
if(empty($_GET))
	$view = new DocumentView(HOME_VIEW);
elseif(View::relatesToGallery($page) && !empty($artId))
	$view = new ArtworkView($page, $artId);
elseif(View::relatesToGallery($page))
	$view = new GalleryView($page);
else
	$view = new DocumentView($page);

$title; $body;
try
{
	$title = $view->renderTitle();
	$body = $view->renderBody();
}
catch(Exception $e)
{
	$view = new DocumentView(ERROR_VIEW);
	$title = $view->renderTitle();
	$body = $view->renderBody();
}

if(empty($_GET))
	$title = "";
else
	$title .= " | ";

?>
<!DOCTYPE html>
<html lang='de'>
<head>
	<script async src='https://www.googletagmanager.com/gtag/js?id=UA-118651392-1'></script>
	<script src='/Code/JS/GoogleAnalytics.js'></script>
	<meta charset='UTF-8'/>
	<meta name='description' content='Freischaffender Maler und Grafiker.
									  Ansässig in Stuttgart, geboren und aufgewachsen in der Schweiz.'/>
	<meta name='keywords' content='art,contemporary art,gemälde,painting,stuttgart,stuttgart art,kunst,malerei,grafik,
								   graphik,grafiken,graphiken,drawing,drawings,maler,grafiker,graphiker,painter,
								   künstler,artist,paint,bild,picture,image,marco,faisst,ausstellungen,ausstellung,
								   freischaffend,freischaffender,freelancing,freelancer'/>
	<meta name='viewport' content='width=device-width,initial-scale=1'/>
	<meta name='author' content='Marco Faisst'/>
	<meta name='google-site-verification' content='BldZPYzJEc-4iJ2R1_VWJ7rPXpsgQaCQ3GDoHA1m9C0'/>
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'/>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css'/>
	<link rel='stylesheet' href='/Docs/Style.css'/>
	<link rel='shortcut icon' href='/Assets/favicon.ico'/>
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js'></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js'></script>
	<script src='https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js'></script>
	<title>

		<?php
		echo $title;
		?>

		Marco Faisst | Freischaffender Maler und Grafiker
	</title>
</head>
<body>
	<nav class='navbar navbar-light navbar-expand-md sticky-top'>
		<div class='container'>
			<a class='navbar-brand' href='/'>MARCO <strong>FAISST</strong></a>
			<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#collapsibleNavbar'>
				<span class='navbar-toggler-icon'></span>
			</button>
			<div id='collapsibleNavbar' class='collapse navbar-collapse'>
				<ul class='navbar-nav ml-auto'>
					<li class='nav-item dropdown'>
						<a id='navbardrop' class='nav-link dropdown-toggle' href='#' data-toggle='dropdown'>GEMÄLDE</a>
						<div class='dropdown-menu'>

							<?php
							foreach(YEAR_GROUPS as $yearGroup)
								echo "<a class='dropdown-item' href='/$yearGroup'>$yearGroup</a>";
							?>

						</div>
					</li>

					<?php
					foreach(NAVBAR_LINKS as $navbarLink)
					{
						echo "<span class='separator'>|</span><li class='nav-item'><a ";
						if($page == $navbarLink)
							echo "id='active-link' ";

						$linkText = strtoupper($navbarLink);
						echo "class='nav-link' href='/$navbarLink'>$linkText</a></li>";
					}
					?>

				</ul>
			</div>
		</div>
	</nav>
	<div id='main-content' class='container'>

		<?php
		echo $body;
		?>

	</div>
	<footer>
		<div class='container'>
			<span>© 2020 Marco Faisst</span>
			<span id='impressum-link'>
				<a href='/impressum'>Impressum</a>
			</span>
		</div>
	</footer>
</body>
</html>