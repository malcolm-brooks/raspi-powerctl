<?php
class htpasswd {
		var $fp;
		var $filename;

		function htpasswd($filename) {
				@$this->fp = fopen($filename,'r+') or die('Invalid file name');
				$this->filename = $filename;
		}

		function user_update($username,$password) {
				rewind($this->fp);
						while(!feof($this->fp)) {
								$line = rtrim(fgets($this->fp));
								if(!$line)
										continue;
								$lusername = explode(":",$line)[0];
								if($lusername == $username) {
										fseek($this->fp,(-24-15 - strlen($username)),SEEK_CUR);
										fwrite($this->fp,$username.':'.$this->crypt_apr1_md5($password)."\n");
										return true;
								}
						}
				return false;
		}

		function crypt_apr1_md5($plainpasswd)
		{
			$salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
			$len = strlen($plainpasswd);
			$text = $plainpasswd.'$apr1$'.$salt;
			$bin = pack("H32", md5($plainpasswd.$salt.$plainpasswd));
			for($i = $len; $i > 0; $i -= 16) { $text .= substr($bin, 0, min(16, $i)); }
			for($i = $len; $i > 0; $i >>= 1) { $text .= ($i & 1) ? chr(0) : $plainpasswd{0}; }
			$bin = pack("H32", md5($text));
			for($i = 0; $i < 1000; $i++)
			{
				$new = ($i & 1) ? $plainpasswd : $bin;
				if ($i % 3) $new .= $salt;
				if ($i % 7) $new .= $plainpasswd;
				$new .= ($i & 1) ? $bin : $plainpasswd;
				$bin = pack("H32", md5($new));
			}
			for ($i = 0; $i < 5; $i++)
			{
				$k = $i + 6;
				$j = $i + 12;
				if ($j == 16) $j = 5;
				$tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
			}
			$tmp = chr(0).chr(0).$bin[11].$tmp;
			$tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
			"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
			"./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");

			return "$"."apr1"."$".$salt."$".$tmp;
		}

}
?>

<?php virtual("/includes/header"); ?>
<div id="content">
<?php
# attempt to update password on POST requests only
if (!empty($_POST)) {
	$pwd = $_POST['httpd_new_pwd'];
	$rpwd = $_POST['httpd_r_new_pwd'];
	if ( $pwd==$rpwd and strlen($pwd) >= 8 ) {
		$htpasswd = new htpasswd('/etc/apache2/.htpasswd');
		if ($htpasswd->user_update("{$_SERVER['PHP_AUTH_USER']}",$pwd)) {
			echo "<div class=\"alert alert-success\"><strong>Success!</strong> Your password has been updated.</div>";
		}
	} else {
		echo "<div class=\"alert alert-danger\"><strong>Failed!</strong> Your password has not been updated.</div>";
	}
}
?>
	<form class="form-signin" method="POST" action="#">
	<div class="well">
		<div class="form-group">
			<label for="httpd_new_pwd" class="sr-only">New password</label>
			<input type="password" name="httpd_new_pwd" class="form-control" placeholder="New password" value="" />
			<label for="httpd_r_new_pwd" class="sr-only">Confirm new password</label>
			<input type="password" name="httpd_r_new_pwd" class="form-control" placeholder="Confirm new password" value="" />
		</div>
		<button class="btn btn-lg btn-danger btn-block" type="submit">Change Password</button>
	</div>
	</form>
</div>
<?php virtual("/includes/footer"); ?>
