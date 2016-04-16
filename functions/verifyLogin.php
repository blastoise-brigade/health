<?php

function verifyLogin($db) {
  if (empty($_POST['email'])) {
    if (empty(getCurrentUri()[1])) {
      showLogin();
    }
    else {
      $query1 = mysqli_query($db, "SELECT * FROM ps_user WHERE token='".getCurrentUri()[1]."'");
      if (mysqli_num_rows($query1) == 1) {
        $query2 = mysqli_fetch_array($query1);
        $_SESSION['ps_id'] = $query2['id'];
        header("Location: ../");
      }
      else {
        echo "<h1>Error: Invalid SSO Token</h1>";
      }
    }
  }
  else {
    $query1 = mysqli_query($db, "SELECT * FROM ps_user WHERE email='".$_POST['email']."'");
    if (mysqli_num_rows($query1) == 1) {
      $str = generateRandomString();
      $query2 = mysqli_query($db, "UPDATE ps_user SET token='".$str."' WHERE email='".$_POST['email']."'");
      if (curlToServer("mailgun", $str)) {
        echo "<h1>An email has been sent to: ".$_POST['email'].".<br>Please check that email for your login details.</h1>";
      }
      else {
        echo "<h1>An unknown error has occured.</h1><p>We apologize for the inconveinence.</p>";
      }
    }
    else {
      echo "<h1>Error: No Valid User Found.</h1><p>The user specified is not found nor authorized to access.</p>";
      showLogin();
    }
  }
}

?>
