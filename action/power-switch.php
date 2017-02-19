<?php virtual("/includes/header.php"); ?>
<body>
	<div id="content">
		<div class="block">
			<p>
				<?php
					if ( isset( $_GET['host'], $_GET['gpio'], $_GET['time'] ) ) {
						$host = (string)$_GET['host'];
						$gpio = (int)$_GET['gpio'];
						$time = (int)$_GET['time'];
						# runs python script
						exec("sudo /usr/bin/python /usr/scripts/power-ctl.py $gpio $time");
						# displays action
						echo "Holding the power switch for $time second(s)";
					} else {
						echo "Error: Invalid JSON";
					}
				?>
			</p>
			<a class="button" href="/index.php">Home</a>
		</div>
	</div>
<?php virtual("/includes/footer.php"); ?>
