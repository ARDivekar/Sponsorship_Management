<?php
//		header("Location: login.php");
		include_once "DBconnect.php";
		$db = new SponsorshipDB();
		include_once "library_functions.php";
		include_once "FormAndFieldClasses.php";
		include_once "SecurityFunctions.php";
		include_once "SponsEnums.php";
		include_once "SponsDBFunctions.php";
		include_once "UserNavBarImports.php";
	?>

<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<header align="center" style="background-color: #305496;">
	<h1>Sponsorship Department, VJTI</h1>
</header>
	

<div id"page" align= "center">
	
	<h2>Login page</h2>

<?php
	echo new HTMLForm(
		$formName = "SponsRepInsert", $formAction = "login.php", $formMethod = FormMethod::POST,
		$fields = array(
			new InputField(
				$inputType = InputTypes::text, $name = SessionEnums::UserLoginID, $value = "", $disabled = false, $inputCSSClass = "form-control",
				$labelText = "Reg. ID", $labelCSSClass = NULL
			),
			new InputField(
				$inputType = InputTypes::password, $name = QueryFieldNames::SponsPassword, $value = "", $disabled = false, $inputCSSClass = "form-control",
				$labelText = "Password", $labelCSSClass = NULL
			),

			new InputField(
				$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = "Login", $disabled = false, $inputCSSClass = "query_forms btn btn-primary"
			)
		),
		$formCSSClass="query_forms",
		$title = NULL,
		$fieldSeparator = "<br><br>"
	);

/*
	<form class="test_login" method="post" action="login.php?submit=true">
		<div>
			<label for="SponsorshipID">SponsID:</label>
			<input type="text" name="<?php echo SessionEnums::UserLoginID ?>" />
		</div>
		<div>
			<label for="Password">Password:</label>
			<input type="password" name="<?php echo QueryFieldNames::SponsPassword ?>" value="" />
		</div>
		<button type="submit" name="submit" class="login_button">Login</button>
		
	</form>	
*/

?>
<?php


//$_SESSION[SessionEnums::UserLoginID] = $_SESSION['loginID'];


//Getting data from HTML form:

//$_POST is a 'superglobal' variable, and how you get data from an HTML form



if(isset($_POST[QueryFieldNames::Submit])){ //Check if the form has been submitted. The form is in the same HTML file.

	$SponsPassword=$_POST[QueryFieldNames::SponsPassword];
	$SponsID=$_POST[SessionEnums::UserLoginID];


	if(isset($SponsID) && isset($SponsPassword)){ 
	//check if individual variables in the form are set. If either are blank, the condition is false.

		if($SponsID =="" or  $SponsPassword==""){
			//print __incorrect data message__ and exits script
			exit('<h3 class="invalid_message">Improper Form submission<br>Please enter ID and Password.</h3>');
		}
		else{
			$login_query = "
				SELECT
					Password,
					AccessLevel,
					Organization,
					EventName,
					Department,
					Name,
					Role,
					Email,
					Mobile,
					Year,
					Branch
				FROM SponsLogin INNER JOIN CommitteeMember ON (SponsLogin.SponsID = CommitteeMember.ID)
				WHERE SponsLogin.SponsID=$SponsID";

			$SponsLogin = $db->select($login_query);


			if(count($SponsLogin) == 1){ //if not, we have multiple entries with the same userID in the database.
				if($SponsLogin[0]["Password"] != $SponsPassword && !checkPasswordHash($SponsPassword, $SponsLogin[0]["Password"])){
					/* Could not log in:
					 * exit script and print <invalid login message>
					 * */
					exit('<h3 class="valid_message">Login not authorized.<br>Please check ID and Password</h3>');
				}

				else{
					// Login successful:
					echo '<h3 class="valid_message">Connected Successfully.<br>Redirecting...</h3>';

					/*starting session:*/
					session_start();

					$_SESSION[SessionEnums::UserLoginID] = $SponsID; //we need to pass $SponsID between pages, hence we use the $_SESSION associative array. Google it.
					$_SESSION[SessionEnums::UserAccessLevel] = $SponsLogin[0]["AccessLevel"];
					$_SESSION[SessionEnums::UserOrganization] = $SponsLogin[0]["Organization"];
					$_SESSION[SessionEnums::UserFestival] = $SponsLogin[0]["EventName"];
					$_SESSION[SessionEnums::UserDepartment] = $SponsLogin[0]["Department"];
					$_SESSION[SessionEnums::UserName] = $SponsLogin[0]["Name"];
					$_SESSION[SessionEnums::UserRole] = $SponsLogin[0]["Role"];
					$_SESSION[SessionEnums::UserEmail] = $SponsLogin[0]["Email"];
					$_SESSION[SessionEnums::UserMobile] = $SponsLogin[0]["Mobile"];
					$_SESSION[SessionEnums::UserYear] = $SponsLogin[0]["Year"];
					$_SESSION[SessionEnums::UserBranch] = $SponsLogin[0]["Branch"];

					$possibleSector = get_person_sector($SponsID);
					$_SESSION[SessionEnums::UserSector] = $_SESSION[SessionEnums::UserAccessLevel]==UserTypes::CSO ? QueryFieldNames::CSOSector : $possibleSector;

					header("Location: homepage.php");


				}




			}

		}
	}
		
}

$SponsID=""; //safety purposes
$SponsPassword="";

?>
</body>

</html>
