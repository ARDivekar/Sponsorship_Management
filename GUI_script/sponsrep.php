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
		$SponsID=$_SESSION['loginID']; //get SponsID from previos session
		
		function get_person_name($SponsID){
			require('DBconnect.php'); //this is needed in every function that uses mySQL
			$rep_name=mysql_query("SELECT Name FROM CommitteeMember WHERE StudID = $SponsID" );
			$rep_name=mysql_fetch_assoc($rep_name);
			$rep_name = $rep_name["Name"];
			return $rep_name;
		}


		function get_access_level($SponsID){
			require('DBconnect.php'); //this is needed in every function that uses mySQL
			$rep_access_level=mysql_query("SELECT AccessLevel FROM SponsLogin WHERE SponsID = $SponsID" );
			$rep_access_level=mysql_fetch_assoc($rep_access_level);
			$rep_access_level = $rep_access_level["AccessLevel"];
			return $rep_access_level;	
		}

		function get_person_sector($SponsID){
			require('DBconnect.php'); //this is needed in every function that uses mySQL
			$rep_sector=mysql_query("SELECT Sector FROM SponsRep WHERE SponsID = $SponsID" );
			if(mysql_num_rows($rep_sector) == 0)//i.e. you don't find the person with that SponsID in the SponsRep table.
				$rep_sector=mysql_query("SELECT Sector FROM SectorHead WHERE SponsID = $SponsID" ); //look in the SectorHead table.

			$rep_sector=mysql_fetch_assoc($rep_sector);
			$rep_sector = $rep_sector["Sector"];
			return $rep_sector;	
		}

		function get_earning_report($SponsID){
			require('DBconnect.php'); //this is needed in every function that uses mySQL
			$rep_name=get_person_name($SponsID);
			$rep_amount=0;
			$rep_num_companies=0; //gets nummber of companies signed by that spons rep
			$rep_amount_query = "SELECT Amount FROM AccountLog WHERE SponsID = $SponsID";
			$result = mysql_query ($rep_amount_query );
			if(mysql_num_rows($result)>0){
				while($row = mysql_fetch_assoc($result)){
					$rep_num_companies++;
					$rep_amount=$rep_amount + $row["Amount"];
				}
			}
			return array($rep_name, $rep_num_companies, $rep_amount);
		}


		function get_meeting_report($SponsID){
			require('DBconnect.php'); //this is needed in every function that uses mySQL
			$rep_name=get_person_name($SponsID);
			$rep_calls=0;
			$rep_emails=0;
			$rep_meetings=0;
			$rep_meeting_query = "SELECT * FROM Meeting WHERE SponsID = $SponsID";
			$result = mysql_query ($rep_meeting_query );
			if(mysql_num_rows($result)>0){
				while($row = mysql_fetch_assoc($result)){
					switch($row["MeetingType"]){
						case "Call": $rep_calls++;
						break;
						case "Email": $rep_emails++;
						break;
						case "Meet": $rep_meetings++;
						break;

					}
				}
			}
			return array($rep_name, $rep_calls, $rep_meetings, $rep_emails);
		}


		

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
				<option>Festival Account</option>
			</select>
			<br>
			<button class="optionsButton" type="submit" name="submit" >Submit</button>

		</form>

	</div>	
	</section>
	<!-- <footer class=".spons_page"></footer> -->
</body>	