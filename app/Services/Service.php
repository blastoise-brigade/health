<?php

namespace App\Services;

use GuzzleHttp\Client;

abstract class Service {

  protected $container;
  protected $client;

  public function __construct(Client $client, $container) {
    $this->container = $container;
    $this->client = $client;
  }

  public function authorizeUrl()
  {
      return $this->getAuthorizeUrl();
  }

  public function getUser($code)
  {
      return $this->getUserByCode($code);
  }

  public function __get($property)
  {
      if ($this->container->{$property}) {
          return $this->container->{$property};
      }
  }

}

?>
