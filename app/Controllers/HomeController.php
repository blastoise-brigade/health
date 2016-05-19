<?php

namespace App\Controllers;

class HomeController extends BaseController {

  public function showHome($request, $response, $args) {
    if (isset($_SESSION['ps_id'])) {
      $this->flash->addMessage('error', 'Your account has been suspended.');
      $this->flash->addMessage('warning', 'We are going to be performing a maintenance update.');
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
    else {
      return $this->view->render($response, 'home.twig');
    }
  }

}

?>
