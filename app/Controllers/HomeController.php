<?php

namespace App\Controllers;

class HomeController extends BaseController {

  public function showHome($request, $response, $args) {
    if (isset($_SESSION['ps_id'])) {
      return $this->view->render($response, 'adminHome.twig');
    }
    else {
      return $this->view->render($response, 'home.twig');
    }
  }

}

?>
