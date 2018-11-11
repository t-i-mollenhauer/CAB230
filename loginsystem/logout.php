<html>
<head>
	header('Location: '.$newURL);
</head>
<body>



	<!--Logges the user out of the session and destroys cookies then redirects the user to the home page -->
	<?php
	session_start();
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
			);
	}
	session_unset();
	session_destroy();
	$_SESSION = array();



	header('Location: ../searchcurrent.php');
	?>
</body>
</html>