<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  include_once("DBconnect.php");
  $db = new SponsorshipDB();

  if($db->isConnected()){
    echo "CONNECTED TO MySQL DB";
    if($_GET["pass"] === "mighty_oak"){
      $tables = $db->listTables();
      echo "<hr>Tables in database: <br>";
      foreach($tables as $table)
        echo $table."<br>";
      echo "<hr>";
    }
  }
  else echo "COULD NOT CONNECT TO MySQL DB";

?>
