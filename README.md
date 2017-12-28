# PHP POO MVC

## Pré-requis

* [ ] Maîtriser PHP 7
* [ ] Avoir de solides connaissances en POO
* [ ] Connaître les grands principes de MVC
* [ ] Savoir utiliser composer

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