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
        mysqli_query($db, "UPDATE ps_user SET token=NULL WHERE id='".$query2['id']."'");
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
      ?>
      <html>
        <head>
          <title>Presky</title>
          <link rel="stylesheet" type="text/css" href="./assets/style.css" />
          <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
          <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
          <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-OiWEn8WwtH+084y4yW2YhhH6z/qTSecHZuk/eiWtnvLtU+Z8lpDsmhOKkex6YARr" crossorigin="anonymous">
          <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        </head>
        <body>
          <div class="jumbotron" style="background: linear-gradient(to bottom, #7A98BF , #9CBCD9);">
            <div class="container">
              <h1><a href='./' style="color: #FFF;">Presky</a></h1>

            </div>
          </div>
      <div class="container">
        <div class="row">
      <?php
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
