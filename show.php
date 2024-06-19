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
if (!isset($recipe['title'], $recipe['description'])) {
    header("HTTP/1.1 404 Not Found");
    die("Recipe not found");
}

// Generate the web page
require __DIR__.'/src/views/showRecipe.php';