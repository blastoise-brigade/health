<?php

function show404() {
  header("HTTP/1.0 404 Not Found");
  echo "<h1>Error 404: File not Found.</h1>";
  echo "<p>The file or directory requested has not been found.</p>";
}

 ?>
