<?php

namespace App\Controllers;

class HerokuSetupController extends BaseController {

  public function startSetup($request, $response, $arguments) {
    return $this->view->render($response, 'herokuSetup.twig');
  }

}

 ?>
