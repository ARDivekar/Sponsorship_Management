<?php 

require('DBconnect.php'); //imports all the connection settings.
$dbname = $db_name;
$result = mysql_list_tables($dbname);

mysql_query('SET foreign_key_checks = 0');

  
if (!$result) {
    print "DB Error, could not list tables\n";
    print 'MySQL Error: ' . mysql_error();
    exit;
}
echo "<h1> Dropping tables...</h1>";
echo "<p align=\"left\">";
echo "<table width=\"50%\" border=\"0\">";
echo  "<tr bgcolor=\"#993333\"> ";
echo    "<td width=\"10%\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"-1\" color=\"#FFFFFF\">No.</font></td>";
echo    "<td width=\"25%\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"-1\" color=\"#FFFFFF\">Table name:</font></td>";
echo    "<td width=\"65%\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"-1\" color=\"#FFFFFF\">Status:</font></td>";
echo  "</tr>";

$i = 1;
while ($row = mysql_fetch_row($result)) {
    echo "<tr bgcolor=\"#CCCCCC\">";
    echo    "<td>";
       print "$i";
    $i = $i +1;
	echo    "</td>";

	echo    "<td>";
       print "$row[0]\n";
	echo    "</td>";
	$drop_query = "DROP TABLE $row[0]";
	// echo $drop_query;
	$deleteIt=mysql_query($drop_query); 
	$status = "";
	if($deleteIt){
		$status="The table $row[0] has been deleted successfully.";
	}
	else{
		$status="Table $row[0] has not been deleted. <br>An error has occured...please try again.";
	}

	echo    "<td>";	
    print "$status\n";
	echo    "</td>";
	echo "</tr>";     
}

mysql_query('SET foreign_key_checks = 1');





/*
$all_table_names=array(
	"Event",
	"CommitteeMember", 
	"AccountLog",
	"SponsRep", 
	"SectorHead", 
	"SponsLogin", 
	"Company", 
	"CompanyExec", 
	"Meeting"
);


//we are connecting with MYSQLI PROCEDURAL, __not__ object oriented.

$i=0;
while ($i < count($all_table_names)) {
	$table_name=$all_table_names[$i]; 
	
	$query="select 1 from `$table_name`"; //select 1 selects anything
	if(mysql_query($query)){   
		//the table exists.
		echo "<br><br>Table $table_name exists.";

		if(mysql_query( "DROP TABLE `". $table_name."`")){
			echo "<br>Table $table_name dropped successfully<br><br>";
		}
	}


	$i=$i+1;
}
*/

?>
