<?php

require './configuration/compile.php';
require './functions/compile.php';
function getVisitsList() {
  $page = "1";

  function push($p) {
    $cu = curl_init();
    $facility_id = "9043921151503031111120000";
    $url = "http://shine.ph/emr/api/healthcareservices/visitsListByFacilityId?facility_id=".$facility_id."&page=".$p;
    $curlArgs = array(
      CURLOPT_URL => $url,
      CURLOPT_HTTPHEADER => array(
        "ShineKey: 7480178111108080909110423",
        "ShineSecret: 9587353111108080909112508"
      ),
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_CONNECTTIMEOUT => 10,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_SSL_VERIFYHOST => 0
    );
    curl_setopt_array($cu, $curlArgs);
    return json_decode(curl_exec($cu), TRUE);
  }

  $arrayvar = array();

  array_push($arrayvar, push($page));

  for ($i = 1; $i < 3; $i++) {
    $j = $i + 1;
    array_push($arrayvar, push($j));
  }

  return $arrayvar;
}

function getPatientLocationInfo($pid) {
  $cu = curl_init();
  $url = "http://shine.ph/emr/api/patients/patientDetailsByPatientId?patient_id=".$pid;
  $curlArgs = array(
    CURLOPT_URL => $url,
    CURLOPT_HTTPHEADER => array(
      "ShineKey: 7480178111108080909110423",
      "ShineSecret: 9587353111108080909112508"
    ),
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_SSL_VERIFYHOST => 0
  );
  curl_setopt_array($cu, $curlArgs);
  $var = json_decode(curl_exec($cu), TRUE);
  return array(
    "city" => $var['data']['patient_contact']['city'],
    "province" => $var['data']['patient_contact']['province'],
    "region" => $var['data']['patient_contact']['region']
  );
}

$list = getVisitsList();
$token = generateRandomString();
$query1 = mysqli_query($db, "INSERT INTO ps_sync (totalvisits, token) VALUES (".$list[0]['data']['total'].", '$token')");
$query2 = mysqli_query($db, "SELECT * FROM ps_sync WHERE token='$token'");
$query3 = mysqli_fetch_array($query2);
$query4 = mysqli_query($db, "UPDATE ps_sync SET token=NULL WHERE id='".$query3['id']."'");
$query5 = mysqli_query($db, "CREATE TABLE `ps_sync_".$query3['id']."` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `healthcareservice_id` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `encounter_datetime` timestamp NULL DEFAULT NULL,
  `city_code` int(11) DEFAULT NULL,
  `province_code` int(11) DEFAULT NULL,
  `region_code` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
)");
for ($i = 0; $i < count($list); $i++) {
  for ($j = 0; $j < count($list[$i]['data']['data']); $j++) {
    $location_info = getPatientLocationInfo($list[$i]['data']['data'][$j]['patient_id']);
    $query = mysqli_query($db, "INSERT INTO ps_sync_".$query3['id']." (
    patient_id,
    healthcareservice_id,
    encounter_datetime,
    city_code,
    province_code,
    region_code)
    VALUES(
    '".$list[$i]['data']['data'][$j]['patient_id']."',
    '".$list[$i]['data']['data'][$j]['healthcareservice_id']."',
    '".$list[$i]['data']['data'][$j]['encounter_datetime']."',
    '".$location_info['city']."',
    '".$location_info['province']."',
    '".$location_info['region']."'
    )
    ");
  }
}

?>
