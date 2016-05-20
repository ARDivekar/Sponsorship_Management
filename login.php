<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<header align="center">
	<?php
	require('DBconnect.php');
	
	echo "<h1>Sponsorship Department, VJTI</h1>"
	?>
</header>
	

<div id"page" align= "center">
	
	<h2>Login page</h2>
	<form class="test_login" method="post" action="login.php?submit=true">
		<div>
			<label for="SponsorshipID">SponsID:</label>
			<input type="text" name="SponsID" />
		</div>
		<div>
			<label for="Password">Password:</label>
			<input type="password" name="password" value="" />
		</div>
		<button type="submit" name="submit" class="login_button">Login</button>
		
	</form>	

<?php


require('DBconnect.php');



//Getting data from HTML form:

//$_POST is a 'superglobal' variable, and how you get data from an HTML form
if(isset($_POST['submit'])){ //Check if the form has been submitted. The form is in the same HTML file.

	$SponsPassword=$_POST['password'];
	$SponsID=$_POST['SponsID'];

	if(isset($SponsID) && isset($SponsPassword)){ 
	//check if individual variables in the form are set. If either are blank, the condition is false.

		if($SponsID =="" or  $SponsPassword==""){
			//print __incorrect data message__ and exits script
			exit('<h3 class="invalid_message">Improper Form submission<br>Please enter ID and Password.</h3>');
		}
		else{
			$login_query = "SELECT AccessLevel FROM `SponsLogin` WHERE SponsID=$SponsID and (Password=\"$SponsPassword\" or Password='".md5($SponsPassword)."');";
			// echo $login_query;
			$SponsLogin=mysql_query($login_query );

			if(mysql_num_rows($SponsLogin) > 0){
				/*Successful login*/
				echo '<h3 class="valid_message">Connected Successfully.<br>Redirecting...</h3>';
				

				/*starting session:*/ 
				session_start();
				
				$_SESSION['loginID']=$SponsID; //we need to pass $SponsID between pages, hence we use the $_SESSION associative array. Google it.

				$SponsLogin=mysql_fetch_assoc($SponsLogin); //we don't need the old SponsLogin, let it be reassigned.
				$SponsLoginAccessLevel = $SponsLogin["AccessLevel"];


				/*redirecting to appropriate page:*/
				if($SponsLoginAccessLevel == "SponsRep"){
					header("Location: Sponsorship Representative.php");
				}
				else if($SponsLoginAccessLevel == "SectorHead"){
					header("Location: Sector Head.php");
				}
				else if($SponsLoginAccessLevel == "CSO"){
					header("Location: CSO.php");
				}
				else {
					exit('<h3 class="valid_message">Invalid access level.<br>Please contact database admin</h3>');
					session_write_close();
				}

			}
			else{
				/*exit script and print __invalid login message__ */
				exit('<h3 class="valid_message">Login not authorized.<br>Please check ID and Password</h3>');
				
			}
		}
	}
		
}

$SponsID=""; //safety purposes
$SponsPassword="";

?>
</body>

</html>
