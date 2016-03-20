	<?php 


require('DBconnect.php');

// The Sponsorship Department using the software has many events. They assign a subset of their CommitteeMembers to these events.
// 'Technovanza' is an event, if funding is done at the level of Technovanza. The SponsorshipOrganization could be 'Technovanza'
//'RoboWars' is an event, if funding is done for RoboWars. The SponsorshipOrganization could be 'Technovanza'
$Event_args = " (
	EventID				INT(7) UNSIGNED PRIMARY KEY AUTO_INCREMENT, 
	Organization		VARCHAR(50) NOT NULL,
	EventName 			VARCHAR(80) NOT NULL,
	StartDate 			DATE,
	EndDate 			DATE,
	UNIQUE KEY(Organization, EventName)
);";

// A CommitteeMember is assigned to only one event at once.
$CommitteeMember_args =" (
	ID 				INT(9) UNSIGNED PRIMARY KEY,
	Organization	VARCHAR(50),
	EventName 		VARCHAR(80),
	Name 			VARCHAR(80) NOT NULL,
	Department		VARCHAR(15) NOT NULL,
	Role 			VARCHAR(40) NOT NULL,
	Mobile 			VARCHAR(15),
	Email 			VARCHAR(50),
	Year 			INT (1),
	Branch 			VARCHAR(25),
	foreign key (Organization, EventName) References Event(Organization, EventName)
		On Update Cascade
		On Delete Cascade
	);";

$AccountLog_args = " (
	ID 				INT(9) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	Organization	VARCHAR(50) NOT NULL,
	EventName 		VARCHAR(80) NOT NULL,
	Title 			VARCHAR(100) NOT NULL,
	SponsID 		INT(9) UNSIGNED NOT NULL,
	Amount 			INT(8) UNSIGNED NOT NULL,
	TransType 		ENUM ('Deposit','Withdraw') NOT NULL,
	Date 			DATE,
	foreign key (SponsID) References CommitteeMember(ID)
		On Update Cascade
		On Delete Cascade,
	foreign key (Organization, EventName) References Event(Organization, EventName)
		On Update Cascade
		On Delete Cascade
	);";

$SponsRep_args=" (
	SponsID 		INT(9) UNSIGNED  PRIMARY KEY,
	Sector 			VARCHAR(15) NOT NULL,
	DateAssigned 	DATE,
	Foreign key (SponsID) References CommitteeMember(ID)
		On Update Cascade
		On Delete Cascade
	);";

$SectorHead_args=" (
	SponsID 		INT(9) UNSIGNED PRIMARY KEY,
	Sector 			VARCHAR(15) NOT NULL,
	DateAssigned 	DATE,
	foreign key (SponsID) References CommitteeMember(ID)
		On Update Cascade
		On Delete Cascade
	);";

$SponsLogin_args=" (
	SponsID 		INT(9) UNSIGNED PRIMARY KEY NOT NULL,
	Password 		VARCHAR (50) NOT NULL,
	AccessLevel 	ENUM ('SponsRep', 'SectorHead', 'CSO') NOT NULL,
	foreign key (SponsID) References CommitteeMember(ID)
		On Update Cascade
		On Delete Cascade
	);";


$Company_args=" (
	CMPName 	VARCHAR(100) PRIMARY KEY, 
	CMPStatus 	VARCHAR(15), 
	Sector 		VARCHAR(15) NOT NULL,
	CMPAddress TEXT 

	);";

$CompanyExec_args=" (
	CEName 		VARCHAR(50),
	CMPName 	VARCHAR(100),
	CEMobile 	VARCHAR(15),
	CEEmail 	VARCHAR(75),
	CEPosition 	VARCHAR(50),
	PRIMARY KEY (CEName, CMPName),
	foreign key (CMPName) References Company(CMPName)
		On Update Cascade
		On Delete Cascade
	);";

$Meeting_args=" (
	ID 				INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	Date 			DATE,
	Time 			TIME,
	SponsID 		INT(9) UNSIGNED,
	Organization	VARCHAR(50),
	EventName 		VARCHAR(80),
	MeetingType 	ENUM ('Email', 'Call', 'Meet') NOT NULL,
	CEName 			VARCHAR(50),
	CMPName 		VARCHAR(100),
	Outcome 		TEXT,
	Address 		TEXT,
	foreign key (SponsID) References CommitteeMember(ID)
		On Update Cascade
		On Delete Cascade,
	foreign key (CMPName) References Company(CMPName)
		On Update Cascade
		On Delete Set Null,
	foreign key (CEName, CMPName) References CompanyExec(CEName, CMPName)
		On Update Cascade
		On Delete Set Null,
	foreign key (Organization, EventName) References Event(Organization, EventName)
		On Update Cascade
		On Delete Cascade
	);";


$all_tables=array(
	array("Event", $Event_args),
	array("CommitteeMember", $CommitteeMember_args), 
	array("AccountLog", $AccountLog_args),
	array("SponsRep", $SponsRep_args), 
	array("SectorHead", $SectorHead_args), 
	array("SponsLogin", $SponsLogin_args), 
	array("Company", $Company_args), 
	array("CompanyExec", $CompanyExec_args), 
	array("Meeting", $Meeting_args)
);  


echo "<h1> Generating tables...</h1>";
echo "<p align=\"left\">";
echo "<table width=\"100%\" border=\"0\">";
echo  "<tr bgcolor=\"#993333\"> ";
echo    "<td width=\"5%\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"-1\" color=\"#FFFFFF\">No.</font></td>";
echo    "<td width=\"15%\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"-1\" color=\"#FFFFFF\">Table name:</font></td>";
echo    "<td width=\"25%\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"-1\" color=\"#FFFFFF\">Status:</font></td>";
echo    "<td width=\"55%\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"-1\" color=\"#FFFFFF\">SQL code:</font></td>";
echo  "</tr>";


mysql_query('SET foreign_key_checks = 0');

$i=0;
while ($i < count($all_tables)){
	echo "<tr bgcolor=\"#CCCCCC\">";
	
	$table_name=$all_tables[$i][0];
	$table_args=$all_tables[$i][1]; 
	echo    "<td>";
       print $i+1;
	echo    "</td>";
	echo    "<td>";
       print "$table_name\n";
	echo    "</td>";

	$query="select 1 from `$table_name`";
	$status = "";
	if(mysql_query( $query)){  
		$status = $status."Table $table_name exists and may have data. Truncating it.";

		if(mysql_query( "TRUNCATE TABLE ". $table_name)){
			$status = $status."<br>Table $table_name truncated (i.e. removed all data) successfully.";
		}
	}
	$create_query = "CREATE TABLE IF NOT EXISTS `".$table_name."`".$table_args;
	
	if(mysql_query( $create_query) ) {
		$status = $status."<br><b>Successfully created table $table_name.</b>";
	}
	else{
		$status = $status."<br><b>Could not create table $table_name.</b>";	
	}

	echo    "<td>";	
    print "$status\n";
	echo    "</td>";

	echo    "<td>";	
    print "$create_query\n";
	echo    "</td>";
	echo "</tr>";

	$i=$i+1;
}


mysql_query('SET foreign_key_checks = 1');

?>


