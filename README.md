# PHP POO MVC

## Pré-requis

* [ ] Maîtriser PHP 7
* [ ] Avoir de solides connaissances en POO
* [ ] Connaître les grands principes de MVC
* [ ] Savoir utiliser composer
* [ ] Maîtriser l'autoload

## v0.1 - Initialisation du projet

La première étape est de créer un répertoire contenant notre projet. Suite à cela, on commence tout naturellement par initialiser notre projet avec **composer** avec la commande `composer init`.
```
composer init
Package name (<vendor>/<name>) [thomas/php-poo-mvc]: tboileau/php-poo-mvc
Description []: Concevoir un framework PHP POO MVC
Author [TBoileau <t-boileau@email.com>, n to skip]:
Minimum Stability []: dev
Package Type (e.g. library, project, metapackage, composer-plugin) []:
License []: MIT

Define your dependencies.

Would you like to define your dependencies (require) interactively [yes]? no
Would you like to define your dev dependencies (require-dev) interactively [yes]? no

{
    "name": "tboileau/php-poo-mvc",
    "description": "Concevoir un framework PHP POO MVC",
    "license": "MIT",
    "authors": [
        {
            "name": "TBoileau",
            "email": "t-boileau@email.com"
        }
    ],
    "minimum-stability": "dev",
    "require": {}
}

Do you confirm generation [yes]?
```

## v0.2 - Création des dossiers

Maintenant que notre projet est prêt à démarrer, il faut créer les dossiers :
* **app** contiendra l'ensemble des classes de notre *framework maison*
* **src** contiendra nos contrôleurs, modèles et vues
* **web** contiendra tous les fichiers que seul l'utilisateur aura accès

## v0.3 - Autoload

Après avoir créer nos dossiers, il faut maintenant gérr **l'autoload**, et composer nous facilite grandement la tâche.
```
{
    "name": "tboileau/php-poo-mvc",
    "description": "Concevoir un framework PHP POO MVC",
    "license": "MIT",
    "authors": [
        {
            "name": "TBoileau",
            "email": "t-boileau@email.com"
        }
    ],
    "minimum-stability": "dev",
    "require": {},
    "autoload": {
        "psr-4": {
            "App\\": "app",
            "": "src"
        }
    }
}
```
Ce qui nous intéresse ici c'estla nouvelle entrée dans notre objet json : `àutoload`. Pour respecter les dernièrs standards de PHP, nous utiliserons le [psr-4](http://www.php-fig.org/psr/psr-4/).
La déclaration de nos espaces de noms est assez simple, on définie dans un premier temps un préfixe `App\` puis on lui affecte comme valeur le nom du dossier `app`.

*On remarque qu'il n'y a pas de préfixe pour le dossier `src`, ne vous inquiétez pas c'est normal. C'est un choix personnel, vous pouvez tout à fait en mettre un, mais il faudra donc le repercuter pour chaque `namespace` dans vos classes.*

J'ai créé les dossiers `Controller` et `Model` dans le dossier `src`, pour vous donner un exemple, si on crée un nouveau contrôleur, voici à quoi ressemblera le **namespace** :
```php
<?php

namespace Controller;

class FooController
{
    
}
```


Il ne faut pas oublier de mettre à jour composer avec la commande `composer update`. Il va générer un ensemble de fichier pour gérer l'autoload.
```
composer update
Loading composer repositories with package information
Updating dependencies (including require-dev)
Nothing to install or update
Generating autoload files
``` 
Vous avez sans doute remarqué qu'un nouveau dossier a vu le jour `vendor`. Ce dossier contient les fichiers nécessaire à la gestion de l'autoload par composer, mais contiendra aussi nos dépendances externes comme **twig** par exemple.

N'oubliez pas d'ajouter le dossier vendor dans le fichier `.gitignore`

*Je vous invite à regarder les fichiers générés par composer pour comprendre unpeu mieux le fonctionnement de son autoload.*

## v0.4 - Index.php

Voilà, on rentre enfin dans le vif du sujet, on va pouvoir écrire nos premières lignes de codes. On commence évidemment par notre fichier `index.php`, ce sera la porte d'entrée de notre application web.

À ce stade, il faudra créer votre premier `VirtualHost`, pour ma part j'utilise **apache2** :
```apacheconfig
#/etc/apache2/sites-available/php-poo-mvc.conf
<VirtualHost *:80>
    ServerName php-poo-mvc.dev
    DocumentRoot /home/thomas/Developpements/github.com/TBoileau/php-poo-mvc/web
    SetEnv ENV "dev"
    <Directory /home/thomas/Developpements/github.com/TBoileau/php-poo-mvc/web>
        Require all granted
        AllowOverride All
        Order Allow,Deny
        Allow from All
        <IfModule mod_rewrite.c>
            Options -MultiViews
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ index.php [QSA,L]
        </IfModule>
    </Directory>
</VirtualHost>
```

N'oublions pas d'ajouter `php-poo-mvc.dev` dans notre fichier `etc/hosts` pour lui préciser que ce nom de domaine doit pointer en local (sur notre machine et pas sur internet).
```
127.0.0.1   php-poo-mvc.dev
```

Maintenant activons notre **VirtualHost** avec la commande suivante : `sudo a2ensite php-poo-mvc`. Et enfin pour activer notre nouvelle configuration, il faut relancer **apache2** avec la commande suivante `sudo service apache2 reload`.

Revenons un instant sur notre **VirtualHost**, il y a une ligne que j'aimerais détailler : `SetEnv ENV "dev"`. Comme vous le savez, lorsque nous travaillons en local, et donc en environnement de développement, il est intelligent de distinguer les différents environnement, car peut être qu'en production nous n'afficherons pas les erreurs de PHP. C'est pour cela, que j'ai créé une variable d'environnement qui s'appelle `ENV`.

## v0.5 - Classe Request

On va pouvoir commencer à implémenter notre première classe. Comme vous êtesz censé le savoir, lorsqu'un utilisateur va sur une page web, une requête est envoyé au serveur web, ce dernier effectue un traitement puis renvoie une réponse en HTML.

Nous allons donc commencer par implémenter la classe `Request`. Cette classe nous permettra de gérer facilement les [superglobales de PHP](http://php.net/manual/fr/language.variables.superglobals.php). Cela nous permettra de récupérer toutes les informations de la requête envoyée par l'utilisateur.

À ce stade, vous êtes censé pouvoir comprendre facilement la classe `Request`. La méthode static `createFromGlobals` permet de récupérer une instance de notre classe `Request`, en suvant légèrement les principes du **Singleton**.

Modifions maintenant un petit peu notre fichier `web/index.php` pour prendre en compte