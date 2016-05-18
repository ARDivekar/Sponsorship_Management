<?php 


function get_person_name($SponsID){
	require('DBconnect.php'); //this is needed in every function that uses mySQL
	$rep_name=mysql_query("SELECT Name FROM CommitteeMember WHERE ID = $SponsID" );
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


/*
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
*/
	
function print_table($result){ //array of arritubtes and corresponding sql result we get from querying the attributes
	echo '<div align="center">';
	echo '<table align="center" style=\"width:100%\" class="output_table">';
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
	echo '</div>';
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





?>