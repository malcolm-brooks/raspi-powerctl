		<footer>
			<?php
				$config = json_decode(file_get_contents("config.json", true));
				$footer = $config->site->footer;
				echo "<p class=\"footer-content\">$footer</p>";
			?>
		</footer>
	</body>
</html>
