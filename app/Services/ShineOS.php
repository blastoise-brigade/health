<?php

namespace App\Services;

class ShineOS extends Service {

  private function pushShineVisitsList($p)
  {
    $url = "http://shine.ph/emr/api/healthcareservices/visitsListByFacilityId";
    $response = $this->client->request('GET', $url, [
        'query' => [
          'facility_id' => '9043921151503031111120000',
          'page' => $p
        ],
        'headers' => [
            'ShineKey' => '7480178111108080909110423',
            "ShineSecret" => "9587353111108080909112508"
        ]
    ])->getBody();

    return json_decode($response);
  }

  private function getPatientLocationInfo($pid) {
    $url = "http://shine.ph/emr/api/patients/patientDetailsByPatientId";
    $response = $this->client->request('GET', $url, [
        'query' => [
          'patient_id' => $pid
        ],
        'headers' => [
            'ShineKey' => '7480178111108080909110423',
            "ShineSecret" => "9587353111108080909112508"
        ]
    ])->getBody();
    $responseData = json_decode($response, TRUE);
    return array(
      "city" => $responseData['data']['data'][0]['patient_contact']['city'],
      "province" => $responseData['data']['data'][0]['patient_contact']['province'],
      "region" => $responseData['data']['data'][0]['patient_contact']['region']
    );
  }

  private function getPatientMedications($healthcareservice_id, $city, $province, $region) {
    $url = "http://shine.ph/emr/api/healthcareservices/healthcaretypeByHealthcareserviceid";
    $response = $this->client->request('GET', $url, [
        'query' => [
          'healthcaretype' => 'medicalOrders',
          'healthcareservice_id' => $healthcareservice_id
        ],
        'headers' => [
            'ShineKey' => '7480178111108080909110423',
            "ShineSecret" => "9587353111108080909112508"
        ]
    ])->getBody();
    $medResponse = json_decode($response, TRUE);
    $array = array();
    if (count($medResponse['data']) > 0) {
      foreach($medResponse['data'] as $data) {
        if (count($data['medical_order_prescription']) > 0) {
          foreach ($data['medical_order_prescription'] as $item) {
            array_push($array, array(
              "generic_name" => $item['generic_name'],
              "brand_name" => $item['brand_name'],
              "total_quantity" => $item['total_quantity'],
              "city" => $city,
              "province" => $province,
              "region" => $region
            ));
          }
        }
        else {
        }
      }
    }
    if (count($array) == 0) {
      return NULL;
    }
    return $array;
  }

  public function getVisitsList() {
    $array = array();
    $lastPage = $this->pushShineVisitsList($visitPage)->data->last_page;
    for ($i = 0; $i < $lastPage; $i++) {
      $parsePage = $i + 1;
      $shineResult = $this->pushShineVisitsList($parsePage)->data->data;
      foreach($shineResult as $data) {
        $locationData = $this->getPatientLocationInfo($data->patient_id);
        array_push($array, array(
          "patient_id" => $data->patient_id,
          "healthcareservice_id" => $data->healthcareservice_id,
          "encounter_datetime" => $data->encounter_datetime,
          "city" => $locationData['city'],
          "province" => $locationData['province'],
          "region" => $locationData['region']
        ));
      }
    }
    return $array;
  }

  public function getMedicationList($visits) {
    $array = array();
    foreach ($visits as $data) {
      $result = $this->getPatientMedications($data['healthcareservice_id'], $data['city'], $data['province'], $data['region']);
      array_push($array, $result);
    }
    $arr = array_filter($array);
    return $arr;
  }

}

?>
