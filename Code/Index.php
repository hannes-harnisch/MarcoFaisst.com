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
	<script src='/JS/GoogleAnalytics.js'></script>
	<title>
		<?php
		require_once "Database.php";

		$dbName = Database::Name;
		$pdo = new PDO("mysql:host=localhost;dbname={$dbName};charset=utf8",
			DatabaseCredentials::User,
			DatabaseCredentials::Password,
			[
				PDO::ATTR_ERRMODE			=> PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false,
			]);

		$allowedYears = array("2019-2020", "2017-2018", "2015-2016", "2013-2014", "2010-2012");

		$activeLinkStyleModifier = "color:#221f20 !important";

		if(empty($_GET))
			echo "";
		elseif(!empty($_GET["uri"]))
		{
			$table;
			switch($_GET["page"])
			{
				case "grafiken":	$table = "graphic";
				case "zeichnungen":	$table = "drawing";
				default:			$table = "painting";
			}

			$query = $pdo->prepare("SELECT title FROM marcofaisst_com.{$table} WHERE uri = ?");
			$query->execute([$_GET["uri"]]);
			while($row = $query->fetch())
				echo "{$row["title"]} | ";
		}
		else
			echo ucfirst($_GET["page"])." | ";
			
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
							
							foreach($allowedYears as $value)
								echo "<a class='dropdown-item' href='/$value'>$value</a>";
								
							?>
						</div>
					</li><span class='separator'>|</span>
					<li class='nav-item'>
						<a class='nav-link' href='/grafiken' style='<?php if($_GET["page"] == "grafiken") echo $activeLinkStyleModifier;?>'>GRAFIKEN</a>
					</li>
					<span class='separator'>|</span>
					<li class='nav-item'>
						<a class='nav-link' href='/zeichnungen' style='<?php if($_GET["page"] == "zeichnungen") echo $activeLinkStyleModifier;?>'>ZEICHNUNGEN</a>
					</li>
					<span class='separator'>|</span>
					<li class='nav-item'>
						<a class='nav-link' href='/ausstellungen' style='<?php if($_GET["page"] == "ausstellungen") echo $activeLinkStyleModifier;?>'>AUSSTELLUNGEN</a>
					</li>
					<span class='separator'>|</span>
					<li class='nav-item'>
						<a class='nav-link' href='/vita' style='<?php if($_GET["page"] == "vita") echo $activeLinkStyleModifier;?>'>VITA</a>
					</li>
					<span class='separator'>|</span>
					<li class='nav-item'>
						<a class='nav-link' href='/kontakt' style='<?php if($_GET["page"] == "kontakt") echo $activeLinkStyleModifier;?>'>KONTAKT</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<div id='main-content' class='container'>
		<?php
		
		set_error_handler(function($errno, $errstr, $errfile, $errline)
		{
			throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
			
		});
		
		try
		{
			if(empty($_GET))
				require_once "./html/start.htm";
			elseif(in_array($_GET["page"], $allowedYears) && !empty($_GET["uri"]))
			{
				$years = explode("-", $_GET["page"]);
				
				$error_query = $pdo->prepare("SELECT COUNT(*) FROM marcofaisst_com.painting WHERE uri = ?");
				$error_query->execute([$_GET["uri"]]);
				if($error_query->fetchColumn() == 0)
					throw new Exception();
				
				$target_id_query = $pdo->prepare("SELECT id FROM marcofaisst_com.painting WHERE uri = ?");
				$target_id_query->execute([$_GET["uri"]]);
				$target_id = $target_id_query->fetch()["id"];
				
				$query = $pdo->prepare("(SELECT * FROM marcofaisst_com.painting WHERE id < ? AND year BETWEEN ? AND ? ORDER BY id DESC LIMIT 1) UNION
										(SELECT * FROM marcofaisst_com.painting WHERE id >= ? AND year BETWEEN ? AND ? LIMIT 2)");
				$query->execute([$target_id, $years[0], $years[1], $target_id, $years[0], $years[1]]);
				
				$prev_work_uri;
				$data;
				$next_work_uri;
				while($row = $query->fetch())
				{
					if($row["id"] < $target_id)
						$next_work_uri = $row["uri"];
					if($row["id"] == $target_id)
						$data = $row;
					if($row["id"] > $target_id)
						$prev_work_uri = $row["uri"];
				}
				
				echo "<div class='control-row'>
					<span>";
				
				if(isset($prev_work_uri))
					echo "<a href='/{$_GET["page"]}/$prev_work_uri'>
						<i class='fa fa-chevron-left'></i>
					</a>";
				
				echo "</span>
				<span class='centered-span'>
					<a href='/{$_GET["page"]}'>
						<i class='fa fa-close'></i>
					</a>
				</span>
				<span>";
				
				if(isset($next_work_uri))
					echo "<a href='/{$_GET["page"]}/$next_work_uri'>
						<i class='fa fa-chevron-right'></i>
					</a>";
				
				$height = str_replace(".", ",", $data["height"]);
				$width = str_replace(".", ",", $data["width"]);
				
				echo "</span>
					</div>
					<div class='row'><div class='artwork'>
						<a id='modal-link' href='#' data-toggle='modal' data-target='#work-display-modal'>
							<img src='/painting/{$data["uri"]}.jpg' alt='{$data["title"]}'>
						</a>
					</div>
				</div>
				<div class='row'>
					<div class='artwork'>
						<div class='artwork-info'>
							<strong class='artwork-title'>{$data["title"]}</strong>{$data["year"]}, {$data["medium"]} auf {$data["base"]}, {$height} × {$width} cm
						</div>
					</div>
				</div>
				<div class='modal fade' id='work-display-modal'>
					<div class='modal-dialog'>
						<img src='/painting/{$data["uri"]}.jpg' alt='{$data["title"]}'>
					</div>
				</div>
				<a class='control-icon control-close' href='/{$_GET["page"]}'>
					<i class='fa fa-close'></i>
				</a>";
				
				if(isset($prev_work_uri))
					echo "<a class='control-icon control-left' href='/{$_GET["page"]}/$prev_work_uri'>
						<i class='fa fa-chevron-left'></i>
					</a>";
				
				if(isset($next_work_uri))
					echo "<a class='control-icon control-right' href='/{$_GET["page"]}/$next_work_uri'>
						<i class='fa fa-chevron-right'></i>
					</a>";
			}
			elseif(in_array($_GET["page"], $allowedYears))
			{
				$years = explode("-", $_GET["page"]);
				$query = $pdo->prepare("SELECT title, uri FROM marcofaisst_com.painting WHERE year BETWEEN ? AND ? ORDER BY id DESC");
				$query->execute([$years[0], $years[1]]);
				
				echo "<div class='row'>";
				
				for($i = 1; $row = $query->fetch(); $i++)
				{
					echo "<div class='col-12 col-sm-4 artwork-grid'>
						<span class='vertical-aligner'></span>
						<a href='/{$_GET["page"]}/{$row["uri"]}'>
							<img src='/painting/preview/{$row["uri"]}.jpg' alt='{$row["title"]}'>
						</a>
					</div>";
					
					if($i % 3 == 0)
						echo "</div>
						<div class='row'>";
				}
				
				echo "</div>";
			}
			elseif(in_array($_GET["page"], array("grafiken", "zeichnungen")) && !empty($_GET["uri"]))
			{
				$table;
				switch($_GET["page"])
				{
					case "grafiken":
						$table = "graphic"; break;
					case "zeichnungen":
						$table = "drawing"; break;
				}
				
				$error_query = $pdo->prepare("SELECT COUNT(*) FROM marcofaisst_com.$table WHERE uri = ?");
				$error_query->execute([$_GET["uri"]]);
				if($error_query->fetchColumn() == 0)
					throw new Exception();
					
				$target_id_query = $pdo->prepare("SELECT id FROM marcofaisst_com.$table WHERE uri = ?");
				$target_id_query->execute([$_GET["uri"]]);
				$target_id = $target_id_query->fetch()["id"];
				
				$query = $pdo->prepare("(SELECT * FROM marcofaisst_com.$table WHERE ID < ? ORDER BY id DESC LIMIT 1) UNION
										(SELECT * FROM marcofaisst_com.$table WHERE ID >= ? LIMIT 2)");
				$query->execute([$target_id, $target_id]);
				
				$prev_work_uri;
				$data;
				$next_work_uri;
				while($row = $query->fetch())
				{
					if($row["id"] < $target_id)
						$next_work_uri = $row["uri"];
					if($row["id"] == $target_id)
						$data = $row;
					if($row["id"] > $target_id)
						$prev_work_uri = $row["uri"];
				}
				
				echo "<div class='control-row'>
					<span>";
				
				if(isset($prev_work_uri))
					echo "<a href='/{$_GET["page"]}/$prev_work_uri'>
						<i class='fa fa-chevron-left'></i>
					</a>";
				
				echo "</span>
				<span class='centered-span'>
					<a href='/{$_GET["page"]}'>
						<i class='fa fa-close'></i>
					</a>
				</span>
				<span>";
				
				if(isset($next_work_uri))
					echo "<a href='/{$_GET["page"]}/$next_work_uri'>
						<i class='fa fa-chevron-right'></i>
					</a>";
				
				echo "</span>
				</div>
				<div class='row'>
					<div class='artwork'>
						<a id='modal-link' href='#' data-toggle='modal' data-target='#work-display-modal'>
							<img src='/$table/{$data["uri"]}.jpg' alt='{$data["title"]}'>
						</a>
					</div>
				</div>
				<div class='row'>
					<div class='artwork'>
						<div class='artwork-info'>
							<strong class='artwork-title'>{$data["title"]}</strong>{$data["year"]}, {$data["medium"]}";

				if(!empty($data["base"]))
					echo " auf {$data["base"]}";
							
				if($data["height"] != 0 && $data["width"] != 0)
				{
					$height = str_replace(".", ",", $data["height"]);
					$width = str_replace(".", ",", $data["width"]);
					echo ", {$height} × {$width} cm";
				}
					
				echo "</div>
					</div>
				</div>
				<div class='modal fade' id='work-display-modal'>
					<div class='modal-dialog'>
						<img src='/$table/{$data["uri"]}.jpg' alt='{$data["title"]}'>
					</div>
				</div>
				<a class='control-icon control-close' href='/{$_GET["page"]}'>
					<i class='fa fa-close'></i>
				</a>";
				
				if(isset($prev_work_uri))
					echo "<a class='control-icon control-left' href='/{$_GET["page"]}/$prev_work_uri'>
						<i class='fa fa-chevron-left'></i>
					</a>";
				
				if(isset($next_work_uri))
					echo "<a class='control-icon control-right' href='/{$_GET["page"]}/$next_work_uri'>
						<i class='fa fa-chevron-right'></i>
					</a>";
			}
			elseif(in_array($_GET["page"], array("grafiken", "zeichnungen")))
			{
				$table;
				switch($_GET["page"])
				{
					case "grafiken":
						$table = "graphic"; break;
					case "zeichnungen":
						$table = "drawing"; break;
				}
				
				$query = $pdo->query("SELECT title, uri FROM marcofaisst_com.$table ORDER BY id DESC");
				echo "<div class='row'>";
				
				for($i = 1; $row = $query->fetch(); $i++)
				{
					echo "<div class='col-12 col-sm-4 artwork-grid'>
						<span class='vertical-aligner'></span>
						<a href='/{$_GET["page"]}/{$row["uri"]}'>
							<img src='/$table/preview/{$row["uri"]}.jpg' alt='{$row["title"]}'>
						</a>
					</div>";

					if($i % 3 == 0)
						echo "</div>
						<div class='row'>";
				}
				
				echo '</div>';
			}
			else
				require_once "./html/{$_GET["page"]}.htm";
		}
		catch(Exception $e)
		{
			require_once "./html/fehler.htm";
		}
		
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