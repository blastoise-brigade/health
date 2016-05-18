<?php

$app->get('/', "HomeController:showHome")->setName("home");
$app->get('/login', "AuthController:showLoginForm")->setName("auth.signin");
$app->post('/login', "AuthController:verifyEmailAddress");
$app->get("/login/{token}", "AuthController:verifyToken");
$app->get("/logout", "AuthController:signout")->setName("auth.signout");

?>
