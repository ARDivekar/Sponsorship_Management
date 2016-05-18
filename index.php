<?php
	if($_GET['error']=='404'){
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
		header("Location: 404.php");
	}

	else header("Location: login.php");
?>