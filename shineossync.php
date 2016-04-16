<?php
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

  return json_encode($arrayvar);
}
echo getVisitsList();

?>
