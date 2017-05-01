<html>
	<head>
		<?php
			$config = json_decode(file_get_contents("config.json", true));
			$title = $config->site->title;
			echo "<title>$title</title>";
		?>
		<link rel="icon" type="image/png" href="/static/favicon.ico">
		<link rel="stylesheet" type="text/css" href="/static/styles.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">
						<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
					</a>
					<?php
						$config = json_decode(file_get_contents("config.json", true));
						$title = $config->site->title;
						echo "<a class=\"navbar-brand\" href=\"/\">$title</a>"
					?>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav pull-right">
						<li><a class="navbar_nav"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo "{$_SERVER['PHP_AUTH_USER']}"; ?></a></li>
					</ul>
				</div>
			</div>
		</nav>
	<div class="container">
