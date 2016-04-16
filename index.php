<?php

require './vendor/autoload.php';

$twig = new Twig_Environment(new Twig_Loader_Filesystem('models'));

echo $twig->render('page.html', array(
  "name" => "Presky",
  "description" => "A new Infrastructure. Enriched, Enlightened."
));

?>
