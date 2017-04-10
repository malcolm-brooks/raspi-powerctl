<?php virtual("/includes/header.php"); ?>
<div id="content">
	<div class="row">
	<?php
		$devices = json_decode(file_get_contents("includes/config.json"));
		foreach($devices->devices as $device)
		{
			echo "<div class=\"col-md-6\">";
			echo "<div class=\"well\">";
			echo "<h2>$device->name</h2>";
			echo "<p><b>Current Status:</b> ";
			# pings device and returns either an error or alive
			if ( exec("fping -r 1 -t 100 $device->host | awk ' {print$3} ' ") == "alive" )
			{
				echo "Online";
				echo "</p><div class=\"btn-toolbar\">";
				if ($device->gpio) {
					echo "<a class=\"btn btn-primary\" href=\"/action/power-switch.php?gpio=$device->gpio&time=1\" role=\"button\">Sleep</a>";
					echo "<a class=\"btn btn-danger\" href=\"/action/power-switch.php?gpio=$device->gpio&time=6\" role=\"button\">Shutdown</a>";
				}
				echo "</div>";
			} 
			else 
			{
				echo "Offline";
				echo "</p><div class=\"btn-toolbar\">";
				if ($device->gpio) {
					echo "<a class=\"btn btn-primary\" href=\"/action/power-switch.php?gpio=$device->gpio&time=1\" role=\"button\">Power On</a>";
				}
				echo "</div>";
			}
			echo "<div class=\"btn-group\">";
			echo "</div>";
			echo "</div></div>";
		}
	?>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">Raspberry Pi</div>
			<div class="panel-body">
			<?php
				$temp = exec("sudo /opt/vc/bin/vcgencmd measure_temp");
				$tempjunk = array("temp=");
				$tempclean = str_replace($tempjunk, "", $temp);
				echo "<div class=\"col-sm-4\"><b>System Temperature:</b> $tempclean</div>";
			?>
			<?php
				$cpu = exec("cat /proc/loadavg | awk '{print $1, $2, $3}'");
				$cpujunk = array("load average:");
				$cpuclean = str_replace($cpujunk, "", $cpu);
				echo "<div class=\"col-sm-4\"><b>Load Averages:</b> $cpuclean</div>";
			?>
			<?php
				$ram = exec("free -h | grep Mem |  awk ' {print$3,\" of \",$2} ' ");
				$ramclean = str_replace("M", "MB", $ram);
				echo "<div class=\"col-sm-4\"><b>Memory Usage:</b> $ramclean</div>";
			?>
			</div>
		</div>
	</div>
</div>
<?php virtual("/includes/footer.php"); ?>
