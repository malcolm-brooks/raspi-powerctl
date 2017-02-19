<?php virtual("/includes/header.php"); ?>
<div id="content">
	<?php
		$devices = json_decode(file_get_contents("includes/config.json"));
		foreach($devices->devices as $device)
		{
			echo "<div class=\"block\">";
			echo "<h3>$device->name</h3>";
			echo "<p><b>Current Status:</b> ";
			# pings device and returns either an error or alive
			if ( exec("fping -i 150 -t 150 $device->host | awk ' {print$3} ' ") == "alive" )
			{
				echo "Online</p>";
				echo "<a class=\"button\" href=\"/action/power-switch.php?host=$device->host&gpio=$device->gpio&time=1\">Sleep</a>";
				echo "<a class=\"button\" href=\"/action/power-switch.php?host=$device->host&gpio=$device->gpio&time=6\">Shutdown</a>";
			} 
			else 
			{
				echo "Offline</p>";
				echo "<a class=\"button\" href=\"/action/power-switch.php?host=$device->host&gpio=$device->gpio&time=1\">Power On</a>";
			}
			echo "</div>";
		}
	?>
	<div class="block">
		<h3>Raspberry Pi</h3>
		<?php 
			$temp = exec("sudo /opt/vc/bin/vcgencmd measure_temp");
			$tempjunk = array("temp=");
			$tempclean = str_replace($tempjunk, "", $temp);
			echo "<p><b>System Temperature:</b> $tempclean</p>";
		?>
		<?php 
			$cpu = exec("cat /proc/loadavg | awk '{print $1, $2, $3}'");
			$cpujunk = array("load average:");
			$cpuclean = str_replace($cpujunk, "", $cpu);
			echo "<p><b>Load Averages:</b> $cpuclean</p>";
		?>
		<?php 
			$ram = exec("free -h | grep Mem |  awk ' {print$3,\" of \",$2} ' ");
			$ramclean = str_replace("M", "MB", $ram);
			echo "<p><b>Memory Usage:</b> $ramclean</p>";
		?>
	</div>
</div>
<?php virtual("/includes/footer.php"); ?>
