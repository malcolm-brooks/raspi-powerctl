<?php virtual("/includes/header.php"); ?>
<div id="content">
	<div class="jumbotron cell">
		<p>
			<?php
				# check for empty variables in GET request
				foreach($_GET as $name => $value) {
					if($value == "") {
						$exit = True;
					}
				}
				if ($exit) {
					echo "Error: Invalid JSON";
				} else {
					# extract variables from GET request
					extract($_GET);
					# runs wol tool
					exec("/usr/local/bin/wol $mac 2>&1", $output);
					# displays action
					echo "Sending magic packet for hardware address \"$mac\"";
				}
			?>
		</p>
		<?php
			# print error if applicable
			if ($output) {
				echo "<code>";
				foreach ($output as $i => $l) {
					if ($i > 0) { echo "</br>$l"; } else { echo "$l"; }
				}
				echo "</code>";
			}
		?>
		<a class="btn btn-primary" href="javascript:window.location = document.referrer">Back</a>
	</div>
</div>
<?php virtual("/includes/footer.php"); ?>
