<?php virtual("/includes/header"); ?>
<div id="content">
	<div class="row">
	<?php
		$private = json_decode(file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/private/private.config"));
		foreach($private->services as $service)
		{
			echo "<div class=\"col-md-6\">";
			echo "<div class=\"jumbotron cell\">";
			echo "<h2>$service->name</h2>";
			echo "<div class=\"btn-toolbar\">";
			foreach($service->commands as $cmd)
			{
				echo "<a class=\"btn btn-primary\" href=\"/private/action/service?user=$service->user&host=$service->host&command=$cmd->command\" role=\"button\">", ucwords($cmd->name) ,"</a>";
			}
			echo "</div></div></div>";
		}
	?>
	</div>
	<?php virtual("/includes/host-stats"); ?>
</div>
<?php virtual("/includes/footer"); ?>
