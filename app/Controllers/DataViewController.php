<?php

namespace App\Controllers;

class DataViewController extends BaseController {

  public function showSyncTable($request, $response, $arguments) {
    $syncTable = \App\Models\SyncModel::get();
    $arrayData = array();
    $i = 1;
    foreach ($syncTable as $data) {
      array_push($arrayData, array(
        "id" => $i,
        "totalvisits" => $data->totalvisits,
        "created_at" => $data->createdat,
        "tb_id" => $data->id
      ));
      $i++;
    }
    $messages = $this->flash->getMessages();
    if (count($messages) != 0) {
      $messageArray = AuthController::formFlashArray($messages);
      $renderArray = array(
        "flash" => true,
        "flashMessages" => $messageArray,
        "tableData" => $arrayData
      );
    }
    else {
      $renderArray = array(
        "flash" => false,
        "tableData" => $arrayData
      );
    }
    return $this->view->render($response, 'adminShowSyncTableContent.twig', $renderArray);
  }

  public function showRegTable($request, $response, $arguments) {
    $arrayData = array();
    $medData = array();
    $content = $this->db->table("sync_data")->get();
    $medContent = $this->db->table("sync_medication")->get();
    $i = 1;
    foreach ($content as $data) {
      array_push($arrayData, array(
        "id" => $i,
        "patient_id" => $data->patient_id,
        "healthcareservice_id" => $data->healthcareservice_id,
        "timestamp" => $data->encounter_datetime,
        "city" => $data->city,
        "province" => $data->province,
        "region" => $data->region
      ));
      $i++;
    }
    $i = 1;
    foreach ($medContent as $data) {
      array_push($medData, array(
        "id" => $i,
        "generic_name" => $data->generic_name,
        "brand_name" => $data->brand_name,
        "quantity" => $data->total_quantity,
        "city" => $data->city,
        "province" => $data->province,
        "region" => $data->region
      ));
      $i++;
    }
    return $this->view->render($response, 'adminShowRegTableContent.twig', array(
      "tableData" => $arrayData,
      "medData" => $medData
    ));
  }

}

?>
