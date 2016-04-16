<?php

require './vendor/autoload.php';

$twig = new Twig_Environment(new Twig_Loader_Filesystem('models'));

echo $twig->render('page.html', array(
  "name" => "Health by Blastoise Brigade",
  "description" => "A new Infrastructure. Enriched, Enlightened."
));

?>
