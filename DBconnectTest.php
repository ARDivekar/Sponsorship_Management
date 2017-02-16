<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  include_once("DBconnect.php");
  include_once("library_functions.php")
  $db = new SponsorshipDB();

  if($db->isConnected()){
    echo "CONNECTED TO MySQL DB";
    echo_1d_array(parse_url(getenv("CLEARDB_DATABASE_URL")));
  }
  else echo "COULD NOT CONNECT TO MySQL DB";



?>
