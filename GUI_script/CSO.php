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

		/* function get_person_sector($SponsID){
			require('DBconnect.php'); //this is needed in every function that uses mySQL
			$rep_sector=mysql_query("SELECT Sector FROM SponsRep WHERE SponsID = $SponsID" );
			if(mysql_num_rows($rep_sector) == 0)//i.e. you don't find the person with that SponsID in the SponsRep table.
				$rep_sector=mysql_query("SELECT Sector FROM SectorHead WHERE SponsID = $SponsID" ); //look in the SectorHead table.

			$rep_sector=mysql_fetch_assoc($rep_sector);
			$rep_sector = $rep_sector["Sector"];
			return $rep_sector;	
		} */

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


		function get_Meeting_report($SponsID){
			require('DBconnect.php'); //this is needed in every function that uses mySQL
			$rep_name=get_person_name($SponsID);
			$rep_calls=0;
			$rep_emails=0;
			$rep_Meetings=0;
			$rep_Meeting_query = "SELECT * FROM Meeting WHERE SponsID = $SponsID";
			$result = mysql_query ($rep_Meeting_query );
			if(mysql_num_rows($result)>0){
				while($row = mysql_fetch_assoc($result)){
					switch($row["MeetingType"]){
						case "Call": $rep_calls++;
						break;
						case "Email": $rep_emails++;
						break;
						case "Meet": $rep_Meetings++;
						break;

					}
				}
			}
			return array($rep_name, $rep_calls, $rep_Meetings, $rep_emails);
		}


		function get_sector_details($SponsSector){
			require('DBconnect.php'); //this is needed in every function that uses mySQL
			$num_spons_reps=0;
			$num_sector_heads=0;
			$num_companies_in_sector=0;
			$total_companies_signed=0;
			$total_earned=0;
			$max_earned=0;
			$max_earner_ID=-1;
			
			$Sector_SR_query="SELECT * FROM SponsRep WHERE Sector = '$SponsSector'";
			$result=mysql_query($Sector_SR_query );
			
			if( mysql_num_rows($result)> 0){
				while($row = mysql_fetch_assoc($result)){
					$num_spons_reps++;
					$SponsRepID = $row['SponsID'];
					$SponsRepEarningReport = get_earning_report($SponsRepID);
					$total_companies_signed=$total_companies_signed+$SponsRepEarningReport[1];
					$total_earned=$total_earned+$SponsRepEarningReport[2];
					if($max_earned < $SponsRepEarningReport[2]){
						$max_earned = $SponsRepEarningReport[2];
						$max_earner_ID = $SponsRepID;
					}
				}
			}

			$Sector_SH_query="SELECT * FROM SectorHead WHERE Sector='$SponsSector'";
			$result=mysql_query($Sector_SH_query );
			if(mysql_num_rows($result)>0){
				while($row = mysql_fetch_assoc($result))
					$num_sector_heads++;
			}

			$Sector_CMP_query="SELECT * FROM Company WHERE Sector='$SponsSector'";
			$result=mysql_query($Sector_CMP_query );
			if(mysql_num_rows($result)>0){
				while($row = mysql_fetch_assoc($result))
					$num_companies_in_sector++;
			}

			return array("num_spons_reps" => $num_spons_reps, 
				"num_sector_heads"=> $num_sector_heads, 
				"num_companies_in_sector"=> $num_companies_in_sector, 
				"total_companies_signed"=> $total_companies_signed,
				"total_earned"=> $total_earned, 
				"max_earner_ID"=> $max_earner_ID,
				"max_earned"=>$max_earned
				);
		}

	?>
	<header>
		<h1>Sponsorship Department</h1>
	</header>
	
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
			echo"<caption><h2>Festival Overview:</h2></caption>";
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
		<form action="query.php" method ="post"> 
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
				<option>Festival Account</option>
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


