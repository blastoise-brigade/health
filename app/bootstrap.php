<?php

session_start();

require '../vendor/autoload.php';

$container = new \Slim\Container;

$container['config'] = function ($c) {
  return new \Noodlehaus\Config(array(
    "../config/db.php",
    "../config/mailgun.php"
  ));
};

$container['db'] = function($c) {
  $capsule = new \Illuminate\Database\Capsule\Manager;
  $capsule->addConnection(array(
    "driver" => $c['config']->get("db.provider"),
    "host" => $c['config']->get("db.server.host"),
    "database" => $c['config']->get("db.server.database"),
    "username" => $c['config']->get("db.server.username"),
    "password" => $c['config']->get("db.server.password"),
    "charset" => "utf8",
    "collation" => "utf8_unicode_ci",
    "prefix" => $c['config']->get("db.server.prefix")
  ));
  $capsule->setAsGlobal();
  $capsule->bootEloquent();
  return $capsule;
};

$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages();
};

$container['mailgun'] = function ($c) {
  $client = new \Http\Adapter\Guzzle6\Client();
  return new Mailgun\Mailgun($c['config']->get("mailgun.private_api_key"), $client);
};

$container['view'] = function ($c) {
  $view = new \Slim\Views\Twig('../views');

  $view->addExtension(new \Slim\Views\TwigExtension(
    $c['router'],
    $c['request']->getUri()
  ));

  return $view;
};

$container['AuthController'] = function ($c) {
  return new \App\Controllers\AuthController($c);
};

$container['HomeController'] = function ($c) {
  return new \App\Controllers\HomeController($c);
};

$app = new \Slim\App($container);

require 'routes.php';

?>
