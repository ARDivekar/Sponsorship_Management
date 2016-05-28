<!DOCTYPE html PUBLIC "-/W3C//DTD HTML 4.01 Transitional/EN"
"http://www.w3.org/TR/html4/loose.dtd">


<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
<style>

</style>
	
</head>

<body>
	<?php 
		
		/*Resume old session:*/
		session_start();

		require('DBconnect.php');
		require('library_functions.php');
		$SponsID=$_SESSION['loginID']; //get SponsID from previous session

	?>
	<header>
		<h1>Sponsorship Department</h1>
	</header>
	<button name="reports" type="button" id="reportButton" onClick="window.location='./reports.php';" style="width:10em;">Reports</button>
	<section id="overview">
		<!-- I have put this stuff in the nav tag cause i wanted it to appear on the left without disturbing the page layout-->
		<!-- You have to write a php code which displays the deals done in that sector-->
		
			<?php 
			require('DBconnect.php'); //this is needed in every function that uses mySQL
			//$Sector=get_person_sector($SponsID);
			//$sector_details=get_sector_details($Sector);
			echo "<table class=\"SponsRepnav\" style=\"width:100%\" >";
			echo"<caption><h2>Chief Sponsorship Officer Details:</h2></caption>";

			echo "<tr align=left>
				<th>Name:</th>
			<td>";
			echo get_person_name($SponsID);

			
			echo "</td></tr>
				</table>";

			
			echo "<table class=\"SponsRepnav\" style=\"width:100%\" >";
			echo"<caption><h2>Event Overview:</h2></caption>";
			echo "<tr align=left>
				<th>Number of companies in database:</th>
			<td>";
			$TotalCompanies=mysql_query("SELECT COUNT(*) as 'Count' FROM Company");
			$row=mysql_fetch_assoc($TotalCompanies);
			$row=$row['Count'];
			echo $row;

			echo "</td></tr>
			<tr align=left>
				<th>Companies signed so far:</th>
				<td>";
			$test=mysql_query("SELECT COUNT(Title) as 'Count' FROM AccountLog WHERE TransType='Deposit'");
			$row=mysql_fetch_assoc($test);
			$row=$row['Count'];
			echo $row;
			echo "
				</td>
			</tr>
			<tr align=left>
				<th>Number of Sectors:</th>
				<td >";
			$NumberSectors= mysql_query("SELECT COUNT(DISTINCT Sector) as 'Count' FROM SectorHead");
			$row=mysql_fetch_assoc($NumberSectors);
			$row=$row['Count'];
			echo $row;
		
			echo "</td>
			</tr>
			<tr align=left>
				<th>Total earned:</th>
				<td>";
			$Income= mysql_query("SELECT SUM(Amount) as 'Sum' FROM AccountLog WHERE TransType='Deposit'"); 
			$row=mysql_fetch_assoc($Income);
			$row=$row['Sum'];
			echo "Rs.".$row;

			echo "</td>
			</tr>
			</table>";
			
			
			?>


		<br><br><br>
		<!--<h3>Total Amount:<!-- Total amount -->
	</section>	
	
	<div id="welcome" align="right">
		<h2>Welcome, <?php echo get_person_name($SponsID); ?> <!-- The name of the  SponsRep will come here -->
		<br>
		<a href='logout.php'>Logout</a>
	</div>
	
	<section class="options">
	
	

	<div id="query" align="center">
		
		<!-- SectorHead form -->
		<form action="query.php" method ="POST">
			<h2>Options:</h2>
			<select name="query_type">
				<option>Insert</option>
				<option>Update</option>
				<option>Delete</option>
				<option>View</option>
			</select>
			
			<select name="table_name">
				<option>Sponsorship Representative</option>
				<option>Sector Head</option>
				<option>Event Account</option>
				<option>Company</option>
				<option>Company Executive</option>
				<option>Meeting Log</option>
			</select>
			<br>
			<button class="optionsButton" type="submit" name="submit">Submit</button>
		</form>


	</div>	
	</section>
</body>	


