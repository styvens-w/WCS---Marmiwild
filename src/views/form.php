<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>List of Recipes</title>
</head>
<body>
<a href="/"><-- return</a>
<h1>Add a Recipe</h1>
<?php foreach ($errors as $error) : ?>
<p><?=$error?></p>
<?php endforeach; ?>
<form action="" method="post">
    <label for="title" style="display: block">Title</label>
    <input type="text" id="title" name="title" style="display: block; margin-bottom: 20px">

    <label for="description" style="display: block">Description</label>
    <textarea name="description" id="description" cols="30" rows="10" style="display: block; margin-bottom: 20px"></textarea>

    <input type="submit">
</form>
</body>
</html>
