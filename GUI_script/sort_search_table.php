<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
</head>

<body>
	
<?php 

	session_start();

	if(empty($_SESSION['loginID']))	
		header("Location: login.php");

	require('DBconnect.php');
	$SponsID=$_SESSION['loginID']; //get SponsID from previos session

	$FieldEmptyMessage='<div align=center><h3 align=center style="padding: 40px; font-size:28px; line-height:50px;"  class="invalid_message">Error<br>You have not filled all the required fields.</h3> </div>';

	$SponsRepBackButton="<h2><a href='sponsrep.php' class='back_button'>Go back</a></h2><br>";
	$SectorHeadBackButton="<h2><a href='sectorhead.php' class='back_button'>Go back</a></h2><br>";
	$CSOBackButton="<h2><a href='CSO.php' class='back_button'>Go back</a></h2><br>";

	
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
		$rep_access_level=mysql_query("SELECT Sector FROM SponsRep WHERE SponsID = $SponsID" );
		if(mysql_num_rows($rep_access_level) == 0)//i.e. you don't find the person with that SponsID in the SponsRep table.
			$rep_access_level=mysql_query("SELECT Sector FROM SectorHead WHERE SponsID = $SponsID" ); //look in the SectorHead table.

		$rep_access_level=mysql_fetch_assoc($rep_access_level);
		$rep_access_level = $rep_access_level["Sector"];
		return $rep_access_level;	
	}

	function print_table($result){ //array of arritubtes and corresponding sql result we get from querying the attributes
			echo '<table style=\"width:100%\" class="output_table">';
			echo "<tr>";
			$i=0;
			//$attributes_info = mysql_fetch_field($result, $i); //gets a lot of data about the attributes...their names, their types etc.
			while($i < mysql_num_fields($result)) {
				$attr=mysql_fetch_field($result, $i); 
				echo "<th>".$attr->name."</th>";
				$i++;
			}

			while($row=mysql_fetch_assoc($result)){
				echo '<tr>';
				foreach ($row as $key => $value) {
					echo '<td>'.$value.'</td>';
				}
				echo "</tr>";
			}
			echo "</table>";
	}

	

	function print_sort($result){ 
		echo '<form action="sort_search_table.php" class="sort_form" method="post" align="center">';
			echo 'Sort by:<select name="order_by">';
			$i=0;

			while($i < mysql_num_fields($result)) {
				$attr=mysql_fetch_field($result, $i); 
				echo "<option>".$attr->name."</option>";
				$i++;
			}
			echo '</select> ';

			echo '<button type="submit" name="submit">Sort</button>';
		echo '</form>';
	}


	function print_search($result){ 
		echo '<form action="sort_search_table.php" class="search_form" method="post" align="center">';
		
			echo 'Search by:<select name="search_by">';
			$i=0;

			while($i < mysql_num_fields($result)) {
				$attr=mysql_fetch_field($result, $i); 
				echo "<option>".$attr->name."</option>";
				$i++;
			}
			echo '</select> ';
			echo '<input type="text" name="search_field">';

			echo '<button type="submit" name="submit">Search</button>';
		echo '</form>';
	}
	

	
	
	$SponsAccessLevel = get_access_level($SponsID);
	$SponsName = get_person_name($SponsID);
	$SponsSector = get_person_sector($SponsID);
	
	$table_message=$_SESSION['table_message'];
	$main_query=$_SESSION['main_query'];



	echo '<header align="center">
			<h1>Sponsorship Department</h1>';

	if($SponsAccessLevel=="SectorHead")
			echo $SectorHeadBackButton;

	if($SponsAccessLevel=="SponsRep")
		echo $SponsRepBackButton;

	if($SponsAccessLevel == "CSO")
		echo $CSOBackButton;

	echo '</header>';

	echo '<div align="center">';

	if(isset($_POST['submit'])){
		if(isset($_POST['order_by'])){
			$order_by=$_POST['order_by'];
			$main_query=$main_query." Order by `$order_by`";
			
			// echo $main_query;
		}
		else if (isset($_POST['search_by']) and isset($_POST['search_field']) and !empty($_POST['search_field'])){
			$search_by=$_POST['search_by'];
			$search_field=$_POST['search_field'];
			$main_query=$main_query." and $search_by='$search_field'";
		}
		else echo $FieldEmptyMessage;
		
		echo $table_message;
		$result=mysql_query($main_query);

		echo '<script type="text/javascript">
function printpage() {
document.getElementById("printButton").style.visibility="hidden";
window.print();
document.getElementById("printButton").style.visibility="visible";  
}
</script>';
echo '<button name="print" type="button" value="Print" id="printButton" onClick="printpage()">Print</button> ';
		print_sort($result);
		print_search($result);	
		echo '<br>';
		print_table($result);

	}

		?>
</div>
</body>
</html>