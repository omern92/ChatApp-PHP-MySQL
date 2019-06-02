<?php
  require_once 'api/userClass.php';
  require_once 'mapper/functions.php';
  require_once 'header.php';

  if (isset($_SESSION['user_name']))
  {
    destroy_session();
    echo "You have been logged out.";
  }
  else
    echo "You are not logged in.";

?>
