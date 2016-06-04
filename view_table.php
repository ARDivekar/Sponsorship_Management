<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">

</head>

<body>
<?php


	/*Resume old session:*/
	session_start();

	include_once('library_functions.php');

	if(empty($_SESSION[SessionEnums::UserLoginID]))
		header("Location: login.php");

	$SponsID=$_SESSION[SessionEnums::UserLoginID]; //get SponsID from previous session

	/*
	echo "<br><br>In view_table.php<br>";
	foreach ($_SESSION as $key => $value){
		echo $key . " " . $value . "<br>";
	}
	*/

	$SponsID = $_SESSION['loginID']; //get SponsID from previous session
	$_SESSION[SessionEnums::UserLoginID] = $_SESSION['loginID'];

	$UnauthorizedMessage = '<div align="center"><h3 align="center" style="padding: 40px; font-size:28px; line-height:50px;"  class="invalid_message">Sorry, you are not permitted to run this query.</h3> </div>';
	$FieldEmptyMessage = '<div align=center><h3 align=center style="padding: 40px; font-size:28px; line-height:50px;"  class="invalid_message">Error<br>You have not filled all the required fields.</h3> </div>';

	$SponsRepBackButton = "<h2><a href='Sponsorship Representative.php' class='back_button'>Go back</a></h2><br>";
	$SectorHeadBackButton = "<h2><a href='Sector Head.php' class='back_button'>Go back</a></h2><br>";
	$CSOBackButton = "<h2><a href='CSO.php' class='back_button'>Go back</a></h2><br>";


	$SponsAccessLevel = get_access_level($SponsID);
	$SponsName = get_person_name($SponsID);

	$SponsSector = "";
	if ($SponsAccessLevel != "CSO") $SponsSector = get_person_sector($SponsID);


	$query_type = $_SESSION['query_type'];
	$table_name = $_SESSION['table_name'];


	echo '<header align="center">' . '
			<h1>Sponsorship Department</h1>';


	if ($SponsAccessLevel == "SectorHead") echo $SectorHeadBackButton;

	if ($SponsAccessLevel == "SponsRep") echo $SponsRepBackButton;

	if ($SponsAccessLevel == "CSO") echo $CSOBackButton;


	echo '</header>';

	echo '<h3 class="SponsID">';
	echo 'SponsID: ' . $SponsID;
	echo '<br>Name: ' . get_person_name($SponsID);

	$role = get_access_level($SponsID);
	$printing_role = $role;
	if ($role == 'SponsRep') $printing_role = "Sponsorship Representative";
	if ($role == 'SectorHead') $printing_role = "Sector Head";
	if ($role == 'CSO') $printing_role = "Chief Sponsorship Officer";
	echo '<br>Role: ' . $printing_role;

	if (get_access_level($SponsID) == "SponsRep" || get_access_level($SponsID) == "SectorHead"){
		echo '<br>Sector: ' . get_person_sector($SponsID);
	}
	echo '</h3>';


	echo '<div align="center">';


	$meeting_view_query = "SELECT
		SponsOfficer.SponsID as 'SponsID',
		Name as 'SponsRep Name',
		CMPName as 'Company Name',
		CEName as 'Company Executive Name',
		MeetingType as 'Meeting Type',
		Date,
		Time,
		Address,
		Outcome

		FROM ((Select SponsID, Sector from SponsRep) UNION (Select SponsID, Sector from SectorHead)) as SponsOfficer
		natural join Meeting
		inner join CommitteeMember on CommitteeMember.ID = SponsOfficer.SponsID
		and Sector='$SponsSector';
		";    //meeting_view_query is common for both SponsRep and SectorHead

	$CSOmeeting_view_query = "SELECT Name AS 'SponsRep Name',Sector, CMPName AS 'Company Name', CEName AS 'Company Executive Name', MeetingType AS 'Meeting Type', Date, Time, Address, Outcome

				FROM ((SELECT SponsID, Sector FROM SponsRep) UNION (SELECT SponsID, Sector FROM SectorHead)) AS SponsOfficer
				NATURAL JOIN Meeting
				INNER JOIN CommitteeMember ON SponsOfficer.SponsID=CommitteeMember.ID
                WHERE Meeting.SponsID=CommitteeMember.ID AND SponsOfficer.SponsID=CommitteeMember.ID
				";


	$Company_view_query = "SELECT CMPName as 'Company Name', CMPStatus as 'Status', CMPAddress as 'Company Address'
		FROM Company 
		WHERE Sector = '$SponsSector'"; //Company_view_query is common for both SponsRep and SectorHead

	$CSOCompany_view_query = "SELECT CMPName AS 'Company Name', Sector, CMPStatus AS 'Status', CMPAddress AS 'Company Address'
				FROM Company";

	$CSOCompanyExec_view_query = "SELECT  CMPName AS 'Company Name', Sector, CEName AS 'Executive Name', CEMobile AS 'Mobile', CEEmail AS 'Email', CEPosition AS 'Position in Company'
				FROM Company NATURAL JOIN CompanyExec";


	$CompanyExec_view_query = "SELECT  CMPName as 'Company Name', CEName as 'Executive Name', CEMobile as 'Mobile', CEEmail as 'Email', CEPosition as 'Position in Company'
		
		from Company natural join CompanyExec 
		
		where sector = '$SponsSector' "; //CompanyExec_view_query is common for both SponsRep and SectorHead


	$EventAccount_SponsRep_view_query = "SELECT
		AccountLog.ID as 'Deposit ID',
		Event.Organization as 'Organization',
		Event.EventName as 'Event Name', 
		SponsRep.SponsID as 'SponsID of reciever', 
		CommitteeMember.Name as 'Name of reciever', 
		SponsRep.Sector as 'Sector of reciever',
		AccountLog.Title as 'Sponsor Name', 
		AccountLog.Amount as 'Amount (Rs.)',
		AccountLog.Date as 'Date' 

	FROM
		AccountLog inner join CommitteeMember on AccountLog.SponsId = CommitteeMember.ID
		inner join Event on (Event.EventName = AccountLog.EventName and Event.Organization = AccountLog.Organization)
		inner join SponsRep on AccountLog.SponsID = SponsRep.SponsID

	WHERE 
		CommitteeMember.ID=$SponsID;";


	$EventAccount_SectorHead_view_query = "SELECT
		AccountLog.ID as 'Deposit ID',
		Event.Organization as 'Organization',
		Event.EventName as 'Event Name', 
		SponsRep.SponsID as 'SponsID of reciever', 
		CommitteeMember.Name as 'Name of reciever', 
		SponsRep.Sector as 'Sector of reciever',
		AccountLog.Title as 'Sponsor Name', 
		AccountLog.Amount as 'Amount (Rs.)',
		AccountLog.Date as 'Date' 

	FROM
		AccountLog inner join CommitteeMember on AccountLog.SponsId = CommitteeMember.ID
		inner join Event on (Event.EventName = AccountLog.EventName and Event.Organization = AccountLog.Organization)
		inner join SponsRep on AccountLog.SponsID = SponsRep.SponsID

	WHERE 
		SponsRep.Sector='$SponsSector';";


	$EventAccount_CSO_view_query = "SELECT
		AccountLog.ID AS 'Deposit ID',
		Event.Organization AS 'Organization',
		Event.EventName AS 'Event Name',
		SponsRep.SponsID AS 'SponsID of reciever',
		CommitteeMember.Name AS 'Name of reciever',
		SponsRep.Sector AS 'Sector of reciever',
		AccountLog.Title AS 'Sponsor Name',
		AccountLog.Amount AS 'Amount (Rs.)',
		AccountLog.Date AS 'Date'

	FROM
		AccountLog INNER JOIN CommitteeMember ON AccountLog.SponsId = CommitteeMember.ID
		INNER JOIN Event ON (Event.EventName = AccountLog.EventName AND Event.Organization = AccountLog.Organization)
		INNER JOIN SponsRep ON AccountLog.SponsID = SponsRep.SponsID
	;";


	$SponsRep_view_query = "SELECT SponsID, Name, DateAssigned, Mobile, Email
	from SponsRep, CommitteeMember 
	where CommitteeMember.ID=SponsRep.SponsID and Sector = '$SponsSector'";


	$CSOSponsRep_view_query = "SELECT SponsID, Organization, EventName, Name, Sector, DateAssigned, Mobile, Email
					FROM SponsRep, CommitteeMember
					WHERE CommitteeMember.ID=SponsID ";

	$CSOSectorHead_view_query = "SELECT SponsID, Organization, EventName, Name, Sector AS `Head of:`, Mobile, Email
					FROM SectorHead, CommitteeMember
					WHERE CommitteeMember.ID=SponsID ";


	$result = "";
	$main_query = "";
	$table_message = "";

	echo "<h3 class=\"query_status\">";
	if ($table_name == "Meeting Log"){
		if ($SponsAccessLevel != "CSO"){


			if (isset($_POST['submit'])){
				$required = array('Date', 'Time', 'CMPName', 'CEName'); //also require SponsID, but we get that from php

				foreach ($required as $field){
					if (empty($_POST[$field])){
						exit($FieldEmptyMessage);
					}
				}


				$MeetDate = $_POST['Date'];
				$MeetTime = $_POST['Time'];
				$MeetCMPName = $_POST['CMPName'];
				$MeetCEName = $_POST['CEName'];
				$MeetOutcome = "";


				if ($query_type == "Insert"){//outcome is by default (Update after Meeting)

					$MeetType = NULL;
					$MeetAddress = NULL;
					if (!empty($_POST['MeetingType'])) $MeetType = $_POST['MeetingType'];
					if (!empty($_POST['Address'])) $MeetAddress = $_POST['Address'];

					$query = "INSERT INTO `Meeting` (`Date`, `Time`, `SponsID`, `MeetingType`, `CEName`, `CMPName`, `Outcome`, `Address`)
							VALUES ('$MeetDate', '$MeetTime', $SponsID, '$MeetType', '$MeetCEName', '$MeetCMPName', 
								'(Update after meeting)', '$MeetAddress');";
					if (mysql_query($query)){
						echo "Insertion successful";
					}
					else echo "Insertion not successful";
				}
				else{
					if ($query_type == "Update"){
						if (!empty($_POST['Outcome'])){
							$MeetOutcome = $_POST['Outcome'];
						}
						else exit($FieldEmptyMessage);
						$meeting_update_query = "UPDATE Meeting
						SET Outcome='$MeetOutcome'
						WHERE Date='$MeetDate' and Time='$MeetTime' and CMPName='$MeetCMPName' and CEName='$MeetCEName';";
						if (mysql_query($meeting_update_query)){
							echo "Meeting Update successful";
						}
						else echo "Meeting Update not successful";

					}
					else{
						if ($query_type = "Delete"){


							$query = "DELETE FROM Meeting WHERE CMPName = '$MeetCMPName' and SponsID='$SponsID' and CEname = '$MeetCEName' and Date = '$MeetDate' and Time='$MeetTime'";
							if (mysql_query($query)) ;
							// 	echo "Meeting Delete successful";
							// else echo"Meeting Delete not successful";


						}
					}
				}

			}
			$table_message = "<h2>Meetings in " . $SponsSector . " sector:</h2>";
			echo $table_message;
			$result = mysql_query($meeting_view_query);

			$main_query = $meeting_view_query;
		}
		else{
			if (isset($_POST['submit'])){
				$required = array('Date', 'Time', 'CMPName', 'CEName'); //also require SponsID, but we get that from php

				foreach ($required as $field){
					if (empty($_POST[$field])){
						exit($FieldEmptyMessage);
					}
				}


				$MeetDate = $_POST['Date'];
				$MeetTime = $_POST['Time'];
				$MeetCMPName = $_POST['CMPName'];
				$MeetCEName = $_POST['CEName'];
				$MeetOutcome = "";
				$SponsIDForm = $_POST['SponsID'];

				if ($query_type == "Insert"){//outcome is by default (Update after Meeting)

					$MeetType = NULL;
					$MeetAddress = NULL;
					if (!empty($_POST['MeetingType'])) $MeetType = $_POST['MeetingType'];
					if (!empty($_POST['Address'])) $MeetAddress = $_POST['Address'];

					$query = "INSERT INTO `Meeting` (`Date`, `Time`, `SponsID`, `MeetingType`, `CEName`, `CMPName`, `Outcome`, `Address`)
								VALUES ('$MeetDate', '$MeetTime', $SponsIDForm, '$MeetType', '$MeetCEName', '$MeetCMPName', 
									'(Update after meeting)', '$MeetAddress');";
					if (mysql_query($query)){
						echo "Insertion successful";
					}
					else echo "Insertion not successful";
				}
				else{
					if ($query_type == "Update"){
						if (!empty($_POST['Outcome'])){
							$MeetOutcome = $_POST['Outcome'];
						}
						else exit($FieldEmptyMessage);
						$meeting_update_query = "UPDATE Meeting
							SET Outcome='$MeetOutcome'
							WHERE Date='$MeetDate' and Time='$MeetTime' and CMPName='$MeetCMPName' and CEName='$MeetCEName';";
						if (mysql_query($meeting_update_query)){
							echo "Meeting Update successful";
						}
						else echo "Meeting Update not successful";

					}
					else{
						if ($query_type = "Delete"){

							$query = "DELETE FROM Meeting WHERE CMPName = '$MeetCMPName' and SponsID='$SponsIDForm' and CEname = '$MeetCEName' and Date = '$MeetDate' and Time='$MeetTime'";
							if (mysql_query($query)) ;
							//echo "Meeting Delete successful";
							//else echo"Meeting Delete not successful";


						}
					}
				}

			}
			$table_message = "<h2>Meetings Log of all meetings:</h2>";
			echo $table_message;
			$result = mysql_query($CSOmeeting_view_query);

			$main_query = $CSOmeeting_view_query;
		}

	}
	else{
		if ($table_name == "Company"){
			if ($SponsAccessLevel != "CSO"){
				if (isset($_POST['submit'])){

					$required = array('CMPName');

					foreach ($required as $field){
						if (empty($_POST[$field])){
							echo $field;
							exit($FieldEmptyMessage);
						}
					}


					$CMPName = $_POST['CMPName'];
					$CMPAddress = "null";

					if ($query_type == "Insert"){

						if (!empty($_POST['CMPAddress'])) $CMPAddress = $_POST['CMPAddress'];


						$query = "INSERT INTO `Company` (`CMPName`, `CMPStatus`, `Sector`, `CMPAddress`) VALUES
									('$CMPName', 'Not called', '$SponsSector', '$CMPAddress');";
						if (mysql_query($query)){
							echo "Successfully Inserted into Company table";
						}
						else echo "Insertion Unsuccessful into Company table";
					}
					else{
						if ($query_type == "Update"){
							$CMPAddress = "";
							$CMPStatus = "";
							if (!empty($_POST['CMPAddress'])){
								$CMPAddress = $_POST['CMPAddress'];
								if (mysql_query(
									"UPDATE Company SET CMPAddress='$CMPAddress' WHERE CMPName='$CMPName'"
								)){
									echo "Company address update successful";
								}
								else echo "Company address update not successful";
							}

							if (!empty($_POST['CMPStatus'])){
								$CMPStatus = $_POST['CMPStatus'];
								if (mysql_query(
									"UPDATE Company SET CMPStatus='$CMPStatus' WHERE CMPName='$CMPName'"
								)){
									echo "Company status update successful";
								}
								else echo "Company status update not successful";
							}

						}
						else{
							if ($query_type = "Delete"){
								$CMPName = $_POST['CMPName'];

								$query = "DELETE FROM Company WHERE CMPName = '$CMPName' and Sector = '$SponsSector'";
								if (mysql_query($query)) ;
								// 	echo "Company Deletion successful";
								// else echo"Company Deletion not successful";
							}
						}
					}
				}

				$table_message = "<h2>Companies of " . $SponsSector . " sector:</h2>";
				echo $table_message;
				$result = mysql_query($Company_view_query);

				$main_query = $Company_view_query;
			}
			else{

				if (isset($_POST['submit'])){

					$required = array('CMPName');

					foreach ($required as $field){
						if (empty($_POST[$field])){
							echo $field;
							exit($FieldEmptyMessage);
						}
					}


					$CMPName = $_POST['CMPName'];
					$CMPAddress = "null";
					if ($query_type == "Insert"){

						if (!empty($_POST['CMPAddress'])) $CMPAddress = $_POST['CMPAddress'];


						$query = "INSERT INTO `Company` (`CMPName`, `CMPStatus`, `Sector`, `CMPAddress`) VALUES
									('$CMPName', 'Not called', '$SponsSector', '$CMPAddress');";
						if (mysql_query($query)){
							echo "Successfully Inserted into Company table";
						}
						else echo "Insertion Unsuccessful into Company table";
					}
					else{
						if ($query_type == "Update"){
							$CMPAddress = "";
							$CMPStatus = "";
							if (!empty($_POST['CMPAddress'])){
								$CMPAddress = $_POST['CMPAddress'];
								if (mysql_query(
									"UPDATE Company SET CMPAddress='$CMPAddress' WHERE CMPName='$CMPName'"
								)){
									echo "Company address update successful";
								}
								else echo "Company address update not successful";
							}

							if (!empty($_POST['CMPStatus'])){
								$CMPStatus = $_POST['CMPStatus'];
								if (mysql_query(
									"UPDATE Company SET CMPStatus='$CMPStatus' WHERE CMPName='$CMPName'"
								)){
									echo "Company status update successful";
								}
								else echo "Company status update not successful";
							}

						}
						else{
							if ($query_type = "Delete"){
								$CMPName = $_POST['CMPName'];

								$query = "DELETE FROM Company WHERE CMPName = '$CMPName' and Sector = '$SponsSector'";
								if (mysql_query($query)) ;
								//echo "Company Deletion successful";
								//else echo"Company Deletion not successful";
							}
						}
					}
				}


				$table_message = "<h2>All companies in database:</h2>";
				echo $table_message;
				$result = mysql_query($CSOCompany_view_query);

				$main_query = $CSOCompany_view_query;

			}
		}
		else{
			if ($table_name == "Company Executive"){
				if ($SponsAccessLevel != "CSO"){
					if (isset($_POST['submit'])){

						$required = array('CEName', 'CMPName');

						foreach ($required as $field){
							if (empty($_POST[$field])){
								exit($FieldEmptyMessage);
							}
						}


						$CEName = $_POST['CEName'];
						$CMPName = $_POST['CMPName'];
						$CEMobile = "";
						$CEEmail = "";
						$CEPosition = "";


						if ($query_type == "Insert"){
							if (!empty($_POST['CEMobile'])) $CEMobile = $_POST['CEMobile'];
							if (!empty($_POST['CEEmail'])) $CEEmail = $_POST['CEEmail'];
							if (!empty($_POST['CEPosition'])) $CEPosition = $_POST['CEPosition'];
							$CE_insert_query = "INSERT INTO `CompanyExec` (`CEName`,`CMPName`, `CEMobile`, `CEEmail`, `CEPosition`) VALUES
										('$CEName', '$CMPName', '$CEMobile', '$CEEmail','$CEPosition');";
							if (mysql_query($CE_insert_query)){
								echo "Successfully Inserted into CompanyExec";
							}
							else echo "Insertion Unsuccessful into Company Exec";

						}
						else{
							if ($query_type == "Update"){
								if (!empty($_POST['CEEmail'])){
									$CEEmail = $_POST['CEEmail'];
									if (mysql_query(
										"UPDATE CompanyExec SET CEEmail='$CEEmail' where  CMPName='$CMPName' and CEName='$CEName'"
									)){
										echo "Company EXEC Email update successful";
									}
									else echo "Company EXEC Email update not successful";
								}

								if (!empty($_POST['CEMobile'])){
									$CEMobile = $_POST['CEMobile'];
									if (mysql_query(
										"UPDATE CompanyExec SET CEMobile='$CEMobile' where  CMPName='$CMPName' and CEName='$CEName'"
									)){
										echo "Company EXEC Mobile update successful";
									}
									else echo "Company EXEC Mobile update not successful";
								}


								if (!empty($_POST['CEPosition'])){
									$CEPosition = $_POST['CEPosition'];
									if (mysql_query(
										"UPDATE CompanyExec SET CEPosition='$CEPosition' where  CMPName='$CMPName' and CEName='$CEName'"
									)){
										echo "Company EXEC Position update successful";
									}
									else echo "Company EXEC Position update not successful";
								}

							}
							else{
								if ($query_type = "Delete"){

									$query = "DELETE FROM CompanyExec WHERE CMPName = '$CMPName' and CEName = '$CEName' ";
									if (mysql_query($query)) ;
									// 	echo "Query successful";
									// else echo"Query not successful";
								}
							}
						}

					}

					$table_message = "<h2>Company Executives of " . $SponsSector . " sector:</h2>";
					echo $table_message;
					$result = mysql_query($CompanyExec_view_query);

					$main_query = $CompanyExec_view_query;
				}
				else{
					if (isset($_POST['submit'])){

						$required = array('CEName', 'CMPName');

						foreach ($required as $field){
							if (empty($_POST[$field])){
								exit($FieldEmptyMessage);
							}
						}


						$CEName = $_POST['CEName'];
						$CMPName = $_POST['CMPName'];
						$CEMobile = "";
						$CEEmail = "";
						$CEPosition = "";


						if ($query_type == "Insert"){
							if (!empty($_POST['CEMobile'])) $CEMobile = $_POST['CEMobile'];
							if (!empty($_POST['CEEmail'])) $CEEmail = $_POST['CEEmail'];
							if (!empty($_POST['CEPosition'])) $CEPosition = $_POST['CEPosition'];
							$CE_insert_query = "INSERT INTO `CompanyExec` (`CEName`,`CMPName`, `CEMobile`, `CEEmail`, `CEPosition`) VALUES
										('$CEName', '$CMPName', '$CEMobile', '$CEEmail','$CEPosition');";
							if (mysql_query($CE_insert_query)){
								echo "Successfully Inserted into CompanyExec";
							}
							else echo "Insertion Unsuccessful into Company Exec";

						}
						else{
							if ($query_type == "Update"){
								if (!empty($_POST['CEEmail'])){
									$CEEmail = $_POST['CEEmail'];
									if (mysql_query(
										"UPDATE CompanyExec SET CEEmail='$CEEmail' where  CMPName='$CMPName' and CEName='$CEName'"
									)){
										echo "Company EXEC Email update successful";
									}
									else echo "Company EXEC Email update not successful";
								}

								if (!empty($_POST['CEMobile'])){
									$CEMobile = $_POST['CEMobile'];
									if (mysql_query(
										"UPDATE CompanyExec SET CEMobile='$CEMobile' where  CMPName='$CMPName' and CEName='$CEName'"
									)){
										echo "Company EXEC Mobile update successful";
									}
									else echo "Company EXEC Mobile update not successful";
								}


								if (!empty($_POST['CEPosition'])){
									$CEPosition = $_POST['CEPosition'];
									if (mysql_query(
										"UPDATE CompanyExec SET CEPosition='$CEPosition' where  CMPName='$CMPName' and CEName='$CEName'"
									)){
										echo "Company EXEC Position update successful";
									}
									else echo "Company EXEC Position update not successful";
								}

							}
							else{
								if ($query_type = "Delete"){

									$query = "DELETE FROM CompanyExec WHERE CMPName = '$CMPName' and CEName = '$CEName' ";
									if (mysql_query($query)) ;
									// 	echo "Query successful";
									// else echo"Query not successful";
								}
							}
						}

					}

					$table_message = "<h2>List of all Company Executives:</h2>";
					echo $table_message;
					$result = mysql_query($CSOCompanyExec_view_query);

					$main_query = $CSOCompanyExec_view_query;

				}

			}
			else{
				if ($table_name == "Event Account"){
					if ($SponsAccessLevel != "CSO"){
						if ($SponsAccessLevel == "SponsRep"){
							if (isset($_POST['submit'])){

								$required = array('Title', 'Amount', 'Date');

								foreach ($required as $field){
									if (empty($_POST[$field])){
										exit($FieldEmptyMessage);
									}
								}


								$AccountTitle = $_POST['Title'];
								$AccountDate = $_POST['Date'];
								$AccountAmount = $_POST['Amount'];

								if ($query_type == "Insert"){
									$query = "INSERT INTO `AccountLog` (`Title`,`SponsID`, `Amount`, `TransType`, `Date`) VALUES
										('$AccountTitle', '$SponsID', '$AccountAmount', 'Deposit','$AccountDate');";
									if (mysql_query($query)){
										echo "Successfully inserted account entry";
									}
									else echo "Unsuccessful account entry insertion";

								}

							}

							$table_message = "<h2>Account of " . $SponsName . ":</h2>";
							echo $table_message;
							$result = mysql_query($EventAccount_SponsRep_view_query);

							$main_query = $EventAccount_SponsRep_view_query;
						}
						else{
							if ($SponsAccessLevel == "SectorHead"){
								if (isset($_POST['submit'])){

									if ($query_type == "Insert"){

										$required = array('Title', 'Amount', 'Date');

										foreach ($required as $field){
											if (empty($_POST[$field])){
												exit($FieldEmptyMessage);
											}
										}


										$AccountTitle = $_POST['Title'];
										$AccountDate = $_POST['Date'];
										$AccountAmount = $_POST['Amount'];

										$query = "INSERT INTO `AccountLog` (`Title`,`SponsID`, `Amount`, `TransType`, `Date`) VALUES
										('$AccountTitle', '$SponsID', '$AccountAmount', 'Deposit','$AccountDate');";
										if (mysql_query($query)){
											echo "Successfully inserted account entry";
										}
										else echo "Unsuccessful account entry insertion";

									}
									else{
										if ($query_type == "Delete"){
											$required = array('Title', 'SponsID');

											foreach ($required as $field){
												if (empty($_POST[$field])){
													exit($FieldEmptyMessage);
												}
											}

											$AccountTitle = $_POST['Title'];
											$SponsIDForm = $_POST['SponsID'];

											$query = "DELETE FROM AccountLog WHERE Title = '$AccountTitle' and SponsID = '$SponsIDForm' ";
											if (mysql_query($query)) ;
											// 	echo "Account entry deletion successful";
											// else echo"Account entry deletion not successful";
										}
									}
								}

								$table_message = "<h2>Account of " . $SponsSector . " sector:</h2>";
								echo $table_message;
								$result = mysql_query($EventAccount_SectorHead_view_query);

								$main_query = $EventAccount_SectorHead_view_query;

							}
						}
					}
					else{
						if (isset($_POST['submit'])){

							if ($query_type == "Insert"){

								$required = array('Title', 'Amount', 'Date');

								foreach ($required as $field){
									if (empty($_POST[$field])){
										exit($FieldEmptyMessage);
									}
								}


								$AccountTitle = $_POST['Title'];
								$AccountDate = $_POST['Date'];
								$AccountAmount = $_POST['Amount'];

								$query = "INSERT INTO `AccountLog` (`Title`,`SponsID`, `Amount`, `TransType`, `Date`) VALUES
											('$AccountTitle', '$SponsID', '$AccountAmount', 'Deposit','$AccountDate');";
								if (mysql_query($query)){
									echo "Successfully inserted account entry";
								}
								else echo "Unsuccessful account entry insertion";

							}
							else{
								if ($query_type == "Delete"){
									$required = array('Title', 'SponsID');

									foreach ($required as $field){
										if (empty($_POST[$field])){
											exit($FieldEmptyMessage);
										}
									}

									$AccountTitle = $_POST['Title'];
									$SponsIDForm = $_POST['SponsID'];

									$query = "DELETE FROM AccountLog WHERE Title = '$AccountTitle' and SponsID = '$SponsIDForm' ";
									if (mysql_query($query)) ;
									// 	echo "Account entry deletion successful";
									// else echo"Account entry deletion not successful";
								}
							}
						}

						$table_message = "<h2>List of all entries in Account Log:</h2>";
						echo $table_message;
						$result = mysql_query($EventAccount_CSO_view_query);

						$main_query = $EventAccount_CSO_view_query;
					}

				}
				else{
					if ($table_name == "Sponsorship Representative"){
						if ($SponsAccessLevel == "SectorHead"){

							if (isset($_POST['submit'])){

								$required = array('SponsID');

								foreach ($required as $field){
									if (empty($_POST[$field])){
										exit($FieldEmptyMessage);
									}
								}

								$SponsIDForm = $_POST['SponsID'];

								/*


								if($query_type=="Insert"){

									$query = "INSERT INTO `SponsRep` (`SponsID`,`Sector`, DateAssigned) VALUES
													('$SponsIDForm', '$SponsSector', CURDATE());";
										if(mysql_query($query ))
											echo "Successfully added SponsRep";
										else echo"Insertion of SponsRep Unsuccessful";
								}*/


								if ($query_type == "Update"){
									$required = array('Sector');

									foreach ($required as $field){
										if (empty($_POST[$field])){
											exit($FieldEmptyMessage);
										}
									}
									$SponsSectorForm = $_POST['Sector'];


									if (!mysql_query(
										"UPDATE SponsRep SET Sector='$SponsSectorForm', DateAssigned=CURDATE() where SponsID='$SponsIDForm' and Sector='$SponsSector'"
									)
									) // 	echo "SponsRep update successful";
									{
										echo $UnauthorizedMessage;
									}

								}
								else{
									if ($query_type = "Delete"){
										if (!mysql_query(
											"DELETE FROM SponsRep WHERE SponsID = '$SponsIDForm' and Sector = '$SponsSector'"
										)
										) // 	echo "Successfully Deleted SponsRep";
										{
											echo $UnauthorizedMessage;
										}

									}
								}

							}


							$table_message = "<h2>Details of SponsReps from " . $SponsSector . " sector:</h2>";
							echo $table_message;
							$result = mysql_query($SponsRep_view_query);

							$main_query = $SponsRep_view_query;
						}
						else{
							if ($SponsAccessLevel == "CSO"){

								if (isset($_POST['submit'])){

									if ($query_type == "Insert"){

										$required = array('SponsIDForm', 'SponsName', 'SponsPasswordForm', 'SponsSectorForm');

										foreach ($required as $field){
											if (empty($_POST[$field])){
												exit($FieldEmptyMessage);
											}
										}

										$SponsIDForm = $_POST['SponsIDForm'];
										$SponsName = $_POST['SponsName'];
										$SponsPasswordForm = md5($_POST['SponsPasswordForm']);
										$SponsSectorForm = $_POST['SponsSectorForm'];
										$SponsEmail = "";
										$SponsMobile = "";
										$SponsYear = "";
										$SponsBranch = "";
										$SponsOrganization = NULL;
										$SponsEventName = NULL;

										if (!empty($_POST['Email'])) $SponsEmail = $_POST['Email'];
										if (!empty($_POST['Mobile'])) $SponsMobile = $_POST['Mobile'];
										if (!empty($_POST['Year'])) $SponsYear = $_POST['Year'];
										if (!empty($_POST['Branch'])) $SponsBranch = $_POST['Branch'];
										if (!empty($_POST['Organization'])) $SponsOrganization = $_POST['Organization'];
										if (!empty($_POST['EventName'])) $SponsEventName = $_POST['EventName'];

										mysql_query("START TRANSACTION");

										$query = "INSERT INTO `CommitteeMember` (`ID`,`Name`,`Department`,`Role`,`Mobile`,`Email`,`Year`,`Branch`, `Organization`, `EventName`)
						VALUES ($SponsIDForm, '$SponsName', 'Sponsorship', 'SponsRep', '$SponsMobile', '$SponsEmail', '$SponsYear', '$SponsBranch',";

										if ($SponsOrganization == NULL){
											$query .= "NULL,";
										}
										else $query .= "'$SponsOrganization',";

										if ($SponsEventName == NULL){
											$query .= "NULL";
										}
										else $query .= "'$SponsEventName'";
										$query .= ");";

										// echo $query;

										if (mysql_query($query)){
											$query = "INSERT INTO `SponsRep` (`SponsID`,`Sector`, `DateAssigned`) VALUES
											($SponsIDForm, '$SponsSectorForm', CURDATE());";
											// echo $query;
											if (mysql_query($query)){
												$query = "INSERT INTO `SponsLogin` (`SponsID`,`Password`, `AccessLevel`) VALUES
											($SponsIDForm, '$SponsPasswordForm', 'SponsRep');";
												// echo $query;
												if (mysql_query($query)){
													echo "Successfully added Sponsorship Representative";
												}
												else{
													echo "Could not add Sponsorship Representative";
													mysql_query("ROLLBACK");
												}
											}
											else{
												echo "Could not add Sponsorship Representative";
												mysql_query("ROLLBACK");
											}
										}
										else{
											echo "Could not add Sponsorship Representative";
											mysql_query("ROLLBACK");
										}
										mysql_query("COMMIT");

									}
									else{
										if ($query_type == "Update"){
											$required = array('SponsIDForm');

											foreach ($required as $field){
												if (empty($_POST[$field])){
													exit($FieldEmptyMessage);
												}
											}
											$SponsIDForm = $_POST['SponsIDForm'];
											$SponsSectorForm = "";
											$SponsPasswordForm = "";
											$SponsOrganization = "";
											$SponsEventName = "";

											mysql_query("START TRANSACTION");
											$valid = true;

											if (!empty($_POST['SponsSectorForm'])){
												$SponsSectorForm = $_POST['SponsSectorForm'];
												$query = "UPDATE SponsRep SET Sector='$SponsSectorForm' where SponsID='$SponsIDForm' ";
												// echo $query;
												if (!mysql_query($query)){
													$valid = false;
													mysql_query("ROLLBACK");
												}
											}

											if (!empty($_POST['SponsPasswordForm'])){
												$SponsPasswordForm = md5($_POST['SponsPasswordForm']);
												$query = "UPDATE SponsLogin SET Password='$SponsPasswordForm' where SponsID='$SponsIDForm' ";
												// echo $query;
												if (!mysql_query($query)){
													$valid = false;
													mysql_query("ROLLBACK");
												}

											}

											if (!empty($_POST['Organization'])){
												$SponsOrganization = $_POST['Organization'];
												$query = "UPDATE CommitteeMember SET Organization='$SponsOrganization' where ID='$SponsIDForm' ";
												// echo $query;
												if (!mysql_query($query)){
													$valid = false;
													mysql_query("ROLLBACK");
												}
											}

											if (!empty($_POST['EventName'])){
												$SponsEventName = $_POST['EventName'];
												$query = "UPDATE CommitteeMember SET EventName='$SponsEventName' where ID='$SponsIDForm' ";
												// echo $query;
												if (!mysql_query($query)){
													$valid = false;
													mysql_query("ROLLBACK");
												}
											}

											if ($valid == false){
												echo "Could not update Sponsorship Representative details";
											}
											else{
												$query = "UPDATE SponsRep SET DateAssigned=CURDATE() where SponsID='$SponsIDForm' ";
												// echo $query;
												if (!mysql_query($query)){
													echo "Could not update Sponsorship Representative details";

													mysql_query("ROLLBACK");
												}
												else{
													echo "Sponsorship Representative details updated successfully";
													mysql_query("COMMIT");
												}
											}

											//echo "SponsRep update successful";
											//echo $UnauthorizedMessage;


										}
										else{
											if ($query_type = "Delete"){
												$required = array('SponsIDForm');

												foreach ($required as $field){
													if (empty($_POST[$field])){
														exit($FieldEmptyMessage);
													}
												}
												$SponsIDForm = $_POST['SponsIDForm'];
												if (mysql_query(
													"DELETE FROM SponsRep WHERE SponsID = '$SponsIDForm'"
												)){
													;
												}
												//{echo "Successfully Deleted SponsRep";}
												//echo $UnauthorizedMessage;

											}
										}
									}
								}

								$table_message = "<h2>Details of all Sponsorship Representatives:</h2>";
								echo $table_message;
								$result = mysql_query($CSOSponsRep_view_query);

								$main_query = $CSOSponsRep_view_query;

							}
						}

					}
					else{
						if ($table_name == "Sector Head"){
							if ($SponsAccessLevel == "CSO"){

								if (isset($_POST['submit'])){


									if ($query_type == "Insert"){

										$required = array('SponsIDForm', 'SponsName', 'SponsPasswordForm', 'SponsSectorForm');

										foreach ($required as $field){
											if (empty($_POST[$field])){
												exit($FieldEmptyMessage);
											}
										}

										$SponsIDForm = $_POST['SponsIDForm'];
										$SponsName = $_POST['SponsName'];
										$SponsPasswordForm = md5($_POST['SponsPasswordForm']);
										$SponsSectorForm = $_POST['SponsSectorForm'];
										$SponsEmail = "";
										$SponsMobile = "";
										$SponsYear = "";
										$SponsBranch = "";
										$SponsOrganization = NULL;
										$SponsEventName = NULL;

										if (!empty($_POST['Email'])) $SponsEmail = $_POST['Email'];
										if (!empty($_POST['Mobile'])) $SponsMobile = $_POST['Mobile'];
										if (!empty($_POST['Year'])) $SponsYear = $_POST['Year'];
										if (!empty($_POST['Branch'])) $SponsBranch = $_POST['Branch'];
										if (!empty($_POST['Organization'])) $SponsOrganization = $_POST['Organization'];
										if (!empty($_POST['EventName'])) $SponsEventName = $_POST['EventName'];


										mysql_query("START TRANSACTION");

										$query = "INSERT INTO `CommitteeMember` (`ID`,`Name`,`Department`,`Role`,`Mobile`,`Email`,`Year`,`Branch`, `Organization`, `EventName`) VALUES ($SponsIDForm, '$SponsName', 'Sponsorship', 'SectorHead', '$SponsMobile', '$SponsEmail', '$SponsYear', '$SponsBranch',";
										if ($SponsOrganization == NULL){
											$query .= "NULL,";
										}
										else $query .= "'$SponsOrganization',";
										if ($SponsEventName == NULL){
											$query .= "NULL";
										}
										else $query .= "'$SponsEventName'";
										$query .= ");";

										// echo $query;
										if (mysql_query($query)){

											$query = "INSERT INTO `SectorHead` (`SponsID`,`Sector`, `DateAssigned`) VALUES
										($SponsIDForm, '$SponsSectorForm', CURDATE());";
											// echo $query;
											if (mysql_query($query)){


												$query = "INSERT INTO `SponsLogin` (`SponsID`,`Password`, `AccessLevel`) VALUES
										($SponsIDForm, '$SponsPasswordForm', 'SectorHead');";
												// echo $query;
												if (mysql_query($query)){
													echo "Successfully added SectorHead";
												}
												else{
													echo "Could not add Sector Head";
													mysql_query("ROLLBACK");
												}
											}
											else{
												echo "Could not add Sector Head";
												mysql_query("ROLLBACK");
											}
										}
										else{
											echo "Could not add Sector Head";
											mysql_query("ROLLBACK");
										}
										mysql_query("COMMIT");

									}
									else{
										if ($query_type == "Update"){
											$required = array('SponsIDForm');

											foreach ($required as $field){
												if (empty($_POST[$field])){
													exit($FieldEmptyMessage);
												}
											}
											$SponsIDForm = $_POST['SponsIDForm'];
											$SponsSectorForm = "";
											$SponsPasswordForm = "";
											$SponsOrganization = "";
											$SponsEventName = "";

											mysql_query("START TRANSACTION");
											$valid = true;

											if (!empty($_POST['SponsSectorForm'])){
												$SponsSectorForm = $_POST['SponsSectorForm'];
												$query = "UPDATE SectorHead SET Sector='$SponsSectorForm' where SponsID='$SponsIDForm' ";
												// echo $query;
												if (!mysql_query($query)){
													$valid = false;
													mysql_query("ROLLBACK");
												}
											}

											if (!empty($_POST['SponsPasswordForm'])){
												$SponsPasswordForm = md5($_POST['SponsPasswordForm']);
												$query = "UPDATE SponsLogin SET Password='$SponsPasswordForm' where SponsID='$SponsIDForm' ";
												// echo $query;
												if (!mysql_query($query)){
													$valid = false;
													mysql_query("ROLLBACK");
												}

											}

											if (!empty($_POST['Organization'])){
												$SponsOrganization = $_POST['Organization'];
												$query = "UPDATE CommitteeMember SET Organization='$SponsOrganization' where ID='$SponsIDForm' ";
												// echo $query;
												if (!mysql_query($query)){
													$valid = false;
													mysql_query("ROLLBACK");
												}
											}

											if (!empty($_POST['EventName'])){
												$SponsEventName = $_POST['EventName'];
												$query = "UPDATE CommitteeMember SET EventName='$SponsEventName' where ID='$SponsIDForm' ";
												// echo $query;
												if (!mysql_query($query)){
													$valid = false;
													mysql_query("ROLLBACK");
												}
											}

											if ($valid == false){
												echo "Could not update Sector Head details";
											}
											else{
												$query = "UPDATE SectorHead SET DateAssigned=CURDATE() where SponsID='$SponsIDForm' ";
												// echo $query;
												if (!mysql_query($query)){
													echo "Could not update Sector Head details";
													mysql_query("ROLLBACK");
												}
												else{
													echo "Sector Head details updated successfully";
													mysql_query("COMMIT");
												}
											}
											//echo "SectorHead update successful";
											//echo $UnauthorizedMessage;


										}
										else{
											if ($query_type = "Delete"){
												$required = array('SponsIDForm');

												foreach ($required as $field){
													if (empty($_POST[$field])){
														exit($FieldEmptyMessage);
													}
												}
												$SponsIDForm = $_POST['SponsIDForm'];
												if (mysql_query(
													"DELETE FROM SectorHead WHERE SponsID = '$SponsIDForm'"
												)){
													;
												}
												//{echo "Successfully Deleted SectorHead";}
												//echo $UnauthorizedMessage;

											}
										}
									}
								}

								$table_message = "<h2>Details of all Sector Heads:</h2>";
								echo $table_message;
								$result = mysql_query($CSOSectorHead_view_query);

								$main_query = $CSOSectorHead_view_query;

							}

						}
					}
				}
			}
		}
	}
	echo "</h3>";


	echo '
<script type="text/javascript">

	SponsID=document.getElementsByClassName("SponsID")[0];
	SponsID.hidden=true;

	function printpage() {
		document.getElementById("printButton").style.visibility="hidden";

		sort = document.getElementsByClassName("sort_form")[0];
		sort.hidden=true;

		search = document.getElementsByClassName("search_form")[0];
		search.hidden=true;

		header=document.getElementsByTagName("header")[0];
		header.hidden=true;

		query_status=document.getElementsByClassName("query_status")[0];
		query_status.hidden=true;

		SponsID=document.getElementsByClassName("SponsID")[0];
		SponsID.hidden=false;

		window.print();

		document.getElementById("printButton").style.visibility="visible";  
		sort.hidden=false;
		search.hidden=false;
		header.hidden=false;
		query_status.hidden=false;
		SponsID.hidden=true;
	}
</script>';
	echo '<button name="print" type="button" value="Print" id="printButton" onClick="printpage()">Print</button>';
	print_sort($result);
	print_search($result);

	print_table($result);

	$_SESSION['main_query'] = $main_query;
	$_SESSION['table_message'] = $table_message;

?>
</div>
</body>
</html>