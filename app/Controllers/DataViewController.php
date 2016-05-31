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

    $id = $request->getAttribute('id');
    $syncTableInitCount = \App\Models\SyncModel::where("id", $id)->count();
    if ($syncTableInitCount == 1) {
      $arrayData = array();
      $medData = array();
      $content = $this->db->table("sync_".$id)->get();
      $medContent = $this->db->table("sync_".$id."_medication")->get();
      $i = 1;
      foreach ($content as $data) {
        $city = $this->db->table("meta_city")->where("cid", $data->city_code)->first();
        $province = $this->db->table("meta_province")->where("pid", $data->province_code)->first();
        $region = $this->db->table("meta_region")->where("rid", $data->region_code)->first();
        array_push($arrayData, array(
          "id" => $i,
          "patient_id" => $data->patient_id,
          "healthcareservice_id" => $data->healthcareservice_id,
          "timestamp" => $data->encounter_datetime,
          "city" => $city->name,
          "province" => $province->pname,
          "region" => $region->rname
        ));
        $i++;
      }
      $i = 1;
      foreach ($medContent as $data) {
        $city = $this->db->table("meta_city")->where("cid", $data->city_code)->first();
        $province = $this->db->table("meta_province")->where("pid", $data->province_code)->first();
        $region = $this->db->table("meta_region")->where("rid", $data->region_code)->first();
        array_push($medData, array(
          "id" => $i,
          "generic_name" => $data->generic_name,
          "brand_name" => $data->brand_name,
          "quantity" => $data->total_quantity,
          "city" => $city->name,
          "province" => $province->pname,
          "region" => $region->rname
        ));
        $i++;
      }
      return $this->view->render($response, 'adminShowRegTableContent.twig', array(
        "tableData" => $arrayData,
        "medData" => $medData
      ));
    }
    else {
      $this->flash->addMessage('error', 'Table not found.');
      return $response->withRedirect($this->router->pathFor("view.table"));
    }
  }

}

?>
