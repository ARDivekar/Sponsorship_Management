<?php
	include_once "DBconnect.php";
	$db = new SponsorshipDB();
	include_once "library_functions.php";
	include_once "FormAndFieldClasses.php";
	include_once "SecurityFunctions.php";
	include_once "SponsEnums.php";
	include_once "SponsDBFunctions.php";
	include_once "UserNavBarImports.php";

	//Getting data from HTML form:
	//$_POST is a 'superglobal' variable, and how you get data from an HTML form
	
	if (isset($_POST[QueryFieldNames::Submit])){ //Check if the form has been submitted. The form is in the same HTML file.
		$SponsPassword = $_POST[QueryFieldNames::SponsPassword];
		$SponsID = $_POST[SessionEnums::UserLoginID];


		if (isset($SponsID) && isset($SponsPassword)){
			//check if individual variables in the form are set. If either are blank, the condition is false.

			if ($SponsID == "" or $SponsPassword == ""){
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


				if (count($SponsLogin) == 1){ //if not, we have multiple entries with the same userID in the database.
					if ($SponsLogin[0]["Password"] != $SponsPassword && !checkPasswordHash($SponsPassword, $SponsLogin[0]["Password"])){
						/* Could not log in:
						 * exit script and print <invalid login message>
						 * */
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
						$_SESSION[SessionEnums::UserSector] = $_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? QueryFieldNames::CSOSector : $possibleSector;

						header("Location: homepage.php");

					}

				}

			}
		}

	}
	?>
	<script type="text/javascript">
		function sleep(milliseconds) {
		  var start = new Date().getTime();
		  while((new Date().getTime() - start) < milliseconds){
		  }
		}
		document.write('<h1 align="center" style="color: #cc0000;" class="valid_message">Login not authorized.<br>Please check ID and Password</h1>');
		window.location="login.php";
	</script>