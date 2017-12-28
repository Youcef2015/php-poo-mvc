<?php

// On inclus le fichier autoload.php pour pouvoir bénéficier de l'autoloader généré par composer.
require __DIR__."/../vendor/autoload.php";

use App\Request;

$request = Request::createFromGlobals();

// Si nous sommes en environnement de développment, alors nous affichons les erreurs.
if($request->getEnv("ENV") == "dev") {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

echo "Hello world !";