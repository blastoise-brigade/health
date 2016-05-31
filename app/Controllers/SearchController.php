<?php

namespace App\Controllers;

class SearchController extends BaseController {

  public function searchQuery($request, $response, $args) {
    $search = $request->getParam('query');
    $array = array();
    $r1 = $this->db->table("meta_city")->where("name", "like", "%" . $search . "%")->get();
    $r2 = $this->db->table("meta_province")->where("pname", "like", "%" . $search . "%")->get();
    $r3 = $this->db->table("meta_region")->where("rname", "like", "%" . $search . "%")->get();

    foreach ($r1 as $data) {
      array_push($array, $data->name);
    }
    foreach ($r2 as $data) {
      array_push($array, $data->pname);
    }
    foreach ($r3 as $data) {
      array_push($array, $data->rname);
    }
    return $this->view->render($response, 'results.twig', [ "data" => $array ]);

  }

  public function showMain($request, $response, $args) {
      return $this->view->render($response, 'search.twig');
  }

}

?>
