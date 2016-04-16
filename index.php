<?php

session_start();
ob_start();
require './configuration/compile.php';
require './functions/compile.php';
require './views/compile.php';

showHeader();
if (empty(getCurrentUri()[0])) {
  showHome();
}
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
        var_dump($_SESSION);
      }
      break;

    default:
      show404();
      break;
  }
}
showFooter();

?>
