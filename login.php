<?php virtual("/includes/header"); ?>
<div id="content">
	<form class="form-signin" method="POST" action="/dologin.html?<?php echo $_GET["next"] ?>">
	<div class="well">
		<label for="httpd_username" class="sr-only">Username</label>
		<input type="text" name="httpd_username" class="form-control" placeholder="Username" value="" />
		<label for="httpd_password" class="sr-only">Password</label>
		<input type="password" name="httpd_password" class="form-control" placeholder="Password" value="" />
		<button class="btn btn-lg btn-primary btn-block" type="submit">Log In</button>
  </div>
	</form>
</div>
<?php virtual("/includes/footer"); ?>
