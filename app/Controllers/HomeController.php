<?php

namespace App\Controllers;

class HomeController extends BaseController {

  public function showHome($request, $response, $args) {
    return $this->view->render($response, 'home.twig');
  }

}

?>
