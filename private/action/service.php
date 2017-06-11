<?php virtual("/includes/header"); ?>
<body>
	<div id="content">
		<div class="jumbotron cell">
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
					# sends command to server
					exec("sudo /usr/bin/ssh $user@$host $command $option 2>&1", $output);
				}
			?>
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
			<a class="btn btn-primary" href="javascript:history.go(-1)">Back</a>
		</div>
	</div>
<?php virtual("/includes/footer"); ?>
