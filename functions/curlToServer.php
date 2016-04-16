<?php

function curlToServer($service, $function) {
  $cu = curl_init();
  switch ($service) {
    case "mailgun":
      $url = "https://api.mailgun.net/v2/mg.phillytan.me/messages";
      $curlArgs = array(
        CURLOPT_URL => $url,
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_USERPWD => "api:key-22ed40b32f59bbc35ef31261abf1ee9c",
        CURLOPT_POSTFIELDS => array(
          "from" => "Presky <noreply@presky.phillytan.me>",
          "to" => $_POST['email'],
          "subject" => "Your SSO Login Link for Presky",
          "html" => "<h1>Presky</h1><hr><p><button><a href='http://".$_SERVER['SERVER_NAME']."/login/".$function."'>Sign In</a></button></p>"
        ),
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0
      );
      curl_setopt_array($cu, $curlArgs);
      break;
    case "shineos":
      break;
  }
  return curl_exec($cu);
}

?>
