<html>
	<head>
		<?php
			$config = json_decode(file_get_contents("config.json", true));
			$title = $config->site->title;
			echo "<title>$title</title>";
		?>
		<link rel="stylesheet" type="text/css" href="/static/styles.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
	<header>
		<?php
			$config = json_decode(file_get_contents("config.json", true));
			$title = $config->site->title;
			echo "<h2 class=\"ribbon\">$title</h2>";
		?>
	</header>
