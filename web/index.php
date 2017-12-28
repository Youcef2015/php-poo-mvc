<?php

// Si nous sommes en environnement de développment, alors nous affichons les erreurs.
if(getenv("ENV") == "dev") {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// On inclus le fichier autoload.php pour pouvoir bénéficier de l'autoloader généré par composer.
require __DIR__."/../vendor/autoload.php";

echo "Hello world !";