<?php
$medsarray = array();
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

function getPatientMedications($hid, $location_info) {
  $medArray = array();
  $cu = curl_init();
  $url = "http://shine.ph/emr/api/healthcareservices/healthcaretypeByHealthcareserviceid?healthcaretype=medicalOrders&healthcareservice_id=".$hid;
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
  if (!empty($var['data'][0]['medical_order_prescription'])) {
    for ($o = 0; $o < count($var['data'][0]['medical_order_prescription']); $o++) {
      array_push($medArray, array(
        "generic_name" => $var['data'][0]['medical_order_prescription'][$o]['generic_name'],
        "brand_name" => $var['data'][0]['medical_order_prescription'][$o]['brand_name'],
        "total_quantity" => $var['data'][0]['medical_order_prescription'][$o]['total_quantity'],
        "city_code" => $location_info['city'],
        "province_code" => $location_info['province'],
        "region_code" => $location_info['region']
      ));
    }
  }
  return $medArray;
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
    $meds = getPatientMedications($list[$i]['data']['data'][$j]['healthcareservice_id'], $location_info);
    if (!empty($meds)) {
      array_push($medsarray, $meds);
    }
  }
}
$query6 = mysqli_query($db, "CREATE TABLE `ps_sync_".$query3['id']."_medication` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `generic_name` tinytext COLLATE utf8_unicode_ci,
  `brand_name` tinytext COLLATE utf8_unicode_ci,
  `total_quantity` tinytext COLLATE utf8_unicode_ci,
  `city_code` int(11) DEFAULT NULL,
  `province_code` int(11) DEFAULT NULL,
  `region_code` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
)");

for ($i = 0; $i < count($medsarray); $i++) {
  $str = "INSERT INTO ps_sync_".$query3['id']."_medication ( `generic_name`, `brand_name`, `total_quantity`, `city_code`, `province_code`, `region_code`) VALUES (
  '".$medsarray[$i][0]['generic_name']."',
  '".$medsarray[$i][0]['brand_name']."',
  '".$medsarray[$i][0]['total_quantity']."',
  '".$medsarray[$i][0]['city_code']."',
  '".$medsarray[$i][0]['province_code']."',
  '".$medsarray[$i][0]['region_code']."'
  )";
  $query7 = mysqli_query($db, $str);
}
?>
