<?php

namespace App\Controllers;

class DataViewController extends BaseController {

  public function showSyncTable($request, $response, $arguments) {
    $syncTable = \App\Models\SyncModel::get();
    $arrayData = array();
    foreach ($syncTable as $data) {
      array_push($arrayData, array(
        "id" => $data->id,
        "totalvisits" => $data->totalvisits,
        "created_at" => $data->createdat
      ));
    }
    return $this->view->render($response, 'adminShowSyncTableContent.twig', array(
      "tableData" => $arrayData
    ));

  }

}

?>
