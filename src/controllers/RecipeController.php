<?php

require __DIR__ . '/../models/RecipeModel.php';

class RecipeController
{
    // PROPERTIES
    private RecipeModel $model;


    // CONSTRUCT
    public function __construct()
    {
        $this->model = new RecipeModel();
    }


    // METHODS
    // Others
    public function browse(): void
    {
        $recipes = $this->model->getAll();

        require __DIR__ . '/../views/indexRecipe.php';
    }

    public function show(int $id): void
    {
        $id = filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

        if (false === $id || null === $id) {
            die("Wrong input parameter");
        }

        $recipe = $this->model->getById($id);

        if (!isset($recipe['title'], $recipe['description'])) {
            header("HTTP/1.1 404 Not Found");
            die("Recipe not found");
        }

        require __DIR__ . '/../views/showRecipe.php';
    }

    public function add(): void
    {
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $recipe = array_map('trim', $_POST);

            $errors = $this->validate($recipe);

            // Save the recipe
            if (empty($errors)) {
                $this->model->save($recipe);
                header('Location: /');
            }
        }

        $action = 'Add';

        // Generate the web page
        require __DIR__ . '/../views/form.php';
    }

    public function update(int $id): void
    {
        $errors = [];

        $id = filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

        if (false === $id || null === $id) {
            die("Wrong input parameter");
        }

        $recipe = $this->model->getById($id);

        if (!isset($recipe['title'], $recipe['description'])) {
            header("HTTP/1.1 404 Not Found");
            die("Recipe not found");
        }

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {

            $recipe = array_map('trim', $_POST);

            $errors = $this->validate($recipe);

            if (empty($errors)) {
                $this->model->updateById($recipe['id'], $recipe['title'], $recipe['description']);
                header('Location: /');
            }
        }

        $action = 'Update';

        // Generate the web page
        require __DIR__ . '/../views/form.php';
    }

    public function delete(int $id): void
    {
        $id = filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

        if (false === $id || null === $id) {
            die("Wrong input parameter");
        }

        $this->model->deleteById($id);

        // Generate the web page
        header('Location: /');
    }


    private function validate(array $recipe): array
    {
        if (empty($recipe['title'])) {
            $errors[] = 'The title is required';
        }
        if (empty($recipe['description'])) {
            $errors[] = 'The description is required';
        }
        if (!empty($recipe['title']) && strlen($recipe['title']) > 255) {
            $errors[] = 'The title should be less than 255 characters';
        }

        return $errors ?? [];
    }
}