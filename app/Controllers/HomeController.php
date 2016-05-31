<?php

namespace App\Controllers;

class HomeController extends BaseController {

  public function showHome($request, $response, $args) {
    $messages = $this->flash->getMessages();
    if (count($messages) != 0) {
      $messageArray = AuthController::formFlashArray($messages);
      $renderArray = array(
        "flash" => true,
        "flashMessages" => $messageArray
      );
      return $this->view->render($response, 'adminHome.twig', $renderArray);
    }
    else {
      return $this->view->render($response, 'adminHome.twig');
    }
  }

}

?>
