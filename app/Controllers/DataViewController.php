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
        "created_at" => $data->createdat
      ));
      $i++;
    }
    return $this->view->render($response, 'adminShowSyncTableContent.twig', array(
      "tableData" => $arrayData
    ));

  }

}

?>
