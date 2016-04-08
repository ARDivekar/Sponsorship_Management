<?php 
/*Connection stuff
the statement:
	require('DBconnect');
in each of the other php files, basically imports this.
*/

//$person_name = "Divekar";

$servername = 'sql113.byethost8.com';
$username = 'b8_17790289'; //you might need to change this based on your WAMP/XAMP/LAMP username
$password='d6hb7jv8'; //you might need to change this based on your WAMP/XAMP/LAMP username
$db_name='b8_17790289_sponsorshipmanagement';
$sql='CREATE DATABASE IF NOT EXISTS '.$db_name; //I realized that nowhere in this code is this being __used___. Still, don't delete it.


/*we are connecting with MYSQLI PROCEDURAL, __not__ object oriented.*/
error_reporting(E_ALL ^ E_DEPRECATED);

$conn = mysql_connect($servername, $username, $password); //sets up connection.
if (!$conn) {
    die("Connection failed: " . mysql_connect_error());
}
//else echo "Connected successfully, $person_name.<br>";

mysql_select_db($db_name); 
  

/*from: 
http://www.w3schools.com/php/func_mysqli_select_db.asp   
___AND___   
http://stackoverflow.com/questions/2261624/using-same-mysql-connection-in-different-php-pages
*/
?>
