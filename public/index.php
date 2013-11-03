<?php
	session_start();

	$error = null;
	$ini_array = parse_ini_file("../config.ini");
	$cookiehash = sha1($ini_array["remember"] . $_SERVER['REMOTE_ADDR'] . "wbyoko");
	$remembered = strcmp($cookiehash, $_COOKIE["remember"]) === 0;

	// logoff
	if (!empty($_POST['logoff'])) {
		$_POST = null;
		$remembered = false;
		$_SESSION['authenticated'] = false;
		setcookie("remember", "", time() - 3600);
	}

	// try login
	if (!empty($_POST)) {
		$username = empty($_POST['username']) ? null : $_POST['username'];
		$password = empty($_POST['password']) ? null : $_POST['password'];

		if ($username === $ini_array["username"] && $password === $ini_array["password"]) {
			$_SESSION['authenticated'] = true;
			if ($_POST['remember'] === "on") {
				setcookie("remember", $cookiehash, time()+3600*24*365);
			}
			header("location: {$_SERVER['REQUEST_URI']}");
        		exit;
		} else {
			$error = 'Incorrect username or password';
		}
	}

	// display view
	if ($_SESSION['authenticated'] == true || $remembered) {
		include($ini_array["next"]);
	} else { ?>
		<!doctype html>
		<html lang="en">
			<head>
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
			    <?php if (isset($ini_array["title"])) echo  "<title>" . $ini_array["title"] . "</title>"; ?>
			</head>
			<body>
				<?php if (!empty($error)) echo  "<p>" . $error . "</p>"; ?>
				<form method="post" style="margin: 50px auto; width: 33%;">
					<fieldset>
			    		<?php if (isset($ini_array["title"])) echo  "<legend>" . $ini_array["title"] . "</legend>"; ?>

						<label for="username">Username</label>
						<input id="username" name="username" type="text" placeholder="Username">
						<br />

						<label for="password">Password</label>
						<input id="password" name="password" type="password" placeholder="Password">
						<br />

						<label for="remember">Remember me</label>
						<input id="remember" name="remember" type="checkbox">
						<br />
						
						<button type="submit" value="login">Sign in</button>
					</fieldset>
				</form>
			</body>
		</html>
	<?php }
