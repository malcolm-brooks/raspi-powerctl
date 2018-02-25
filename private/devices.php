<?php virtual("/includes/header"); ?>
<div id="content">
	<div class="row">
	<?php
		$private = json_decode(file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/private/private.config"));
		foreach($private->devices as $device)
		{
			echo "<div class=\"col-md-6\">";
			echo "<div class=\"jumbotron cell\">";
			echo "<h2>$device->name</h2>";
			echo "<p><b>Current Status:</b> ";
			# pings device and returns either an error or alive
			if ( exec("fping -r 1 -t 100 $device->host | awk ' {print$3} ' ") == "alive" )
			{
				echo "Online";
				echo "</p>";
				if (! isset($device->owner) || "{$_SERVER['PHP_AUTH_USER']}" == $device->owner) {
					echo "<div class=\"btn-toolbar\">";
					if ($device->gpio) {
						echo "<a class=\"btn btn-primary\" href=\"/private/action/power-switch?gpio=$device->gpio&time=1\" role=\"button\">Sleep</a>";
						echo "<a class=\"btn btn-danger\" href=\"/private/action/power-switch?gpio=$device->gpio&time=6\" role=\"button\">Shutdown</a>";
					}
					echo "</div>";
				} else {
					echo"<p>Only the device owner ($device->owner) can perform actions on an online device.</p>";
				}
			} 
			else 
			{
				echo "Offline";
				echo "</p><div class=\"btn-toolbar\">";
				if ($device->gpio) {
					echo "<a class=\"btn btn-primary\" href=\"/private/action/power-switch?gpio=$device->gpio&time=1\" role=\"button\">Power On</a>";
				}
				if ($device->mac_address) {
					echo "<a class=\"btn btn-primary\" href=\"/private/action/wake-on-lan.php?mac=$device->mac_address\" role=\"button\">Wake</a>";
				}
				echo "</div>";
			}
			echo "</div></div>";
		}
	?>
	</div>
	<?php virtual("/includes/host-stats"); ?>
</div>
<?php virtual("/includes/footer"); ?>
