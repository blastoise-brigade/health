<?php

session_start();
ob_start();
require './views/compile.php';

showHeader();
if (empty(getCurrentUri()[0])) {
  showHome();
}
else {
  switch(getCurrentUri()[0]) {
    case "login":
      showLogin();
      break;
    default:
      show404();
      break;
  }
}
showFooter();

?>
