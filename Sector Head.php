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

		if(empty($_SESSION['loginID']))
			header("Location: login.php");

		require('DBconnect.php');
		require('library_functions.php');

		$SponsID=$_SESSION['loginID']; //get SponsID from previous session
		$_SESSION[SessionEnums::UserLoginID] = $_SESSION['loginID'];


	?>
	<header>
		<h1>Sponsorship Department</h1>
	</header>
	
	<section id="overview">
		<!-- I have put this stuff in the nav tag cause i wanted it to appear on the left without disturbing the page layout-->
		<!-- You have to write a php code which displays the deals done in that sector-->
		
			<?php 
			require('DBconnect.php'); //this is needed in every function that uses mySQL
			$SponsSector=get_person_sector($SponsID);
			$Sector_details=get_sector_details($SponsSector);
			echo "<table class=\"sponsrepnav\" style=\"width:100%\" >";
			echo"<caption><h2>Sector Head Details:</h2></caption>";

			echo "<tr align=left>
				<th>Name:</th>
			<td>";
			echo get_person_name($SponsID);

			
			echo "</td></tr><tr align=left>
				<th>Sector Head of:</th>
			<td>";
			echo get_person_sector($SponsID);

			echo "</td>
				</tr>
				</table>";

			
			echo "<table class=\"sponsrepnav\" style=\"width:100%\" >";
			echo"<caption><h2>$SponsSector Sector Overview:</h2></caption>";
			echo "<tr align=left>
				<th>Number of companies in sector:</th>
			<td>";
			echo $Sector_details['num_companies_in_sector'];


			echo "</td></tr>
			<tr align=left>
				<th>Companies signed so far:</th>
				<td>";
			echo $Sector_details['total_companies_signed'];

			echo "
				</td>
			</tr>
			<tr align=left>
				<th>SponsReps in sector:</th>
				<td >";
			echo $Sector_details['num_spons_reps'];
			
		
			echo "</td>
			</tr>
			<tr align=left>
				<th>Total earned by Sector:</th>
				<td>";
			echo "Rs.".$Sector_details['total_earned']; 


			echo "</td>
			</tr>
			<tr align=left>
				<th>Maximum Earner:</th>
				<td>";
				$max_earner_name=get_person_name($Sector_details['max_earner_ID']);
				$max_earner_amount=$Sector_details['max_earned'];
			echo "$max_earner_name <br> (Rs. $max_earner_amount)"; 

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
	<div align="center">
	</div>
	



	<div id="query" align="center">
		
		<!-- SectorHead form -->
		<form action="query.php" method ="GET">
		<h2>Options:</h2>
			<select name="query_type">
				<option>Insert</option>
				<option>Update</option>
				<option>Delete</option>
				<option>View</option>
			</select>
			
			<select name="table_name">
				<option>Sponsorship Representative</option>
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