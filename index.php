<?php

session_start();
ob_start();
require './configuration/compile.php';
require './functions/compile.php';
require './views/compile.php';
if (empty(getCurrentUri()[0])) {
  include("homepage.html");
  die();
}
showHeader();
else {
  switch(getCurrentUri()[0]) {
    case "logout":
      session_unset("ps_id");
      header("Location: ./");

      break;

    case "login":
      if (isset($_SESSION['ps_id'])) {
        header("Location: ../");
      }
      else {
        verifyLogin($db);
      }
      break;

    default:
      show404();
      break;
  }
}
showFooter();

?>
