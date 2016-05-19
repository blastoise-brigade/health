<?php

namespace App\Controllers;

class AuthController extends BaseController {

  private function generateRandomString() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $function = '';
    for ($i = 0; $i < 16; $i++) {
        $function .= $characters[rand(0, $charactersLength - 1)];
    }
    return $function;
  }

  public function formFlashArray($messages) {
    $messageArray = array();
    if (isset($messages['error'])) {
      array_push($messageArray, array(
        "messageType" => "error",
        "messageArray" => $messages['error']
      ));
    }
    if (isset($messages['warning'])) {
      array_push($messageArray, array(
        "messageType" => "warning",
        "messageArray" => $messages['warning']
      ));
    }
    if (isset($messages['good'])) {
      array_push($messageArray, array(
        "messageType" => "good",
        "messageArray" => $messages['good']
      ));
    }
    return $messageArray;
  }

  public function showLoginForm($request, $response, $args) {
    $messages = $this->flash->getMessages();
    if (count($messages) != 0) {
      $messageArray = $this->formFlashArray($messages);
      $renderArray = array(
        "showForm" => true,
        "flash" => true,
        "flashMessages" => $messageArray
      );
    }
    else {
      $renderArray = array(
        "showForm" => true,
        "flash" => false
      );
    }
    return $this->view->render($response, 'login.twig', $renderArray);
  }

  public function verifyEmailAddress($request, $response, $args) {
    $email = $request->getParam('email');
    $user = $this->db->table("user")->where("email", $email)->get();
    $userCount = count($user);
    if ($userCount == 1) {
      $function = $this->generateRandomString();
      $this->db->table("user")->where("email", $email)->update(["token" => $function]);
      $this->mailgun->sendMessage($this->config->get("mailgun.domain"), array(
        'from'    => "Presky <".$this->config->get("mailgun.no_reply_email_address").">",
        'to'      => $email,
        'subject' => 'Your SSO Login Link for Presky',
        'html'    => "<h1>Presky</h1><hr><p><button><a href='http://".$_SERVER['SERVER_NAME']."/login/".$function."'>Sign In</a></button></p>"
      ));
      $this->flash->addMessage('warning', 'A Verification Email has been sent to: '.$email);
      return $response->withRedirect($this->router->pathFor("auth.signin"));
    }
    else {
      $this->flash->addMessage('error', 'The user specified is not found nor authorized to access.');
      return $response->withRedirect($this->router->pathFor("auth.signin"));
    }
  }

  public function verifyToken($request, $response, $args) {
    $token = $request->getAttribute('token');
    $user = $this->db->table("user")->where("token", $token)->first();
    $userCount = count($user);
    if ($userCount == 1) {
      $_SESSION['ps_id'] = $user->id;
      $this->db->table("user")->where("token", $token)->update(["token" => NULL]);
      $this->flash->addMessage('good', 'You are logged in.');
      return $response->withRedirect($this->router->pathFor("home"));
    }
    else {
      $this->flash->addMessage('error', 'Invalid SSO Token');
      return $response->withRedirect($this->router->pathFor("auth.signin"));
    }
  }

  public function signout($request, $response, $args) {
    unset($_SESSION['ps_id']);
    $this->flash->addMessage('warning', 'You have been logged out.');
    return $response->withRedirect($this->router->pathFor("auth.signin"));
  }

}

?>
