# Marmiwild - épisode 1

Le site des wilders qui aiment la cuisine autant que le code.
{: .text-center }

Tu en es maintenant à quelques semaines de PHP. Les boucles et les conditions n'ont plus de secrets pour toi, mais après toutes ces heures à réfléchir sur des quêtes, tu ne peux plus ignorer ton estomac qui crie famine. La traditionnelle tartine banane/confiture/mortadelle ne suffira pas cette fois, ton corps réclame de la grande gastronomie. Un seul moyen de le satisfaire : développer un site de recette de cuisine !


## Un grand repas commence par de petits amuse-bouches

![Maïté gif](https://media.tenor.com/SF3BV_uprawAAAAC/ma%C3%AFt%C3%A9-maite.gif)
{: .text-center }

Bon, d'accord il y a plus simple pour combler tes envies de tarte au maroilles. Il y a déjà de nombreux sites existants qui proposent des recettes de cuisine. Cet atelier ne va pas t'aider à révolutionner le marché, mais t'apprendre des premières bonnes pratiques pour construire un site en PHP. Une manière de nourrir ton âme de dev avant de t'adonner aux nourritures terrestres. Pour t'aider à démarrer, de valeureux formateurs t'ont préparé une première recette à base de PHP.  

Clone ce dépôt grâce au lien donné au début de cette page ⬆ à la section <a href="#input-clone"><i class="bi bi-code-slash"></i> Code</a>
{: .alert-info } 

## Objectifs

* Prendre en main les premières pages (liste des recettes et fiches détaillées)
* Isoler la communication entre la BDD et l'affichage
* Créer une nouvelle page pour ajouter une recette à la base de données
* <del>Gagner ta place à la prochaine édition de Top Chef</del>

## 1. Aiguise tes couteaux

Tu trouveras à la racine du projet un fichier `database.sql`. Il contient des instructions SQL pour construire la base de données de Marmiwild. Mais tu dois d'abord créer une base de données !

Une fois connecté à MySQL, utilise la commande `CREATE DATABASE`, comme ceci par exemple :

```bash
CREATE DATABASE marmiwild;
```

Utilise ensuite ta base de données et charge le fichier **database.sql** avec la commande `SOURCE` en indiquant le chemin complet vers le fichier depuis la racine de ton ordinateur :

```bash
USE marmiwild;
SOURCE /path/to/my/documents/database.sql;
```

>💡 Pour trouver le chemin réel `/path/to/my/documents/` en fonction de ton arborescence, deux possibilités :  
>- Depuis ton IDE, fais un clique droit sur le fichier **database.sql** et sélectionne **Copier le chemin** (ou Copy path).  
>- Ou tape tout simplement la commande `pwd` dans ton terminal en racine de ton dossier 😛.

Vérifie que tout s'est bien passé :

```bash
DESCRIBE recipe;
```

Tu devrais obtenir une réponse comme celle-ci dans ton terminal :

```
+-------------+---------------+------+-----+---------+----------------+
| Field       | Type          | Null | Key | Default | Extra          |
+-------------+---------------+------+-----+---------+----------------+
| id          | int(11)       | NO   | PRI | NULL    | auto_increment |
| title       | varchar(100)  | NO   |     | NULL    |                |
| description | varchar(2000) | NO   |     | NULL    |                |
+-------------+---------------+------+-----+---------+----------------+
```

## 2. Prépare tes ingrédients

Retourne à la racine du projet, et crée un fichier `config.php`, en copiant le fichier `config.php.dist` déjà existant, puis en modifiant les constantes avec des informations de connexion valide pour ton serveur de base de données.  

N'efface pas ou ne renomme pas le fichier `config.php.dist` qui sert de modèle à la création de `config.php` et qui est versionné.  
En revanche, `config.php` doit être ajouté dans le `.gitignore` (et c'est déjà le cas 🤓) afin de **ne pas** être versionné car il contient des données sensibles et ne doit donc pas être partagé !
{: .alert-warning }

```php
<?php
define('USER', 'user'); // un nom d'utilisateur : idéalement pas root ;)
define('PASSWORD', 'password'); // le mot de passe associé
define('DATABASE', 'database'); // le nom de la base de donnée
define('SERVER', 'localhost'); // l'emplacement du serveur : à ne pas modifier
```

Le projet devrait être fonctionnel. Pour s'en assurer, lance un serveur local :

```bash
php -S localhost:8000
```

Et découvre la page d'accueil de ton nouveau site à l'adresse [http://localhost:8000](http://localhost:8000).  
Le style ne fait pas rêver, mais laisse ce point de côté pour l'instant.

## 3. Allume le feu

Si tu ouvres le fichier `index.php`, tu peux remarquer que le code est déjà un peu « rangé ».

```php
<?php
require_once 'config.php';

// Fetching all recipes from database - assuming the database is okay
$connection = new PDO("mysql:host=" . SERVER . ";dbname=" . DATABASE . ";charset=utf8", USER, PASSWORD);
$statement = $connection->query('SELECT id, title FROM recipe');
$recipes = $statement->fetchAll(PDO::FETCH_ASSOC);
```

👆 La partie PHP au début ouvre une connection avec la base de données, et fait une requête pour récupérer les recettes.
Pas d'HTML qui traine dans cette partie. Il y a **exclusivement** la communication avec la base de données.

👇 Dans la partie HTML, il y a un peu de PHP bien sûr :

```php
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>List of Recipes</title>
    </head>
    <body>
        <ul>
            <?php foreach ($recipes as $recipe): ?>
            <li>
                <a href="show.php?id=<?= $recipe['id'] ?>">
                    <?= $recipe['title'] ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </body>
</html>
```

Mais dans cette partie PHP, rien n'indique que `$recipes` a été initialisé en interrogeant une base de données.  

C'est une bonne pratique de ranger ainsi son code en réunissant des instructions selon leur utilité&nbsp;: d'un côté la gestion des données, de l'autre leur affichage.
{: .alert-info }

Tu vas pousser plus loin cette idée en rangeant carrément ces portions de code dans des fichiers propres.

## 4. Fais rissoler les légumes

D'abord la partie affichage ! 

En partant de la racine du projet, crée l'arboresence de dossier `src/views` puis, crée un fichier `indexRecipe.php`, ce qui donne `src/views/indexRecipe.php`.  

Ouvre-le pour y déplacer tout le code gérant l'affichage (HTML ainsi que la boucle PHP qui ne fait que générer une liste HTML) :

```php
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>List of Recipes</title>
    </head>
    <body>
        <a href="/add.php">Add</a>
        <h1>List of Recipes</h1>
        <ul>
            <?php foreach ($recipes as $recipe) : ?>
            <li>
                <a href="show.php?id=<?= $recipe['id'] ?>">
                    <?= $recipe['title'] ?>
                </a>
            </li>
            <?php endforeach ?>
        </ul>
    </body>
</html>
```

À partir de maintenant, la page `index.php` qui se situe à la racine va uniquement inclure le fichier nouvellement créé contenant l'affichage (la vue), pour devenir :

```php
<?php

// plein de code PHP nécessaire à la connexion avec la base de données

$recipes = $statement->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/src/views/indexRecipe.php';
```

Rafraichis la page dans ton navigateur : rien n'a changé !  

Bonne nouvelle, c'est que tes modifications fonctionnent. Si tu as une erreur, ou un warning, vérifie ton code, tu as peut être mal copié ou oublié une partie de ton code.

**Première vraie séparation** : elle fonctionne parce qu'avant d'inclure la vue, le script `index.php` déclare une variable `$recipes`.
Cette variable est « consommée » par la vue dans la boucle `foreach`, même si elle vient d'un autre fichier.
Rappelle-toi que les instructions `require` fonctionnent plus ou moins comme des copiés-collés.
Et c'est ce que tu as fait : tu n'as pas changé le code, tu l'as juste déplacé dans un autre fichier avant de le réinclure.
Une fois le `require` résolu, les instructions PHP sont exactement les mêmes qu'avant.

Tu peux vérifier que la navigation entre les pages fonctionne toujours.

## 5. Un peu d'assaisonnement

Pourquoi autant d'énergie pour au final arriver au même résultat ?

Dans la vraie vie, tu trouveras bien pratique de pouvoir réutiliser une même vue pour plusieurs pages.  
Pour l'instant, c'est sortir la grosse artillerie.  
Rappelle-toi que le but est de te montrer des bonnes pratiques : elles prendront du sens sur de plus gros projets.  
Sur ce premier projet, tu peux déjà constater que c'est très facile à mettre en place : un simple copié-collé.

Continuons sur ce bon chemin en créant un module spécial pour la gestion des données.

En partant de la racine du projet, crée les dossiers et fichiers pour obtenir `src/models/recipe-model.php`. Ouvre le pour y placer le code suivant :

```php
<?php 

function getAllRecipes(): array
{
    $connection = new PDO("mysql:host=" . SERVER . ";dbname=" . DATABASE . ";charset=utf8", USER, PASSWORD);

    $statement = $connection->query('SELECT id, title FROM recipe');
    $recipes = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $recipes;
}
```

Tu peux maintenant transformer le fichier `index.php` à la racine de ton projet pour qu'il devienne :

```php
<?php
require_once 'config.php';
require __DIR__.'/src/models/recipe-model.php';

// Fetching all recipes
$recipes = getAllRecipes();

// Generate the web page
require __DIR__.'/src/views/indexRecipe.php';
```

Tu as mis le code qui communique avec la base de données dans une fonction plutôt que directement dans ton fichier.

Pourquoi cette petite touche en plus ?  
En fait, dans `index.php`, tu veux un outil qui produise un tableau de données, mais en gardant la liberté  de choisir le nom de la variable qui va contenir le tableau. Le nom `$recipes` est imposé par la vue, et le "modèle", ton outil qui va te donner accès aux données, ne sait pas à quelle vue elles vont être transmises.

Pas plus d'ailleurs que la vue ne saura comment a été remplie `$recipes`.

C'est `index.php` qui sait tout ça : il joue ici le rôle de **chef d'orchestre**, et fait la liaison entre modèle et vue. Peut-être as-tu déjà une idée de ce qui est en train de se tramer, mais nous en reparlerons dans un autre atelier 😉

## 6. Une deuxième commande arrive

Ton fichier `index.php` racine a gagné en modularité. 

Fais de même pour le fichier `show.php` situé en racine.
Extrais d'abord le code HTML dans un nouveau fichier `src/views/showRecipe.php`. 

Essaie de le faire par toi même. En cas de difficultés, tu trouveras la solution ci-dessous 👇 :

```php
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?= $recipe['title'] ?></title>
    </head>
    <body>
        <a href="/">Home</a>
        <h1><?= $recipe['title'] ?></h1>
        <p>
            <?= $recipe['description'] ?>
        </p>
    </body>
</html>
```

Ajoute ensuite une nouvelle fonction dans *recipe-model.php*. Celle-ci te donnera accès à une recette en fonction d'un ID donné. 

De la même façon, essaie de faire la manipulation tout seul. En cas de pépin, jette un œil à la réponse 👇 :

```php
function getRecipeById(int $id): array
{
    $connection = new PDO("mysql:host=" . SERVER . ";dbname=" . DATABASE . ";charset=utf8", USER, PASSWORD);

    $query = 'SELECT title, description FROM recipe WHERE id=:id';
    $statement = $connection->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    $recipe = $statement->fetch(PDO::FETCH_ASSOC);

    return $recipe;
}
```

Ton fichier `show.php` doit maintenant ressembler à :

```php
<?php

require_once 'config.php';
require __DIR__.'/src/models/recipe-model.php';

// Get id value from the query string
$id = $_GET['id'];
if (empty($id)) {
    die("Wrong input parameter");
}

// Fetching a recipe
$recipe = getRecipeById($id);

// Database result check
if (!isset($recipe['title']) || !isset($recipe['description'])) {
    header("HTTP/1.1 404 Not Found");
    die("Recipe not found");
}

// Generate the web page
require __DIR__.'/src/views/showRecipe.php';
```

Ton modèle commence à être réutilisable, et devient une vraie boîte à outils dédiée à la gestion de recette.

Si tu regardes le code, tu peux déjà apprécier la manière dont s'est rangé. Mais tu peux constater que tout n'est pas encore optimisé :


```php
<?php

function getAllRecipes(): array
{
    $connection = new PDO("mysql:host=" . SERVER . ";dbname=" . DATABASE . ";charset=utf8", USER, PASSWORD);

    // ...
}

function getRecipeById(int $id): array
{
    $connection = new PDO("mysql:host=" . SERVER . ";dbname=" . DATABASE . ";charset=utf8", USER, PASSWORD);

    // ...
}
```

Tu peux voir ici une belle répétition de code. Tu n'as pas oublié le principe *DRY*, j'en suis sûr.
Transforme le fichier pour lui donner cette forme :

```php
function createConnection(): PDO
{
    return new PDO("mysql:host=" . SERVER . ";dbname=" . DATABASE . ";charset=utf8", USER, PASSWORD);
}

function getAllRecipes(): array
{
    $connection = createConnection();

    // ...
}

function getRecipeById(int $id): array
{
    $connection = createConnection();

    // ...
}
```

Et bien sûr, vérifie que tout marche toujours 😉

## 7. Renouvelle la carte

Nous y voilà, un dossier de vues dédiées à l'affichage, un modèle *DRY* spécialisé dans la gestion de recettes, le tout dans un dossier source `src`.  
Et toujours, à la racine les pages consultables par les visiteurs de ton site.
Maintenant que tout est en place, à toi de compléter le site avec une page d'ajout de recette.
Cela implique plusieurs tâches :

* Rajoute une fonction d'enregistrement de recette dans `src/models/recipe-model.php`.  
  Quelque chose comme ça :

```php
function saveRecipe(array $recipe): void
{
    $connection = createConnection();

    // lance une requête SQL pour engistrer la recette $recipe
}
```

* Crée une vue `src/views/form.php` qui contient un formulaire de création (méthode POST). Pour rappel, une recette a :
  - un titre : requis, 255 caractères max.
  - une description : requis.
* Crée une page `add.php` à la racine du projet. Tu peux partir de cette base :

```php
<?php

require_once 'config.php';
require __DIR__ . '/src/models/recipe-model.php';

$errors = [];
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $recipe = /* les données nettoyées de $_POST */

    if (empty($errors)) {
        saveRecipe($recipe);
        header('Location: /');
    }
}

require __DIR__ . '/src/views/form.php';
```
Bravo, tu peux maintenant ajouter de nouvelles recettes pour enrichir ton site !