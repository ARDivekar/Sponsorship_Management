<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  include_once("DBconnect.php");
  include_once("library_functions.php")
  $db = new SponsorshipDB();

  if($db->isConnected()){
    echo "CONNECTED TO MySQL DB";
    $clearDB_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    echo "<hr> HOST: ".$clearDB_url["host"];
    echo "<hr> USER: ".$clearDB_url["user"];
    echo "<hr> PASS: ".$clearDB_url["pass"];
    echo "<hr> PATH: ".substr($clearDB_url["path"], 1);
  }
  else echo "COULD NOT CONNECT TO MySQL DB";



?>
