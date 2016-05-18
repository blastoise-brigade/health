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

  public function showLoginForm($request, $response, $args) {
    return $this->view->render($response, 'login.twig', array(
      "showForm" => true,
      "mainText" => "Login using:"
    ));
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
      return $this->view->render($response, 'login.twig', array(
        "showForm" => false,
        "mainText" => "An email has been sent to: " . $email
      ));
    }
    else {
      return $this->view->render($response, 'login.twig', array(
        "showForm" => true,
        "mainText" => "The user specified is not found nor authorized to access."
      ));
    }
  }

  public function verifyToken($request, $response, $args) {
    $token = $request->getAttribute('token');
    $user = $this->db->table("user")->where("token", $token)->first();
    $userCount = count($user);
    if ($userCount == 1) {
      $_SESSION['ps_id'] = $user->id;
      $this->db->table("user")->where("token", $token)->update(["token" => NULL]);
      return $this->view->render($response, 'login.twig', array(
        "showForm" => false,
        "mainText" => "You are logged in."
      ));
    }
    else {
      return $this->view->render($response, 'login.twig', array(
        "showForm" => true,
        "mainText" => "Error: Invalid SSO Token"
      ));
    }
  }

  public function signout($request, $response, $args) {
    unset($_SESSION['ps_id']);
    return $response->withRedirect($this->router->pathFor("auth.signin"));
  }

}

?>
