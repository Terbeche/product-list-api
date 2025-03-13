<?php

namespace App\Repository;

use App\Database\Connection;
use App\Model\Category;
use PDO;

class CategoryRepository
{
    private PDO $connection;
    
    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }
    
    public function findAll(): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM categories");
        $stmt->execute();
        
        $categories = [];
        while ($row = $stmt->fetch()) {
            $categories[] = $this->createCategoryFromRow($row);
        }
        
        return $categories;
    }
    
    public function findById(string $id): ?Category
    {
        $stmt = $this->connection->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        
        return $this->createCategoryFromRow($row);
    }
    
    private function createCategoryFromRow(array $row): Category
    {
        $category = new Category();
        $category->fromArray([
            'id' => $row['id'],
            'name' => $row['name'],
            'type_name' => $row['type_name'] ?? null
        ]);
        
        return $category;
    }
}