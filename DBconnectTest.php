<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  include_once("DBconnect.php");
  $db = new SponsorshipDB();

  if($db->isConnected())
  	echo "CONNECTED TO MySQL DB";
  else echo "COULD NOT CONNECT TO MySQL DB";

?>
