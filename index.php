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
      if (isset($_POST['query'])) {
        echo "<section class='success' id='Search'><h1>Search Results</h1>";
        $search = strtoupper($_POST['query']);
        $search1 = mysqli_query($db, "SELECT * FROM ps_meta_city WHERE name LIKE '%$search%';");
        $search2 = mysqli_query($db, "SELECT * FROM ps_meta_province WHERE pname LIKE '%$search%';");
        $search3 = mysqli_query($db, "SELECT * FROM ps_meta_region WHERE rname LIKE '%$search%';");
        while ($qr = mysqli_fetch_array($search1)) {
          echo $qr['name'] . "<br>";
        }
        while ($qr = mysqli_fetch_array($search2)) {
          echo $qr['pname'] . "<br>";
        }
        while ($qr = mysqli_fetch_array($search3)) {
          echo $qr['rname'] . "<br>";
        }
        include("./views/search.tem");
        echo "</section>";
      }
      else {
        include("./views/search.tem");
      }
      break;

    case "table":
      showHeader();
      $tb1 = mysqli_query($db, "SELECT * FROM ps_sync ORDER BY `ps_sync`.`id` DESC");
      if (mysqli_num_rows($tb1) != 0) {
        $latest_id = mysqli_fetch_array($tb1)[id];
        $tb = mysqli_query($db, "SELECT * FROM ps_sync_".$latest_id);
        echo "<section class='success' id='Search'><h1>Anonymous Patient Data</h1><a href='./medication'><button class='btn btn-warning'>Access Medicational Data</button></a><br><br><table class='table table-hover table-bordered'>";
        echo "<tr style='background-color: #222;'><th>id</th><th>patient_id</th><th>healthcareservice_id</th><th>encounter_datetime</th><th>city_code</th><th>province_code</th><th>region_code</th></tr>";
        while ($data = mysqli_fetch_array($tb)) {
          $cityg = mysqli_query($db, "SELECT * FROM ps_meta_city WHERE cid='".$data['city_code']."'");
          if (mysqli_num_rows($cityg) != 0) {
            $city = mysqli_fetch_array($cityg)['name'];
          }
          else {
            $city = "";
          }
          $provinceg = mysqli_query($db, "SELECT * FROM ps_meta_province WHERE pid='".$data['province_code']."'");
          if (mysqli_num_rows($provinceg) != 0) {
            $province = mysqli_fetch_array($provinceg)['pname'];
          }
          else {
            $province = "";
          }
          $regiong = mysqli_query($db, "SELECT * FROM ps_meta_region WHERE rid='".$data['region_code']."'");
          if (mysqli_num_rows($regiong) != 0) {
            $region = mysqli_fetch_array($regiong)['rname'];
          }
          else {
            $region = "";
          }
          echo "<tr>
          <td>".$data['id']."</td>
          <td>".$data['patient_id']."</td>
          <td>".$data['healthcareservice_id']."</td>
          <td>".$data['encounter_datetime']."</td>
          <td>".$city."</td>
          <td>".$province."</td>
          <td>".$region."</td>
          </tr>";
        }
        echo "</table></section>";
      }
      else {
        echo "<section class='success' id='Search'><h1>No Data Found.</h1><p>We could not find any existing sync data from ShineOS. To start synchronization, please hit the \"Sync to ShineOS+\" button at the top navigation bar. Afterwards, please refresh this page or <a href='./table'>click on this link</a>.</p></section>";
      }
      break;

    case "medication":
      showHeader();
      $latest_id = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM ps_sync ORDER BY `ps_sync`.`id` DESC"))[id];
      $tb = mysqli_query($db, "SELECT * FROM ps_sync_".$latest_id."_medication");
      echo "<section class='success' id='Search'><h1>Anonymous Medication Data</h1><a href='./table'><button class='btn btn-warning'>Access Patient Data</button></a><br><br><table class='table table-hover table-bordered'>";
      echo "<tr style='background-color: #222;'><th>id</th><th>generic_name</th><th>brand_name</th><th>total_quantity</th><th>city_code</th><th>province_code</th><th>region_code</th></tr>";
      while ($data = mysqli_fetch_array($tb)) {
        $cityg = mysqli_query($db, "SELECT * FROM ps_meta_city WHERE cid='".$data['city_code']."'");
        if (mysqli_num_rows($cityg) != 0) {
          $city = mysqli_fetch_array($cityg)['name'];
        }
        else {
          $city = "";
        }
        $provinceg = mysqli_query($db, "SELECT * FROM ps_meta_province WHERE pid='".$data['province_code']."'");
        if (mysqli_num_rows($provinceg) != 0) {
          $province = mysqli_fetch_array($provinceg)['pname'];
        }
        else {
          $province = "";
        }
        $regiong = mysqli_query($db, "SELECT * FROM ps_meta_region WHERE rid='".$data['region_code']."'");
        if (mysqli_num_rows($regiong) != 0) {
          $region = mysqli_fetch_array($regiong)['rname'];
        }
        else {
          $region = "";
        }
        echo "<tr>
        <td>".$data['id']."</td>
        <td>".$data['generic_name']."</td>
        <td>".$data['brand_name']."</td>
        <td>".$data['total_quantity']."</td>
        <td>".$city."</td>
        <td>".$province."</td>
        <td>".$region."</td>
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
