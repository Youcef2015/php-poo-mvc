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

Modifions maintenant un petit peu notre fichier `web/index.php` pour prendre en compte.

## v0.6 - Routeur

On corse un peu les choses et on passe maintenant à notre routeur. Mais qu'est ce qu'un **routeur** ? C'est un ensemble de classes qui nous permettra de de rediriger une requête vers la bonne action d'un contrôleur.

Dans un premier temps, nous allons implémenter la classe `Route`. Une route se définit par un nom unique, un chemin, un ensemble de paramètres, le contrôleur et l'action vers lesquel elle pointe.

Passons maintenant à la classe `Router`, cette dernière stockera l'ensemble des routes de notre application web. Mais pour mieux visualiser, j'ai créé un **contrôleur** et une **action**.

Normalement les classes sont suffisamment bien commentées pour que vous pouissiez vous y retrouver.

Description d'un route, dans l'ordre des arguments du constructeur :
* `name` : Nom de la route, il est censé être unique
* `path` : Chemin de la route, chaque nom de paramètre doit être précédé de `:`, exemple avec le chemin `/foo/:bar`
* `parameters` : Un tableau contenant la définition de chaque paramètre, l'index correspond au nom du paramètre et sa valeur à la regexp, exemple avec `/foo/:bar` : ` ["bar" => "[\w]*"]`. Remarque : Si la regexp se termine par `+` alors cela veut dire que le paramètre est obligatoire, dans le cas contraire cela doit être `*`
* `controller` : Nom de la classe
* `action` : Nom de la méthode du contrôleur

Procédure : 
* On instancie notre routeur (cf `web/index.php`)
* On ajoute de nouvelles routes dans notre routeur (cf `Router::addRoute`)
* On appelle la méthode `Router::getCurrentRoute` pour récupérer notre route associé à la requête de l'utilisateur
    * On va tester chaque route pour retourner la première qui correspond à la requête
    * La méthode `Route::match` permet dans un premier temps de tester si c'est la bonne et de récupérer les paramètres de la requête
* La méthode `Route::call` appelle dynamiquement l'action du contrôleur

## v0.7 - Contrôleur frontal

Le contrôleur frontale est une classe abstraite qui nous permettra de simplifier le développement de notre application. Il évoluera au fur et à mesure du projet. Par conséquent, chaque contrôleur dans `src/Controller` sera une classe enfant du contrôleur frontal.

Pour le moment, nous avons simplement implémenter une méthode permettant de faire une redirection, et pour cela nous avons besoin du routeur. N'oublions pas non plus de passer notre requête dans le constructeur pour pouvoir récupérer si besoin les données des superglobales.

## v0.8 - Twig

Nous allons maintenant intégrer twig à notre projet, pour simplifier l'écriture des vues.

Dans un premier temps, nous allons installer la dépendances avec **composer** : `composer require twig/twig`.

Une fois que votre dossier `vendor` est à jour, il faut paramétrer twig, et nous faire cela dans notre contrôleur frontal. Nous allons donc implémenter une nouvelle méthode de notre contrôleur frontal qui nous permettra d'afficher une vue simplement.

Nous allons maintenant créer notre premier template twig. L'une des forces de twig est de pouvoir gérer nos vues en utilisant la notion d'héritage, permettant ainsi de ne garder que le strict minimum dans notre vue. Je vous invite à regarder chaque vue pour comprendre le procédé d'héritage.

## v0.9 - Classe Response

À ce stade, votre petit framework devrait être fonctionnel. Mais nous allons ocntinuer de l'otpimiser. Rappelez-vous, nous avons implémenter la classe `Request` car dans la théorie, le client envoie une requête qui est interprétée par le serveur web, et ce dernier renvoie du html. Dans notre framework nous allons plutôt parler de **réponse**. 
Procédure :
* Le framework reçoit une requête
* Le routeur pointe vers la bonne action d'un contrôleur
* Le contrôleur renvoie une réponse
* Notre index envoie la réponse au client

Nous allons créer autant de classe `Response` qu'il y a de réponse différente. Actuellement, nous avons l'affichage d'une vue, la redirection et pour l'occasion j'ai créé la classe `JsonResponse`.

## v0.10 - Base de données

Nous entrons maintenant dans la partie l'une des partie les plus complexe de notre framework maison : les intéraction avec une base de données.

Dans un premier temps, nous allons modifier notre **VirtualHost** en créant de nouvelles variables d'environnement. Elles nous permettrons de récupérer facilement les informations pour se connecter à la base de données. Pourquoi passer par des variables d'environnement ? Car cela évite d'écrire les informations en clair dans nos fichiers et donc cela apporte plus de sécurité.
```apacheconfig
#/etc/apache2/sites-available/php-poo-mvc.conf
<VirtualHost *:80>
    ServerName php-poo-mvc.dev
    DocumentRoot /home/thomas/Developpements/github.com/TBoileau/php-poo-mvc/web
    SetEnv ENV "dev"
    SetEnv DB_HOST "localhost"
    SetEnv DB_NAME "framework"
    SetEnv DB_USER "root"
    SetEnv DB_PASSWORD "azerty"
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

Ce que nous allons créer est un petit [ORM (Object Relational Mapping)](https://fr.wikipedia.org/wiki/Mapping_objet-relationnel). Le but est de centraliser tous nos traitements de manière plus générique. En gros, je n'ai pas envie de devoir écrire plusieurs fois une requête du style `UPDATE ma_table SET mon_chhamp=ma_valeur`, pourquoi ne pas implémenter une méthode pour n'écrire qu'une seule fois cette requête et qu'elle s'adapter à toutes les situations ?

## v0.11 - Routing configuration

On a déà vu ensemble comment créer un routeur. Seulement voilà, la définition de nos routes se font dans le fichier `web/index.php`, ce qui pourrait être optimisé.

Nous allons donc utiliser un fichier de type **yaml** pour lister les routes de notre projet. Et pour cela, nous allons utiliser une librairie, ou plus exactement le [composant **yaml** du framework Symfony](http://symfony.com/doc/current/components/yaml.html).

Nous allons utiliser composer pour l'installer avec la commande suivante `composer require symfony/yaml`.

