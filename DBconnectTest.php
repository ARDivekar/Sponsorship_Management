<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  include_once("DBconnect.php");
  $db = new SponsorshipDB();

  if($db->isConnected()){
    echo "CONNECTED TO MySQL DB";
    if(isset($_GET["pass"]) && $_GET["pass"] === "mighty_oak"){

      echo "<hr> <h3>Connection Details:</h3> <br>";
      $details = $db->getDetails();
      echo "hostname: ".$details["hostname"]."<br>";
      echo "portnumber: ".$details["portnumber"]."<br>";
      echo "username: ".$details["username"]."<br>";
      echo "password: ".$details["password"]."<br>";
      echo "dbname: ".$details["dbname"]."<br>";

      echo "<hr> <h3>Tables in database:</h3> <br>";
      $tables = $db->listTables();
      foreach($tables as $table)
        echo $table."<br>";
      echo "<hr>";
    }
  }
  else echo "COULD NOT CONNECT TO MySQL DB";

?>
