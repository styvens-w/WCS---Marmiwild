<?php

require_once 'config.php';
require __DIR__ . '/src/models/recipe-model.php';

$errors = [];
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (!isset($_POST["title"]) || $_POST["title"] === '' || strlen($_POST["title"]) > 255 ) {

        $errors[] = "Le titre est obligatoire et doit faire moin de 255 caractères";

    } else {

        $title = trim(htmlentities($_POST['title']));
        $recipe['title'] = $title;

    }

    if (!isset($_POST["description"]) || $_POST["description"] === '' || strlen($_POST["description"]) > 2000 ) {

        $errors[] = "Le description est obligatoire et doit faire moin de 2000 caractères";

    } else {

        $description = trim(htmlentities($_POST['description']));
        $recipe['description'] = $description;

    }

    if (empty($errors)) {
        saveRecipe($recipe);
        header('Location: /');
    }
}

require __DIR__ . '/src/views/form.php';