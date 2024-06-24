<?php

class RecipeModel
{
    // PROPERTIES
    private PDO $connection;


    // CONSTRUCT
    public function __construct()
    {
        $this->connection = new PDO("mysql:host=" . SERVER . ";dbname=" . DATABASE . ";charset=utf8", USER, PASSWORD);
    }


    // METHODS
    // Getters
    public function getAll(): array
    {
        return $this->connection->query('SELECT id, title FROM recipe')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array
    {
        $query = 'SELECT id, title, description FROM recipe WHERE id=:id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }


    // Setters
    public function save(array $recipe): void
    {
        $query = 'INSERT INTO recipe(title, description) VALUES (:title, :description)';
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':title', $recipe['title']);
        $statement->bindValue(':description', $recipe['description']);
        $statement->execute();
    }

    public function updateById(int $id, string $title, string $description): void
    {
        $query = 'UPDATE recipe SET title=:title, description=:description WHERE id=:id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':id', $id,PDO::PARAM_INT);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':description', $description);
        $statement->execute();
    }

    // Others
    public function deleteById(int $id): void
    {
        $query = 'DELETE FROM recipe WHERE id=:id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }
}