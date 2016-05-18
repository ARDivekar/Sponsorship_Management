<!DOCTYPE html PUBLIC "-/W3C//DTD HTML 4.01 Transitional/EN"
"http://www.w3.org/TR/html4/loose.dtd">


<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	</head>

<body>

<?php 
		
		/*Resume old session:*/
		session_start();

		if(empty($_SESSION['loginID']))
			header("Location: login.php");

		require('DBconnect.php');
		require('library_functions.php');
		$SponsID=$_SESSION['loginID']; //get SponsID from previos session
		

		
		$SponsRepBackButton="<h2><a href='sponsrep.php' class='back_button'>Go back</a></h2><br>";
		$SectorHeadBackButton="<h2><a href='sectorhead.php' class='back_button'>Go back</a></h2><br>";
		$CSOBackButton="<h2><a href='CSO.php' class='back_button'>Go back</a></h2><br>";
		

		$SponsAccessLevel = get_access_level($SponsID);
		$SponsName = get_person_name($SponsID);
		$SponsSector="";
		if($SponsAccessLevel!="CSO")
		{
			$SponsSector = get_person_sector($SponsID);
		}



		if(isset($_POST['submit'])){
			$query_type=$_POST['query_type'];
			$table_name=$_POST['table_name'];
			//echo $query_type;
			//echo $table_name;

		$UnauthorizedMessage = '<div align="center"><h3 align="center" style="padding: 40px; font-size:28px; line-height:50px;"  class="invalid_message">Sorry, you are not permitted to run this query.</h3> </div>';




		echo '<header align="center">
			<h1>Sponsorship Department</h1>';


		if($SponsAccessLevel=="SectorHead")
			echo $SectorHeadBackButton;

		if($SponsAccessLevel=="SponsRep")
			echo $SponsRepBackButton;

		if($SponsAccessLevel == "CSO")
			echo $CSOBackButton;

		echo '</header>';


		$AccountLogInsert="";
		$AccountLogUpdate="";
		$AccountLogDelete="";
		$SponsRepInsert="";
		$SponsRepUpdate="";
		$SponsRepDelete="";
		$MeetingLogInsert="";
		$MeetingLogUpdate="";
		$MeetingLogDelete="";
		$CompanyInsert="";
		$CompanyUpdate="";
		$CompanyDelete="";
		$CompanyExecInsert="";
		$CompanyExecUpdate="";
		$CompanyExecDelete="";


		if($SponsAccessLevel!= "CSO"){
			$AccountLogInsert = '
				<h2 align="center">Insert details of the sponorship recieved:</h2>		

			<div>
				<form action="view_table.php" method="post"  class="Insert">
					<label>Transaction Type:</label>          <input type="text" name="TransType" disabled value="Deposit">
					<br>
					<br>
					<label>Company Name:</label>          <input type="text" name="Title">
					<br>
					<br>
					<label>Sponsorship ID:</label>			  <input type="text" name="SponsID" disabled value="'.$SponsID.'"  >
					<br>
					<br>
					<label>Date:</label>     <input type="date" name="Date">
					<br>
					<br>
					<label>Amount:</label><input type="text" name="Amount">
					<br>
					<br>
					<button class="query_forms" type="submit" name="submit" >Insert Account Entry Details</button>	
					
				</form>
			</div>';


			$AccountLogUpdate='
			<h2 align="center">Update Event Account:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Update">
					<label>Transaction Type:</label>          <input type="text" name="TransType" disabled value="Deposit">
					<br>
					<br>
					<label>Company Name:</label> <input type="text" name="Title">
					<br>
					<br>
				    <label>Sponsorship ID:</label> <input type="text" name="SponsID" disabled value="'.$SponsID.'" >
					<br> 
					<br>
					<!--<input type="checkbox" name="DateCheckbox">--> <label>Date:</label> <input type="date" name="Date">
					<br>
					<br>
					<!--<input type="checkbox" name="AmountCheckbox">--> <label>Amount:</label> <input type="text" name="Amount">
					<br>
				    <br>
				    <button class="query_forms" type="submit" name="submit">Update Account Entry Details</button>
					
				</form>
			</div>';



			$AccountLogDelete='
			
				<h2 align="center">Delete entry from Event Account:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Company Name:</label><input type="text" name="Title">
					<br>
					<br>
					<label>Sponsorship ID:</label> <input type="text" name="SponsID" >
					<br>
					<br>
					<button class="query_forms" type="submit" name="submit">Delete Account Entry</button>
					
				</form>
			</div>
			';



			$CompanyInsert = '<h2 align="center">Add a Company to the '.$SponsSector.' sector:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Company Name:</label> <input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Status:</label> <input type="text" name="CMPStatus" disabled value="Not called">
					<br>
					<br>
					<label>Sector:</label> <input type="text" name="Sector" disabled  value="'.$SponsSector.'">
					<br>
					<br>
					<label>Address:</label> <input type="text"  name="CMPAddress">
					<button class="query_forms" type="submit" name="submit">Insert Company Details</button>
					
				</form>
			</div>';


			$CompanyUpdate = '<h2 align="center">Update Company Details:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Update">
					<label>Company Name:</label> <input type="text" name="CMPName">
					<br>
					<br>
					<label>Sector:</label> <input type="text" name="Sector"  disabled value="'.$SponsSector.'">
					<br>
					<br>
					<!--<input type="checkbox" name="CMPStatusCheckbox">--><label>Status:	</label>		  <input type="text" name="CMPStatus">
					<br>
					<br>
					<!--<input type="checkbox" name="CMPAdressCheckbox">--><label>Address:</label>         <input type="text" max-length="50" name="CMPAddress">
					
					<button class="query_forms" type="submit" name="submit">Update Company Details</button>	
					
				</form>
			</div>';


			$CompanyDelete = '<h2 align="center">Remove a Company and all it\'s associated data from sector '.$SponsSector.':</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Company Name:</label> <input type="text" name="CMPName">
					<br>
					<br>
					<label>Sector:</label> <input type="text" name="Sector" disabled  value="'.$SponsSector.'"> 
					<button class="query_forms" type="submit" name="submit">Delete Company</button>
					
					
				</form>
			</div>';





			$CompanyExecInsert = '<h2 align="center">Add an Executive to a Company in the '.$SponsSector.' sector:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Company Name:</label>
					<input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Executive Name:</label>
					<input type="text" name="CEName">
					<br>
					<br>
					<label>Phone Number:</label>
					<input type="text" name="CEMobile">
					<br>
					<br>
					<label>Email ID:</label>
					<input type="text" name="CEEmail">
					<br>
					<br>
					<label>Company Position:</label>
					<input type="text" name="CEPosition">
					
					<button class="query_forms" type="submit" name="submit">Insert Company and Executive Details</button>
					
				</form>
			</div>';


			$CompanyExecUpdate = '<h2 align="center">Update Details of a Company Executive:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Update">
					<label>Company Name:</label> <input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Executive:</label><input type="text" name="CEName">
					<br>
					<br>
					<!--<input type="checkbox" name="CEMobileCheckbox">--><label>Phone Number:</label>     <input type="text" name="CEMobile">
					<br>
					<br>
					<!--<input type="checkbox" name="CEEmailCheckbox">--><label>Email ID:</label>         <input type="text" name="CEEmail">
					<br>
					<br>
					<!--<input type="checkbox" name="CEPositionCheckbox">--><label>Company Position:</label>     <input type="text" name="CEPosition">
					<br>
					<br>
					<button class="query_forms" type="submit" name="submit">Update Company and Executive Details</button>	
					
				</form>
			</div>';

			$CompanyExecDelete='<h2 align="center">Remove a Company Executive:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Company Name:</label>          <input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Executive Name:</label>    <input type="text" name="CEName">
					<br>
					<br>
					<button class="query_forms" type="submit" name="submit">Delete Company Executive</button>
					
				</form>
			</div>';





			$MeetingLogInsert ='<h2 align="center">Add a Meeting to the log of Sector '.$SponsSector.':</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Sponsorship ID:</label><input type="text" name="SponsID" disabled  value="'.$SponsID.'">
					<br>
					<br>
					<label>Meeting Type:</label> 
					<select name="MeetingType">
						<option>Call</option>
						<option>Meet</option>
						<option>Email</option>
					</select>
		
					<br>
					<br>
					<label>Company Name:</label>           <input type="text" name="CMPName">
					<br>
					<br>
					<label>Executive Name:</label>			  <input type="text" name="CEName">
					<br>
					<br>
					<label>Date:</label>     <input type="date" name="Date">
					<br>
					<br>
					<label>Time:</label><input type="time" name="Time">
					<br>
					<br>
					<label>Address:</label>         <input type="text" name="Address">
					<br>
					<br>
					<label>Outcome:</label>         <input type="text" name="Output" disabled  value="(Update after meeting)" >
					<br>
					<br>
					<button class="query_forms" type="submit" name="submit">Insert Meeting Details</button>
					
				</form>
			</div>';


			$MeetingLogUpdate = '<h2 align="center">update the Outcome of a Meeting:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Update">
					<label>Sponsorship ID:</label><input type="text" name="SponsID"  disabled value="'.$SponsID.'">
					<!--<br>
					<br>
					<label>Meeting Type:</label> 
					<select name="MeetingType">
						<option>Call</option>
						<option>Meet</option>
						<option>Email</option>
					</select>-->
					<br>
					<br>
					<label>Company:</label><input type="text" name="CMPName">
					<br>
					<br>
					<label>Executive Name:</label><input type="text" name="CEName">
					<br>
					<br>
					<label>Date:</label><input type="date" name="Date">
					<br>
					<br>
					<label>Time:</label><input type="time" name="Time">
					<br>
					<br>
					<label>Outcome:</label><input type="text" name="Outcome">
					<br>
					<br>	
						
					<button class="query_forms" type="submit" name="submit">Update Meeting Details</button>	
					
				</form>
			</div>';


			$MeetingLogDelete ='<h2 align="center">Delete a meeting thhat was previously planned:</h2>
				

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Sponsorship ID:</label><input type="text" name="SponsID" disabled  value="'.$SponsID.'">
					<br>
					<br>
					<label>Company Name:</label><input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Executive:</label><input type="text" name="CEName">
					<br>
					<br>
					<label>Date:</label><input type="date" name="Date">
					<br>
					<br>
					<label>Time:</label><input type="time" name="Time">
					<button class="query_forms" type="submit" name="submit">Delete Meeting</button>
					
				</form>
			</div>';


			
			$SponsRepInsert = '
			<h2 align="center">Insert a Sponsorship Representative into any sector:</h2>
			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>SponsID:</label>    <input type="text" name="SponsID">
					<br><br>
					<label>Sector:</label> <input type="text" name="Sector" disabled  value="'.$SponsSector.'"> 
					<br><br>
					<!--<label>Date Assigned:</label>			  <input type="date" name="DateAssigned">
					<br><br>-->
					<button class="query_forms" type="submit" name="submit">Insert SponRep Details</button>
					
				</form>
			</div>';
			


			$SponsRepUpdate ='<h2 align="center">Update the details of a SponsRep in sector '.$SponsSector.':</h2>

			<div>
					<form action="view_table.php" method="post"  class ="Update">
					<label>SponsID:</label><input type="text" name="SponsID">
					<br>
					<br>
 
					<label>Sector:</label><input type="text" name="Sector">
					<br>
					<br>

					<label>Organization:</label>          <input type="text" name="Organization" value="">
					<br>
					<br>
					
					<label>Event Name:</label>          <input type="text" name="EventName" value="">
					<br>
					<br>
					
					<label>Date Assigned:</label> <input type="date" name="DateAssigned">
					<br>
					<br>-->
					<button class="query_forms" type="submit" name="submit">Update SponsRep Details</button>
					
				</form>
			</div>';


			$SponsRepDelete='
				<h2 align="center">Delete Sponsorship Representative</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Sponsorship ID:</label><input type="text" name="SponsID">
					<br>
					<br>
					<label>Sector:</label> <input type="text" name="Sector" disabled  value="'.$SponsSector.'"> 
					<br>
					<br>
					<button class="query_forms" type="submit" name="submit">Delete SponsRep</button>
					
				</form>
			</div>';


		}
		
		if($SponsAccessLevel=="CSO"){


			$CSOAccountLogInsert = '<h2 align="center">Insert details of the sponorship recieved:</h2>		

				<div>
					<form action="view_table.php" method="post"  class="Insert">
						<label>Transaction Type:</label>          <input type="text" name="TransType" disabled value="Deposit">
						<br>
						<br>
						<label>Company Name:</label>          <input type="text" name="Title">
						<br>
						<br>
						<label>Sponsorship ID:</label>			  <input type="text" name="SponsID">
						<br>
						<br>
						<label>Date:</label>     <input type="date" name="Date">
						<br>
						<br>
						<label>Amount:</label><input type="text" name="Amount">
						<br>
						<br>
						<button class="query_forms" type="submit" name="submit" >Insert Account Entry Details</button>	
						
					</form>
				</div>';








				$CSOAccountLogUpdate='<h2 align="center">Update Event Account:</h2>

					<div>
							<form action="view_table.php" method="post"  class="Update">
							<label>Transaction Type:</label>          <input type="text" name="TransType" disabled value="Deposit">
							<br>
							<br>
							<label>Company Name:</label> <input type="text" name="Title">
							<br>
							<br>
							<label>Sponsorship ID:</label> <input type="text" name="SponsID">
							<br> 
							<br>
							<!--<input type="checkbox" name="DateCheckbox">--> <label>Date:</label> <input type="date" name="Date">
							<br>
							<br>
							<!--<input type="checkbox" name="AmountCheckbox">--> <label>Amount:</label> <input type="text" name="Amount">
							<br>
							<br>
							<button class="query_forms" type="submit" name="submit">Update Account Entry Details</button>
							
						</form>
					</div>';





			$CSOAccountLogDelete='
					<h2 align="center">Delete entry from Event Account:</h2>

					<div>
							<form action="view_table.php" method="post"  class="Insert">
							<label>Company Name:</label><input type="text" name="Title">
							<br>
							<br>
							<label>Sponsorship ID:</label> <input type="text" name="SponsID" >
							<br>
							<br>
							<button class="query_forms" type="submit" name="submit">Delete Account Entry</button>
							
						</form>
					</div>
					';

					
					




				$CSOCompanyInsert = '<h2 align="center">Add a Company to the any sector:</h2>

					<div>
							<form action="view_table.php" method="post"  class="Insert">
							<label>Company Name:</label> <input type="text" name="CMPName">
							<br>
							<br>
							<label>Company Status:</label> <input type="text" name="CMPStatus" disabled value="Not called">
							<br>
							<br>
							<label>Sector:</label> <input type="text" name="Sector">
							<br>
							<br>
									<label>Address:</label> <input type="text"  name="CMPAddress">
						<button class="query_forms" type="submit" name="submit">Insert Company Details</button>
						
					</form>
				</div>';





				$CSOCompanyUpdate = '<h2 align="center">Update Company Details:</h2>

				<div>
						<form action="view_table.php" method="post"  class="Update">
						<label>Company Name:</label> <input type="text" name="CMPName">
						<br>
						<br>
						<label>Sector:</label> <input type="text" name="Sector">
						<br>
						<br>
						<!--<input type="checkbox" name="CMPStatusCheckbox">--><label>Status:	</label>		  <input type="text" name="CMPStatus">
						<br>
						<br>
						<!--<input type="checkbox" name="CMPAdressCheckbox">--><label>Address:</label>         <input type="text" max-length="50" name="CMPAddress">
						
						<button class="query_forms" type="submit" name="submit">Update Company Details</button>	
						
					</form>
				</div>';




			$CSOCompanyDelete = '<h2 align="center">Remove a Company and all it\'s associated data:</h2>

				<div>
						<form action="view_table.php" method="post"  class="Insert">
						<label>Company Name:</label> <input type="text" name="CMPName">
						<br>
						<br>
						<label>Sector:</label> <input type="text" name="Sector"> 
						<button class="query_forms" type="submit" name="submit">Delete Company</button>
						
						
					</form>
				</div>';





			$CSOMeetingLogInsert ='<h2 align="center">Add a Meeting to the log of any sector:</h2>

			<div>
				<form action="view_table.php" method="post"  class="Insert">
				<label>Sponsorship ID:</label><input type="text" name="SponsID">
				<br>
				<br>
				<label>Meeting Type:</label> 
				<select name="MeetingType">
					<option>Call</option>
					<option>Meet</option>
					<option>Email</option>
				</select>
	
				<br>
				<br>
				<label>Company Name:</label>           <input type="text" name="CMPName">
				<br>
				<br>
				<label>Executive Name:</label>			  <input type="text" name="CEName">
				<br>
				<br>
				<label>Date:</label>     <input type="date" name="Date">
				<br>
				<br>
				<label>Time:</label><input type="time" name="Time">
				<br>
				<br>
				<label>Address:</label>         <input type="text" name="Address">
				<br>
				<br>
				<label>Outcome:</label>         <input type="text" name="Output" disabled  value="(Update after meeting)" >
				<br>
				<br>
				<button class="query_forms" type="submit" name="submit">Insert Meeting Details</button>
				
			</form>
			</div>';

		




				$CSOMeetingLogUpdate = '<h2 align="center">update the Outcome of a Meeting:</h2>

			<div><h2 align=center>Please check the boxes which you want to update and enter details</h2>
					<form action="view_table.php" method="post"  class="Update">
					<label>Sponsorship ID:</label><input type="text" name="SponsID">
					<!--<br>
					<br>
					<label>Meeting Type:</label> 
					<select name="MeetingType">
						<option>Call</option>
						<option>Meet</option>
						<option>Email</option>
					</select>-->
					<br>
					<br>
					<label>Company:</label><input type="text" name="CMPName">
					<br>
					<br>
					<label>Executive Name:</label><input type="text" name="CEName">
					<br>
					<br>
					<label>Date:</label><input type="date" name="Date">
					<br>
					<br>
					<label>Time:</label><input type="time" name="Time">
					<br>
					<br>
					<label>Outcome:</label><input type="text" name="Outcome">
					<br>
					<br>	
						
					<button class="query_forms" type="submit" name="submit">Update Meeting Details</button>	
					
				</form>
			</div>';

			




				$CSOMeetingLogDelete ='<h2 align="center">Delete a meeting thhat was previously planned:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Sponsorship ID:</label><input type="text" name="SponsID">
					<br>
					<br>
					<label>Company Name:</label><input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Executive:</label><input type="text" name="CEName">
					<br>
					<br>
					<label>Date:</label><input type="date" name="Date">
					<br>
					<br>
					<label>Time:</label><input type="time" name="Time">
					<button class="query_forms" type="submit" name="submit">Delete Meeting</button>
					
				</form>
			</div>';


			$CSOSectorHeadInsert='<h2 align="center">Insert a Sector Head into any sector:</h2>
		<div>
				<form action="view_table.php" method="post"  class="Insert">
				<label>SponsID:</label>       <input type="text" name="SponsIDForm" value="">
				<br>
				<br>
				<label>Name:</label>          <input type="text" name="SponsName" value="">
				<br>
				<br>
				<label>Password:</label>          <input type="password" name="SponsPasswordForm" value="">
				<br>
				<br>
				<label>Organization:</label>          <input type="text" name="Organization" value="">
				<br>
				<br>
				
				<label>Event Name:</label>          <input type="text" name="EventName" value="">
				<br>
				<br>
				<label>Department:</label>          <input type="text" name="Dept" disabled="disabled" value="Sponsorship">
				<br>
				<br>
				<label>Role:</label>          <input type="text" name="Role" disabled="disabled" value="SectorHead">
				
				<br>
				<br>
				<label>Sector:</label>        <input type="text" name="SponsSectorForm" value="">
				<br>	<br>
				<label>Email:</label>         <input type="text" name="Email" max-length="50" value="">
				<br><br>
				<label>Mobile Number:</label> <input type="text" name="Mobile" value="">
				<br><br>
				<label>Year:</label> <input type="text" name="Year" value="">
				<br><br>
				<label>Branch:</label>        <input type="text" name="Branch" value="">
				<br>	
				<button class="query_forms" type="submit" name="submit">Insert SectorHead Details</button>
			</form>
		</div>
			';
			$CSOSectorHeadUpdate='<h2 align="center">Edit Sector Head details:</h2>

			<div>
					<form action="view_table.php" method="post"  class ="Update">
					<label>SponsID:</label><input type="text" name="SponsIDForm">
					<br>
					<br>

					<label>Sector:</label><input type="text" name="SponsSectorForm">
					<br>
					<br>

					<label>Organization:</label>          <input type="text" name="Organization" value="">
					<br>
					<br>
					
					<label>Event Name:</label>          <input type="text" name="EventName" value="">
					<br>
					<br>
					
					<label>Password:</label>          <input type="password" name="SponsPasswordForm" value="">
					<br>
					<br>

					
					<button class="query_forms" type="submit" name="submit">Update SectorHead Details</button>
					
				</form>
			</div>';
			$CSOSectorHeadDelete='<h2 align="center">Delete a Sector Head from any sector:</h2>

				<div>
						<form action="view_table.php" method="post"  class="Delete">
						<label>SponsID:</label><input type="text" name="SponsIDForm">
						<br>
						<br>
						
						<button class="query_forms" type="submit" name="submit">Delete SectorHead</button>
						
					</form>
				</div>';

				$CSOSponsRepInsert = '<h2 align="center">Insert a Sponsorship Representative into any sector:</h2>

					<div>
				<form action="view_table.php" method="post"  class="Insert">
				<label>SponsID:</label>       <input type="text" name="SponsIDForm" value="">
				<br>
				<br>
				<label>Name:</label>          <input type="text" name="SponsName" value="">
				<br>
				<br>
				<label>Password:</label>          <input type="password" name="SponsPasswordForm" value="">
				<br>
				<br>
				<label>Organization:</label>          <input type="text" name="Organization" value="">
				<br>
				<br>
				<label>Event Name:</label>          <input type="text" name="EventName" value="">
				<br>
				<br>
				<label>Department:</label>          <input type="text" name="Dept" disabled="disabled" value="Sponsorship">
				<br>
				<br>

				<label>Role:</label>          <input type="text" name="Role" disabled="disabled" value="SponsRep">
				<br><br>
				<label>Sector:</label>        <input type="text" name="SponsSectorForm" value="">
				<br>
				<br>
				<label>Email:</label>         <input type="text" name="Email" max-length="50" value="">
				<br><br>
				<label>Mobile Number:</label><input type="text" name="Mobile" value="">
				<br><br>
				<label>Year:</label> <input type="text" name="Year" value="">
				<br><br>
				<label>Branch:</label>        <input type="text" name="Branch" value="">
				<br>	
				<button class="query_forms" type="submit" name="submit">Insert SponRep Details</button>
			</form>
		</div>';


				$CSOSponsRepUpdate ='<h2 align="center">Edit Sponsorship Representative details:</h2>

			<div>
					<form action="view_table.php" method="post"  class ="Update">
					<label>SponsID:</label><input type="text" name="SponsIDForm">
					<br>
					<br>
					
					<label>Organization:</label>          <input type="text" name="Organization" value="">
					<br>
					<br>
					
					<label>Event Name:</label>          <input type="text" name="EventName" value="">
					<br>
					<br>
					
					<label>Sector:</label><input type="text" name="SponsSectorForm">
					<br>
					<br>
					
					<label>Password:</label>          <input type="password" name="SponsPasswordForm" value="">
					<br>
					<br>
					
					
					<button class="query_forms" type="submit" name="submit">Update SponsRep Details</button>
					
				</form>
			</div>';

				
				$CSOSponsRepDelete='<h2 align="center">Delete a Sponsorship Representative from any sector:</h2>

				<div>
						<form action="view_table.php" method="post"  class="Delete">
						<label>SponsID:</label><input type="text" name="SponsIDForm">
						<br>
						<br>
						<button class="query_forms" type="submit" name="submit" style="width:150px;">Delete SponsRep</button>
						
					</form>
				</div>';

		}	

	

		/*THE FOLLOWING CODE SELECTS WHICH TABLE SHOULD BE DISPLAYED, 
		BASED ON THE INPUT AND WHO IS RUNNING THE QUERY.
		*/

		/*THIS TABLES ALSO TELLS US WHO HAS WHAT ACCESS RIGHTS (NEEDS TO E UPDATED IN SCHEMA).*/


		$_SESSION["SponsAccessLevel"]=$SponsAccessLevel;
		$_SESSION["query_type"]=$query_type;
		$_SESSION["table_name"]=$table_name;


		if($SponsAccessLevel == "SponsRep"){
			
			
			if($table_name=="Meeting Log"){
				if($query_type == "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $MeetingLogInsert;
				}
				
				else if ($query_type=="Update"){
					echo $MeetingLogUpdate;
				}
				else if($query_type="Delete"){
					echo $MeetingLogDelete;
				}
				
			}

			else if ($table_name=="Company"){
				if($query_type == "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $CompanyInsert;
				}
				else if ($query_type=="Update"){
					echo $CompanyUpdate;
				}
				
				else if($query_type="Delete"){
					echo $CompanyDelete;
				}

			}

			else if ($table_name=="Company Executive"){
				if($query_type== "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $CompanyExecInsert;
				}
				else if ($query_type=="Update"){
					echo $CompanyExecUpdate;
				}
				if($query_type=="Delete"){
					echo $CompanyExecDelete;
				}
			}

			else if ($table_name=="Event Account"){
				if($query_type == "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $AccountLogInsert;
				}
				else exit ($UnauthorizedMessage);	
			}
			
		}





		if ($SponsAccessLevel == "SectorHead"){ 


			if($table_name=="Sponsorship Representative"){
				
				
				if($query_type=="Insert"){
					exit($UnauthorizedMessage);
				}
				
				else if($query_type=="Update"){
					echo $SponsRepUpdate;
				}
				else if($query_type == "View"){
					header("Location: view_table.php");	
				}
				
				else if($query_type="Delete"){
					echo $SponsRepDelete;
				}
				else exit($UnauthorizedMessage);

				
			}

			else if ($table_name=="Event Account"){
				if($query_type == "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $AccountLogInsert;
				}
				else if($query_type=="Delete"){
					echo $AccountLogDelete;
				}
				else exit ($UnauthorizedMessage);	
			}

			else if ($table_name=="Company"){
				if($query_type == "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $CompanyInsert;
				}
				else if ($query_type=="Update"){
					echo $CompanyUpdate;
				}
				else if($query_type=="Delete"){
					echo $CompanyDelete;
				}

			}

			else if ($table_name=="Company Executive"){
				if($query_type == "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $CompanyExecInsert;
				}
				else if ($query_type=="Update"){
					echo $CompanyExecUpdate;
				}
				
				if($query_type=="Delete"){
					echo $CompanyExecDelete;
				}
			}


			else if($table_name=="Meeting Log"){
				if($query_type == "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $MeetingLogInsert;
				}
				
				else if ($query_type=="Update"){
					echo $MeetingLogUpdate;
				}
				else if($query_type=="Delete"){
					echo $MeetingLogDelete;
				}
			}

			

		}



		if ($SponsAccessLevel == "CSO"){ 

			if($table_name=="Sponsorship Representative"){
						
						
				if($query_type=="Insert"){
					echo $CSOSponsRepInsert;
				}
				
				else if($query_type=="Update"){
					echo $CSOSponsRepUpdate;
				}
				else if($query_type == "View"){
					header("Location: view_table.php");	
				}
				
				else if($query_type="Delete"){
					echo $CSOSponsRepDelete;
				}
				else exit($UnauthorizedMessage);

				
			}


			else if($table_name=="Sector Head"){
						
						
				if($query_type=="Insert"){
					echo $CSOSectorHeadInsert;
				}
				
				else if($query_type=="Update"){
					echo $CSOSectorHeadUpdate;
				}
				else if($query_type == "View"){
					header("Location: view_table.php");	
				}
				
				else if($query_type="Delete"){
					echo $CSOSectorHeadDelete;
				}
				else exit($UnauthorizedMessage);

				
			}



			else if ($table_name=="Event Account"){
				if($query_type == "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $CSOAccountLogInsert;
				}
				else if($query_type=="Delete"){
					echo $CSOAccountLogDelete;
				}
				else exit ($UnauthorizedMessage);	
			}

			else if ($table_name=="Company"){
				if($query_type == "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $CSOCompanyInsert;
				}
				else if ($query_type=="Update"){
					echo $CSOCompanyUpdate;
				}
				else if($query_type=="Delete"){
					echo $CSOCompanyDelete;
				}

			}

			else if ($table_name=="Company Executive"){
				if($query_type == "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $CompanyExecInsert;
				}
				else if ($query_type=="Update"){
					echo $CompanyExecUpdate;
				}
				
				if($query_type=="Delete"){
					echo $CompanyExecDelete;
				}
			}


			else if($table_name=="Meeting Log"){
				if($query_type == "View"){
					header("Location: view_table.php");
				}
				else if($query_type=="Insert"){
					echo $CSOMeetingLogInsert;
				}
				
				else if ($query_type=="Update"){
					echo $CSOMeetingLogUpdate;
				}
				else if($query_type=="Delete"){
					echo $CSOMeetingLogDelete;
				}
			}
		}

	}
	else echo "\$_POST not set";

?>

</body>
</html>