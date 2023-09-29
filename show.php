<?php
require_once 'config.php';

// Get id value from the query string
$id = $_GET['id'];
if (empty($id)) {
    die("Wrong input parameter");
}

// Fetching a recipe from database -  assuming the database is okay
$connection = new PDO("mysql:host=" . SERVER . ";dbname=" . DATABASE . ";charset=utf8", USER, PASSWORD);
$query = 'SELECT title, description FROM recipe WHERE id=:id';
$statement = $connection->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$recipe = $statement->fetch(PDO::FETCH_ASSOC);

// Database result check
if (!isset($recipe['title']) || !isset($recipe['description'])) {
    header("HTTP/1.1 404 Not Found");
    die("Recipe not found");
}

// Generate the web page
?>
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
