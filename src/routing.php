<?php

require __DIR__ . '/controllers/recipe-controller.php';

$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($urlPath) {
    case '/':
        browseRecipes();
        break;
    case '/show':
        showRecipe($_GET['id']);
        break;
    case '/add':
        addRecipe();
        break;
    default :
        header('HTTP/1.1 404 Not Found');
}