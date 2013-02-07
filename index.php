<?php
  session_start();

	$ini_array = parse_ini_file("config.ini");

	if ($_SESSION['authenticated'] == true) {
		include($ini_array["next"]);
	} else {
		$error = null;
		if (!empty($_POST)) {
			$username = empty($_POST['username']) ? null : $_POST['username'];
			$password = empty($_POST['password']) ? null : $_POST['password'];

			if ($username == $ini_array["username"] && $password == $ini_array["password"]) {
				$_SESSION['authenticated'] = true;
				header('Location: index.php');
				return;
			} else {
				$error = 'Incorrect username or password';
			}
		}
		echo $error;
		?>
			<form method="POST" action="index.php">
				Username <input type="text" name="username" />
				Password <input type="text" name="password" />
				<input type="submit" value="login" />
			</form>
		<?php
	}
