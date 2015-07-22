	<?php 


require('DBconnect.php');


$all_table_names=array(

	1 => "Festival",
	2 => "CommitteeMember", 
	3 => "AccountLog",
	4 => "SponsRep", 
	5 => "SectorHead", 
	6 => "SponsLogin", 
	7 => "Company", 
	8 => "CompanyExec", 
	9 => "Meeting"
);//, "AccountLog", "Festival");



$Festival_args = " (
	FestName 		VARCHAR(20) PRIMARY KEY,
	StartDate 		DATE,
	EndDate 		DATE
);";

$CommitteeMember_args =" (
	StudID 		INT(9) UNSIGNED PRIMARY KEY,
	Name 		VARCHAR(50) NOT NULL,
	Dept 		VARCHAR(15) NOT NULL,
	Role 		VARCHAR(40) NOT NULL,
	Mobile 		VARCHAR(15),
	Email 		VARCHAR(50),
	Year 		INT (1),
	Branch 		VARCHAR(25),
	
);";

$AccountLog_args = " (
	Title 		VARCHAR(100) PRIMARY KEY NOT NULL,
	SponsID 	INT(9) UNSIGNED NOT NULL,
	Amount 		INT(8) UNSIGNED NOT NULL,
	TransType 	ENUM ('Deposit','Withdraw') NOT NULL,
	Date 		DATE,
	foreign key (SponsID) References CommitteeMember(StudID)
		On Update Cascade
		On Delete Cascade
);";

//SponsID is the same as StudID

$SponsRep_args=" (
	SponsID 		INT(9) UNSIGNED  PRIMARY KEY,
	Sector 			VARCHAR(15) NOT NULL,
	DateAssigned 	DATE,
	Foreign key (SponsID) References CommitteeMember(StudID)
		On Update Cascade
		On Delete Cascade
	);";

$SectorHead_args=" (
	SponsID 	INT(9) UNSIGNED  PRIMARY KEY,
	Sector 		VARCHAR(15) NOT NULL,
	foreign key (SponsID) References CommitteeMember(StudID)
		On Update Cascade
		On Delete Cascade
	);";

$SponsLogin_args=" (
	SponsID 		INT(9) UNSIGNED PRIMARY KEY NOT NULL,
	Password 		VARCHAR (20) NOT NULL,
	AccessLevel 	ENUM ('SponsRep', 'SectorHead', 'CSO') NOT NULL,
	foreign key (SponsID) References CommitteeMember(StudID)
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
	Date 			DATE,
	Time 			TIME,
	SponsID 		INT(9) UNSIGNED,
	MeetingType 	ENUM ('Email', 'Call', 'Meet') NOT NULL,
	CEName 			VARCHAR(50),
	CMPName 		VARCHAR(100),
	Outcome 		TEXT,
	Address 		TEXT,
	PRIMARY KEY 	(Date, Time, SponsID),
	foreign key (SponsID) References CommitteeMember(StudID)
		On Update Cascade
		On Delete Cascade,
	foreign key (CMPName) References Company(CMPName)
		On Update Cascade
		On Delete Set Null,
	foreign key (CEName, CMPName) References CompanyExec(CEName, CMPName)
		On Update Cascade
		On Delete Set Null

);";



$all_table_args=array(
	1 => $Festival_args,
	2 => $CommitteeMember_args, 
	3 => $AccountLog_args,
	4 => $SponsRep_args, 
	5 => $SectorHead_args, 
	6 => $SponsLogin_args, 
	7 => $Company_args, 
	8 => $CompanyExec_args, 
	9 => $Meeting_args
);  

/*
array_keys()
for (array_keys($array_one) as $key) {
    // do something with $array_one[$key] and $array_two[$key]
}*/

$i=1;
while ($i <= 9){

	$table_name=$all_table_names[$i];
	$table_args=$all_table_args[$i]; 
	//echo "<br><br>" .$table_name.$table_args;

	$query="select 1 from `$table_name`";

	if(mysql_query( $query)){  
		//DO SOMETHING! IT EXISTS!
		echo "<br>Table $table_name exists. Truncating it.";

		if(mysql_query( "TRUNCATE TABLE ". $table_name)){
			echo "<br>Table $table_name truncated (i.e. removed all data) successfully<br><br>";
		}
	}

	if(mysql_query( "CREATE TABLE IF NOT EXISTS `".$table_name."`".$table_args) ) {
		echo "<br>Successfully created table $table_name <br><br>";
	}

	$i=$i+1;
}




?>


