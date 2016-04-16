<?php

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
  }
}
showFooter();

?>
