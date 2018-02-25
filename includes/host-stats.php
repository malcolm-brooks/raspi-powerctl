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
