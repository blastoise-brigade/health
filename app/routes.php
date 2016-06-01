<?php

$app->get('/herokuSetup', "HerokuSetupController:startSetup")->setName("setup");
$app->get('/shine', function ($request, $response, $args) {
  $table1 = $this->shineos->getVisitsList();
  $table2 = $this->shineos->getMedicationList($table1);
  $syncTableCount = \App\Models\SyncModel::count() + 1;
  $instance = new \App\Models\SyncModel;
  $instance->totalvisits = count($table1);
  $instance->save();
  $dataTable = new \App\Models\DataModel;
  $dataTable->truncate();
  foreach ($table1 as $data) {
    $dataTable->create($data);
  }
  $medicationTable = new \App\Models\MedicationModel;
  $medicationTable->truncate();
  foreach ($table2 as $semitable2) {
    foreach ($semitable2 as $data) {
      $medicationTable->create($data);
    }
  }
  return "OK";
});

$app->group("", function () {
  $this->get('/login', "AuthController:showLoginForm")->setName("auth.signin");
  $this->post('/login', "AuthController:verifyEmailAddress");
  $this->get("/login/{token}", "AuthController:verifyToken");
})->add(new App\Middleware\GuestMiddleware($container));

$app->group("", function () {
  $this->get('/', "HomeController:showHome")->setName("home");
  $this->get('/search', "SearchController:showMain")->setName("search");
  $this->post('/search', "SearchController:searchQuery")->setName("search");
  $this->get("/logout", "AuthController:signout")->setName("auth.signout");
  $this->get("/table", "DataViewController:showRegTable")->setName("view.table");
})->add(new App\Middleware\AuthMiddleware($container));

?>
