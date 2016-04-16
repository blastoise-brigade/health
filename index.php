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

    case "table":
      showHeader();
      $latest_id = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM ps_sync ORDER BY `ps_sync`.`id` DESC"))[id];
      $tb = mysqli_query($db, "SELECT * FROM ps_sync_".$latest_id);
      //$tb = mysqli_query($db, "SELECT * FROM ps_sync_".$latest_id."_medication");
      echo "<section class='success' id='Search'><h1>Anonymous Patient Data</h1><a href='./medication'><button class='btn btn-warning'>Access Medicational Data</button></a><br><br><table class='table table-hover table-bordered'>";
      echo "<tr style='background-color: #222;'><th>id</th><th>patient_id</th><th>healthcareservice_id</th><th>encounter_datetime</th><th>city_code</th><th>province_code</th><th>region_code</th></tr>";
      while ($data = mysqli_fetch_array($tb)) {
        echo "<tr>
        <td>".$data['id']."</td>
        <td>".$data['patient_id']."</td>
        <td>".$data['healthcareservice_id']."</td>
        <td>".$data['encounter_datetime']."</td>
        <td>".$data['city_code']."</td>
        <td>".$data['province_code']."</td>
        <td>".$data['region_code']."</td>
        </tr>";
      }
      echo "</table></section>";
      break;

    case "medication":
      showHeader();
      $latest_id = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM ps_sync ORDER BY `ps_sync`.`id` DESC"))[id];
      $tb = mysqli_query($db, "SELECT * FROM ps_sync_".$latest_id."_medication");
      //$tb = mysqli_query($db, "SELECT * FROM ps_sync_".$latest_id."_medication");
      echo "<section class='success' id='Search'><h1>Anonymous Medication Data</h1><a href='./table'><button class='btn btn-warning'>Access Patient Data</button></a><br><br><table class='table table-hover table-bordered'>";
      echo "<tr style='background-color: #222;'><th>id</th><th>generic_name</th><th>brand_name</th><th>total_quantity</th><th>city_code</th><th>province_code</th><th>region_code</th></tr>";
      while ($data = mysqli_fetch_array($tb)) {
        echo "<tr>
        <td>".$data['id']."</td>
        <td>".$data['generic_name']."</td>
        <td>".$data['brand_name']."</td>
        <td>".$data['total_quantity']."</td>
        <td>".$data['city_code']."</td>
        <td>".$data['province_code']."</td>
        <td>".$data['region_code']."</td>
        </tr>";
      }
      echo "</table></section>";
      break;

    default:
      show404();
      break;
  }
}

showFooter();

?>
