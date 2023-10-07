# TodoList
Projet d'amélioration une application existante de ToDo &amp; Co. Projet 8 du parcours PHP Symfony sur Openclassrooms.

## Environnement utilisé durant le développement
* [Symfony 6.3.21 LTS](https://symfony.com/doc/current/setup.html#start-coding) 
* [Composer 2.5.5](https://getcomposer.org/doc/00-intro.md)
* MAMP 6 (985)
    * Apache 2.4.54
    * PHP 8.2.0
    * MySQL 8.0.31
      
## Installation
1- Clonez le repository GitHub dans le dossier voulu :
```
    git clone https://github.com/zaynakaadan/TodoList.git
```
2- Placez vous dans le répertoire de votre projet et installez les dépendances du projet avec la commande de [Composer](https://getcomposer.org/doc/00-intro.md) :
```
    composer install
```
3- Configurez vos variables d'environnement dans le fichier `.env` tel que :

* La connexion à la base de données :
  ```
     DATABASE_URL="mysql://root:@127.0.0.1:3306/ToDoList?serverVersion=8.0.32&charset=utf8mb4"
  ```
3- Si le fichier `.env` est correctement configuré, créez la base de données avec la commande ci-dessous:
```
    php bin/console doctrine:database:create
```
4- Créez les différentes tables de la base de données :
```
    php bin/console doctrine:schema:create
```
5- Installer des données fictives avec des fixtures pour agrémenter le site :
```
    php bin/console doctrine:fixtures:load
```
6- Votre projet est prêt à l'utilisation ! Pour utiliser l'application dans un environnement local, veuillez vous renseigner sur cette [documentation](https://symfony.com/doc/current/setup.html#running-symfony-applications).

## Tester l'application (optionnel)
Des tests ont été écrits afin de vérifier l'intégrité et le fonctionnement de l'application. Afin de pouvoir les lancer :

1- Créez un fichier `.env.test.local`, avec une variable d'environnement qui contient l'adresse de la base de données de test voulue:
```
    DATABASE_URL="mysql://root:@127.0.0.1:3306/ToDoListTest?serverVersion=8.0.32&charset=utf8mb4"
```
2- Créez la base de données de test :
```
    php bin/console doctrine:database:create --env=test
```
3- Lancez le test via la commande :
```
    vendor/bin/phpunit
```
4-ous pouvez générer un test de couverture de code avec la commande ci-dessous (Le résultat est disponible à dans `./html/test-coverage/index.html`:
```
    vendor/bin/phpunit --coverage-html deliverables/test-coverage
```
