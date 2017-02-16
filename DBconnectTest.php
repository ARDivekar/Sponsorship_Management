<?php
  include_once("DBconnect.php");
  $db = new SponsorshipDB();
  
  if($db->isConnected())
  	echo "CONNECTED TO MySQL DB";
  else echo "COULD NOT CONNECT TO MySQL DB";

?>
