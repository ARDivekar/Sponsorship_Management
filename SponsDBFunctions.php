<?php


	function get_person_name($SponsID){
		$db = new SponsorshipDB();
		$rep_access_level = $db->select("SELECT Name FROM CommitteeMember WHERE ID = " . $SponsID);
		if(count($rep_access_level)==0)
			return NULL;

		return $rep_access_level[0]["Name"];

	}



	function get_access_level($SponsID){
		$db = new SponsorshipDB();
		$rep_access_level = $db->select("SELECT AccessLevel FROM SponsLogin WHERE SponsID = $SponsID");
		if(count($rep_access_level)==0)
			return NULL;

		return $rep_access_level[0]["AccessLevel"];
	}




	function get_person_sector($SponsID){
		$db = new SponsorshipDB();
		$rep_sector = $db->select("SELECT Sector FROM ((Select SponsID, Sector from SponsRep) UNION (Select SponsID, Sector from SectorHead)) as SponsOfficer  WHERE SponsOfficer.SponsID = $SponsID");
		if (count($rep_sector) == 0)//i.e. you don't find the person with that SponsID in the SponsRep table.
			return NULL;

		return $rep_sector[0]["Sector"];
	}



	function get_earning_report($SponsID){
		$rep_name = get_person_name($SponsID);
		$rep_amount = 0;
		$rep_num_companies = 0; //gets nummber of companies signed by that spons rep

		$rep_amount_query = "SELECT Amount FROM AccountLog WHERE SponsID = $SponsID";

		$db = new SponsorshipDB();
		$result = $db->select($rep_amount_query);
		if (count($result) > 0){
			foreach ($result as $row){
				$rep_num_companies++;
				$rep_amount = $rep_amount + $row["Amount"];
			}
		}

		return array($rep_name, $rep_num_companies, $rep_amount);
	}




	function get_meeting_report($SponsID){
		$rep_name = get_person_name($SponsID);
		$rep_calls = 0;
		$rep_emails = 0;
		$rep_meetings = 0;

		$rep_meeting_query = "SELECT * FROM Meeting WHERE SponsID = $SponsID";
		$db = new SponsorshipDB();
		$result = $db->select($rep_meeting_query);
		if (count($result) > 0){
			foreach ($result as $row){
				switch ($row["MeetingType"]){
					case MeetingTypes::Call:
						$rep_calls++;
						break;
					case MeetingTypes::Email:
						$rep_emails++;
						break;
					case MeetingTypes::FaceToFace:
						$rep_meetings++;
						break;

				}
			}
		}

		return array($rep_name, $rep_calls, $rep_meetings, $rep_emails);
	}



	function get_sector_details($SponsSector){
		$num_spons_reps = 0;
		$num_sector_heads = 0;
		$num_companies_in_sector = 0;
		$total_companies_signed = 0;
		$total_earned = 0;
		$max_earned = 0;
		$max_earner_ID = -1;

		$Sector_SR_query = "SELECT * FROM SponsRep WHERE Sector = '$SponsSector'";
		$db = new SponsorshipDB();
		$result = $db->select($Sector_SR_query);

		if (count($result) > 0){
			foreach ($result as $row){
				$num_spons_reps++;
				$SponsRepID = $row['SponsID'];
				$SponsRepEarningReport = get_earning_report($SponsRepID);
				$total_companies_signed = $total_companies_signed + $SponsRepEarningReport[1];
				$total_earned = $total_earned + $SponsRepEarningReport[2];
				if ($max_earned < $SponsRepEarningReport[2]){
					$max_earned = $SponsRepEarningReport[2];
					$max_earner_ID = $SponsRepID;
				}
			}
		}

		$Sector_SH_query = "SELECT * FROM SectorHead WHERE Sector='$SponsSector'";
		$result = $db->select($Sector_SH_query);
		$num_sector_heads = count($result);


		$Sector_CMP_query = "SELECT * FROM Company WHERE Sector='$SponsSector'";
		$result = $db->select($Sector_CMP_query);
		$num_companies_in_sector = count($result);

		return array("num_spons_reps" => $num_spons_reps, "num_sector_heads" => $num_sector_heads, "num_companies_in_sector" => $num_companies_in_sector, "total_companies_signed" => $total_companies_signed, "total_earned" => $total_earned, "max_earner_ID" => $max_earner_ID, "max_earned" => $max_earned);
	}





	$db = new SponsorshipDB();


	/*##------------------------------------------------TESTS------------------------------------------------##

	echo get_person_sector("131010004")."<hr>";
	echo get_access_level(131010004)."<hr>";
	echo get_person_name("131010004")."<hr>";
	foreach(get_earning_report("131010004") as $x){
		echo "<br>".$x;
	}
	echo "<hr>";
	foreach(get_meeting_report("131010004") as $x){
		echo "<br>".$x;
	}
	echo "<hr>";
	foreach(get_sector_details(get_person_sector("131010004")) as $x){
		echo "<br>".$x;
	}


	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/



?>