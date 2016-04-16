<?php

session_start();
ob_start();
require './configuration/compile.php';
require './functions/compile.php';
require './views/compile.php';
if (empty(getCurrentUri()[0])) {
  if (isset($_SESSION['ps_id'])) {
    showHeader();
    include("./views/adminpanel.tem");
  }
  else {
    include("./views/homepage.tem");
  }
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
      }
      break;

    case "search":
      showHeader();
      include("./views/search.tem");
      break;

    default:
      show404();
      break;
  }
}

showFooter();

?>
