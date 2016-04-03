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

		$SponsID=$_SESSION['loginID']; //get SponsID from previos session

	?>
	<header>
		<h1>Sponsorship Department</h1>
	</header>
	
	<section id="overview">
		<!-- I have put this stuff in the nav tag cause i wanted it to appear on the left without disturbing the page layout-->
		<!-- You have to write a php code which displays the deals done in that sector-->
		
			<?php 
			$earning_arr= get_earning_report($SponsID);
			$meeting_arr= get_meeting_report($SponsID);
			echo "<table class=\"sponsrepnav\" style=\"width:100%\" >";
			echo"<caption><h2>Sponsorship Representative Details:</h2></caption>";

			echo "<tr align=left>
				<th>Name:</th>
			<td>";
			echo get_person_name($SponsID);
			echo "</td></tr><tr align=left>
				<th>Sector:</th>
			<td>";
			echo get_person_sector($SponsID);
			echo "</td></tr><tr align=left>
				<th>Companies signed:</th>
			<td>";
			echo $earning_arr[1];
			echo "</td></tr>
			<tr align=left>
				<th>Total Earned (Rupees):</th>
				<td>";
			echo $earning_arr[2];
			echo "
				</td>
			</tr>
			<tr align=left>
				<th>Calls Made:</th>
				<td>";
			echo $meeting_arr[1]; 
			echo "</td>
			</tr>
			<tr align=left>
				<th>Meetings:</th>
				<td>";
			echo $meeting_arr[2]; 
			echo "</td>
			</tr>
			<tr align=left>
				<th>Emails:</th>
				<td>";
			echo $meeting_arr[3]; 

			echo "</td>
			</tr>"
			
			
			?>


		</table>
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
		<!-- SponsRep form -->
		<form action="query.php" method ="post"> 
		<h2>Options:</h2>
			<select name="query_type">
				<option>Insert</option>
				<option>Update</option>
				<option>Delete</option>
				<option>View</option>
			</select>
			
			<select name="table_name">
				<option>Company</option>
				<option>Company Executive</option>
				<option>Meeting Log</option>
				<option>Event Account</option>
			</select>
			<br>
			<button class="optionsButton" type="submit" name="submit" >Submit</button>

		</form>

	</div>	
	</section>
	<!-- <footer class=".spons_page"></footer> -->
</body>	