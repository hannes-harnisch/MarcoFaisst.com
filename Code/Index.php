<?php
require_once "ArtworkPage.php";
require_once "Constants.php";
require_once "DocumentPage.php";
require_once "Gallery.php";
require_once "MainPage.php";

$pageArg = $_GET["page"] ?? null;
$uriArg = $_GET["uri"] ?? null;

$page;
if(empty($_GET))
	$page = new MainPage();
elseif(!empty($uriArg))
	$page = new ArtworkPage($pageArg, $uriArg);
elseif(in_array($pageArg, YEAR_GROUPS) || in_array($pageArg, NAVBAR_GALLERIES))
	$page = new Gallery($pageArg);
else
	$page = new DocumentPage($pageArg);
?>

<!DOCTYPE html>
<html lang='de'>
<head>
	<meta charset='UTF-8'/>
	<meta name='description' content='Freischaffender Maler und Grafiker. Ansässig in Stuttgart, geboren und aufgewachsen in der Schweiz. Aktuelles: Atelierausstellung in Stuttgart-Bad Cannstatt.'/>
	<meta name='keywords' content='art,contemporary art,gemälde,painting,stuttgart,stuttgart art,kunst,malerei,grafik,graphik,grafiken,graphiken,drawing,drawings,maler,grafiker,graphiker,painter,künstler,artist,paint,bild,picture,image,marco,faisst,ausstellungen,ausstellung,freischaffend,freischaffender,freelancing,freelancer'/>
	<meta name='viewport' content='width=device-width,initial-scale=1'/>
	<meta name='author' content='Hannes Harnisch'/>
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'/>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css'/>
	<link rel='stylesheet' href='../Docs/Style.css'/>
	<link rel='shortcut icon' href='../Assets/favicon.ico'/>
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js'></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js'></script>
	<script src='https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js'></script>
	<script async src='https://www.googletagmanager.com/gtag/js?id=UA-118651392-1'></script>
	<script src='JS/GoogleAnalytics.js'></script>
	<title>

		<?php
		echo $page->getTitle();
		if(!$page instanceof MainPage)
			echo " | ";
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
			<div class='collapse navbar-collapse' id='collapsibleNavbar'>
				<ul class='navbar-nav ml-auto'>
					<li class='nav-item dropdown'>
						<a class='nav-link dropdown-toggle' href='#' id='navbardrop' data-toggle='dropdown'>GEMÄLDE</a>
						<div class='dropdown-menu'>

							<?php
							foreach(YEAR_GROUPS as $yearGroup)
								echo "<a class='dropdown-item' href='/$yearGroup'>$yearGroup</a>";	
							?>

						</div>
					</li>

					<?php
					foreach(NAVBAR_PAGES as $navbarPage)
					{
						echo "<span class='separator'>|</span><li class='nav-item'><a ";
						
						if(strcmp($pageArg, $navbarPage) == 0)
							echo "id='active-doc' ";
						$upperCasePageName = strtoupper($navbarPage);

						echo "class='nav-link' href='/$navbarPage'>$upperCasePageName</a></li>";
					}
					?>

				</ul>
			</div>
		</div>
	</nav>
	<div id='main-content' class='container'>
		<?php
		
		echo $page->getBody();
		// set_error_handler(function($errno, $errstr, $errfile, $errline)
		// {
		// 	throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
			
		// });
		
		// try
		// {
		// 	if(in_array($pageArg, YEAR_GROUPS) && !empty($uriArg))
		// 	{
		// 		$years = explode("-", $pageArg);
				
		// 		$error_query = $pdo->prepare("SELECT COUNT(*) FROM marcofaisst_com.painting WHERE uri = ?");
		// 		$error_query->execute([$uriArg]);
		// 		if($error_query->fetchColumn() == 0)
		// 			throw new Exception();
				
		// 		$target_id_query = $pdo->prepare("SELECT id FROM marcofaisst_com.painting WHERE uri = ?");
		// 		$target_id_query->execute([$uriArg]);
		// 		$target_id = $target_id_query->fetch()["id"];
				
		// 		$query = $pdo->prepare("(SELECT * FROM marcofaisst_com.painting WHERE id < ? AND year BETWEEN ? AND ? ORDER BY id DESC LIMIT 1) UNION
		// 								(SELECT * FROM marcofaisst_com.painting WHERE id >= ? AND year BETWEEN ? AND ? LIMIT 2)");
		// 		$query->execute([$target_id, $years[0], $years[1], $target_id, $years[0], $years[1]]);
				
		// 		$prev_work_uri;
		// 		$data;
		// 		$next_work_uri;
		// 		while($row = $query->fetch())
		// 		{
		// 			if($row["id"] < $target_id)
		// 				$next_work_uri = $row["uri"];
		// 			if($row["id"] == $target_id)
		// 				$data = $row;
		// 			if($row["id"] > $target_id)
		// 				$prev_work_uri = $row["uri"];
		// 		}
				
		// 		echo "<div class='control-row'>
		// 			<span>";
				
		// 		if(isset($prev_work_uri))
		// 			echo "<a href='/{$pageArg}/$prev_work_uri'>
		// 				<i class='fa fa-chevron-left'></i>
		// 			</a>";
				
		// 		echo "</span>
		// 		<span class='centered-span'>
		// 			<a href='/{$pageArg}'>
		// 				<i class='fa fa-close'></i>
		// 			</a>
		// 		</span>
		// 		<span>";
				
		// 		if(isset($next_work_uri))
		// 			echo "<a href='/{$pageArg}/$next_work_uri'>
		// 				<i class='fa fa-chevron-right'></i>
		// 			</a>";
				
		// 		$height = str_replace(".", ",", $data["height"]);
		// 		$width = str_replace(".", ",", $data["width"]);
				
		// 		echo "</span>
		// 			</div>
		// 			<div class='row'><div class='artwork'>
		// 				<a id='modal-link' href='#' data-toggle='modal' data-target='#work-display-modal'>
		// 					<img src='/painting/{$data["uri"]}.jpg' alt='{$data["title"]}'>
		// 				</a>
		// 			</div>
		// 		</div>
		// 		<div class='row'>
		// 			<div class='artwork'>
		// 				<div class='artwork-info'>
		// 					<strong class='artwork-title'>{$data["title"]}</strong>{$data["year"]}, {$data["medium"]} auf {$data["base"]}, {$height} × {$width} cm
		// 				</div>
		// 			</div>
		// 		</div>
		// 		<div class='modal fade' id='work-display-modal'>
		// 			<div class='modal-dialog'>
		// 				<img src='/painting/{$data["uri"]}.jpg' alt='{$data["title"]}'>
		// 			</div>
		// 		</div>
		// 		<a class='control-icon control-close' href='/{$pageArg}'>
		// 			<i class='fa fa-close'></i>
		// 		</a>";
				
		// 		if(isset($prev_work_uri))
		// 			echo "<a class='control-icon control-left' href='/{$pageArg}/$prev_work_uri'>
		// 				<i class='fa fa-chevron-left'></i>
		// 			</a>";
				
		// 		if(isset($next_work_uri))
		// 			echo "<a class='control-icon control-right' href='/{$pageArg}/$next_work_uri'>
		// 				<i class='fa fa-chevron-right'></i>
		// 			</a>";
		// 	}
		// 	



		// 	elseif(in_array($pageArg, array("grafiken", "zeichnungen")) && !empty($uriArg))
		// 	{
		// 		$table;
		// 		switch($pageArg)
		// 		{
		// 			case "grafiken":
		// 				$table = "graphic"; break;
		// 			case "zeichnungen":
		// 				$table = "drawing"; break;
		// 		}
				
		// 		$error_query = $pdo->prepare("SELECT COUNT(*) FROM marcofaisst_com.$table WHERE uri = ?");
		// 		$error_query->execute([$uriArg]);
		// 		if($error_query->fetchColumn() == 0)
		// 			throw new Exception();
					
		// 		$target_id_query = $pdo->prepare("SELECT id FROM marcofaisst_com.$table WHERE uri = ?");
		// 		$target_id_query->execute([$uriArg]);
		// 		$target_id = $target_id_query->fetch()["id"];
				
		// 		$query = $pdo->prepare("(SELECT * FROM marcofaisst_com.$table WHERE ID < ? ORDER BY id DESC LIMIT 1) UNION
		// 								(SELECT * FROM marcofaisst_com.$table WHERE ID >= ? LIMIT 2)");
		// 		$query->execute([$target_id, $target_id]);
				
		// 		$prev_work_uri;
		// 		$data;
		// 		$next_work_uri;
		// 		while($row = $query->fetch())
		// 		{
		// 			if($row["id"] < $target_id)
		// 				$next_work_uri = $row["uri"];
		// 			if($row["id"] == $target_id)
		// 				$data = $row;
		// 			if($row["id"] > $target_id)
		// 				$prev_work_uri = $row["uri"];
		// 		}
				
		// 		echo "<div class='control-row'>
		// 			<span>";
				
		// 		if(isset($prev_work_uri))
		// 			echo "<a href='/{$pageArg}/$prev_work_uri'>
		// 				<i class='fa fa-chevron-left'></i>
		// 			</a>";
				
		// 		echo "</span>
		// 		<span class='centered-span'>
		// 			<a href='/{$pageArg}'>
		// 				<i class='fa fa-close'></i>
		// 			</a>
		// 		</span>
		// 		<span>";
				
		// 		if(isset($next_work_uri))
		// 			echo "<a href='/{$pageArg}/$next_work_uri'>
		// 				<i class='fa fa-chevron-right'></i>
		// 			</a>";
				
		// 		echo "</span>
		// 		</div>
		// 		<div class='row'>
		// 			<div class='artwork'>
		// 				<a id='modal-link' href='#' data-toggle='modal' data-target='#work-display-modal'>
		// 					<img src='/$table/{$data["uri"]}.jpg' alt='{$data["title"]}'>
		// 				</a>
		// 			</div>
		// 		</div>
		// 		<div class='row'>
		// 			<div class='artwork'>
		// 				<div class='artwork-info'>
		// 					<strong class='artwork-title'>{$data["title"]}</strong>{$data["year"]}, {$data["medium"]}";

		// 		if(!empty($data["base"]))
		// 			echo " auf {$data["base"]}";
							
		// 		if($data["height"] != 0 && $data["width"] != 0)
		// 		{
		// 			$height = str_replace(".", ",", $data["height"]);
		// 			$width = str_replace(".", ",", $data["width"]);
		// 			echo ", {$height} × {$width} cm";
		// 		}
					
		// 		echo "</div>
		// 			</div>
		// 		</div>
		// 		<div class='modal fade' id='work-display-modal'>
		// 			<div class='modal-dialog'>
		// 				<img src='/$table/{$data["uri"]}.jpg' alt='{$data["title"]}'>
		// 			</div>
		// 		</div>
		// 		<a class='control-icon control-close' href='/{$pageArg}'>
		// 			<i class='fa fa-close'></i>
		// 		</a>";
				
		// 		if(isset($prev_work_uri))
		// 			echo "<a class='control-icon control-left' href='/{$pageArg}/$prev_work_uri'>
		// 				<i class='fa fa-chevron-left'></i>
		// 			</a>";
				
		// 		if(isset($next_work_uri))
		// 			echo "<a class='control-icon control-right' href='/{$pageArg}/$next_work_uri'>
		// 				<i class='fa fa-chevron-right'></i>
		// 			</a>";
		// 	}
		// 	

		
		// }
		// catch(Exception $e)
		// {
		// 	require_once "../Docs/Fehler.htm";
		// }
		
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