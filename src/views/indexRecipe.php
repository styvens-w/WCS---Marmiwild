<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>List of Recipes</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>

<header>
    <img src="image.jpg" alt="logo" class="logo">

    <h1 class="pageTitle"> Recipes </h1>

    <nav class="navBar">
        <a class="addNavBarre" href="/add">Add</a>
        <a class="connexionNavBarre" href="/">Connexion</a>
    </nav>
</header>



<main class="container">


    <h1>List of Recipes</h1>
    <ul>
        <?php foreach ($recipes as $recipe) : ?>
            <li>
                <a href="show?id=<?= $recipe['id'] ?>">
                    <?= $recipe['title'] ?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</main>

<footer>
    <div class="social-links">
        <p class="copyRight"> &copy; 2024 </p>

        <a class="link-social" href="#">facebook</a>
        <a  class="link-social" href="#">Instagram</a>
        <a  class="link-social" href="#">Twitter</a>
        <a  class="link-social" href="#">Linkedin</a>
    </div>
</footer>
</body>

</html>