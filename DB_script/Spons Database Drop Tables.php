<?php 

require('DBconnect.php'); //imports all the connection settings.


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


//we are connecting with MYSQLI PROCEDURAL, __not__ object oriented.

$i=1;
while ($i <= 9){

	$table_name=$all_table_names[$i]; 
	
	//echo "<br><br>" .$table_name.$table_args;

	/* //Note: instead of the following code, we could also have used:

		$query="select * from `$table_name`";
		$result=mysql_query(  $query);
		if(mysql_num_rows($result)>0){
			//table has at least one row in it.
		}	
		
	*/

	$query="select 1 from `$table_name`"; //select 1 selects anything
	if(mysql_query(  $query)){   
		//DO SOMETHING! IT EXISTS!
		echo "<br>Table $table_name exists. Destroying it.";

		if(mysql_query( "DROP TABLE `". $table_name."`")){
			echo "<br>Table $table_name dropped successfully<br><br>";
		}
	}


	$i=$i+1;
}


?>
