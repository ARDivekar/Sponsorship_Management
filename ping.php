<?php
  $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  echo "Ping! I am a server at $actual_link";
?>
