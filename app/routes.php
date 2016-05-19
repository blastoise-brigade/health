<?php

$app->get('/', "HomeController:showHome")->setName("home");

$app->group("", function () {
  $this->get('/login', "AuthController:showLoginForm")->setName("auth.signin");
  $this->post('/login', "AuthController:verifyEmailAddress");
  $this->get("/login/{token}", "AuthController:verifyToken");
})->add(new App\Middleware\GuestMiddleware($container));

$app->group("", function () {
  $this->get("/logout", "AuthController:signout")->setName("auth.signout");
})->add(new App\Middleware\AuthMiddleware($container));

?>
