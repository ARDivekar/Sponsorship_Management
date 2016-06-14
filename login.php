<?php
	session_start();
	include_once "DBconnect.php";
	$db = new SponsorshipDB();
	include_once "library_functions.php";
	include_once "FormAndFieldClasses.php";
	include_once "SecurityFunctions.php";
	include_once "SponsEnums.php";

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Sponsorship Login form</title>

	<link rel="stylesheet" href="./LoginForm/css/reset.css">

<!--	<link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>-->

	<link rel='stylesheet prefetch' href='./Font-Awesome/css/font-awesome.min.css'>

	<link rel="stylesheet" href="./LoginForm/css/style.css">

</head>

<body>
<header align="center" style="background-color: #305496; color:white; min-height: 60px; font-size: 46px; font-family: Candara; padding:15px 0">
	<h1>Sponsorship Department, VJTI</h1>
</header>

<div class="container header-margin">
	<div class="card"></div>
	<div class="card">
		<h1 class="title">Login</h1>

		<form name="SponsLogin" action="login_execute.php" method="POST">
			<div class="input-container">
				<input type="text" id="Username" required="required" name="<?php echo SessionEnums::UserLoginID; ?>"/>
				<label for="Username">Username</label>

				<div class="bar"></div>
			</div>
			<div class="input-container">
				<input type="password" id="Password" required="required" name="<?php echo QueryFieldNames::SponsPassword; ?>"/>
				<label for="Password">Password</label>

				<div class="bar"></div>
			</div>
			<div class="button-container">
				<button name="<?php echo QueryFieldNames::Submit; ?>"><span>Go</span></button>
			</div>
			<div class="footer"><a href="#">Forgot your password?</a></div>
		</form>
	</div>


	<div class="card alt">
		<div class="toggle"></div>
		<h1 class="title">Change Password
			<div class="close"></div>
		</h1>
		<form>
			<div class="input-container">
				<input type="text" id="Username" required="required"/>
				<label for="Username">Username</label>

				<div class="bar"></div>
			</div>
			<div class="input-container">
				<input type="password" id="Password" required="required"/>
				<label for="Password">Password</label>

				<div class="bar"></div>
			</div>
			<div class="input-container">
				<input type="password" id="Repeat Password" required="required"/>
				<label for="Repeat Password">Repeat Password</label>

				<div class="bar"></div>
			</div>
			<div class="button-container">
				<button><span>Next</span></button>
			</div>
		</form>
	</div>
</div>

	<script type="text/javascript" src='./ExternalLibraries/jQuery/jquery.min.js'></script>

	<script type="text/javascript" src="./LoginForm/js/index.js"></script>

</body>
</html>
