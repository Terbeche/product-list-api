<?php

namespace App\Repository;

use App\Database\Connection;
use App\Model\Abstract\ProductModel;
use App\Model\Product;
use PDO;

class ProductRepository
{
    private PDO $connection;
    
    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }
    
    public function findAll(): array
    {   
        $stmt = $this->connection->prepare("SELECT * FROM products");
        $stmt->execute();
        
        $products = [];
        while ($row = $stmt->fetch()) {
            $products[] = $this->createProductFromRow($row);
        }
        return $products;
    }
    
    public function findById(string $id): ?ProductModel
    {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        
        return $this->createProductFromRow($row);
    }
    
    public function findByCategory(string $category): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE category_id = :category");
        $stmt->execute(['category' => $category]);
        
        $products = [];
        while ($row = $stmt->fetch()) {
            $products[] = $this->createProductFromRow($row);
        }
        
        return $products;
    }
    
    private function createProductFromRow(array $row): ProductModel
    {
        // Fetch prices
        $stmtPrices = $this->connection->prepare("
            SELECT amount, currency_label as label, currency_symbol as symbol 
            FROM prices 
            WHERE product_id = :product_id
        ");
        $stmtPrices->execute(['product_id' => $row['id']]);
        $prices = [];
        while ($priceRow = $stmtPrices->fetch()) {
            $prices[] = [
                'amount' => (float)$priceRow['amount'],
                'currency' => [
                    'label' => $priceRow['label'],
                    'symbol' => $priceRow['symbol']
                ]
            ];
        }

        // Fetch gallery
        $stmtGallery = $this->connection->prepare("
            SELECT image_url FROM product_gallery WHERE product_id = :product_id
        ");
        $stmtGallery->execute(['product_id' => $row['id']]);
        $gallery = [];
        while ($galleryRow = $stmtGallery->fetch()) {
            $gallery[] = $galleryRow['image_url'];
        }

        // Fetch attributes
        $stmtAttributes = $this->connection->prepare("
            SELECT attr_set.id as set_id, attr_set.name as set_name, attr_set.type as set_type, 
                   ai.id as item_id, ai.display_value, ai.value 
            FROM product_attributes pa 
            JOIN attribute_items ai ON pa.attribute_item_id = ai.id 
            JOIN attribute_sets attr_set ON ai.attribute_set_id = attr_set.id 
            WHERE pa.product_id = :product_id
        ");
        $stmtAttributes->execute(['product_id' => $row['id']]);
        
        // Group attributes by set
        $attributeSets = [];
        
        while ($attributeRow = $stmtAttributes->fetch()) {
            $setId = $attributeRow['set_id'];
            if (!isset($attributeSets[$setId])) {
                $attributeSets[$setId] = [
                    'id' => $setId,
                    'name' => $attributeRow['set_name'],
                    'type' => $attributeRow['set_type'],
                    'items' => []
                ];
            }
            
            // Add item directly to the items array
            $attributeSets[$setId]['items'][] = [
                'id' => $attributeRow['item_id'],
                'display_value' => $attributeRow['display_value'],
                'value' => $attributeRow['value']
            ];
        }
        
        $attributes = array_values($attributeSets);

        // Create product
        $product = new Product();
        $product->fromArray([
            'id' => $row['id'],
            'name' => $row['name'],
            'inStock' => (bool)$row['in_stock'],
            'gallery' => $gallery,
            'description' => $row['description'],
            'category' => $row['category_id'],
            'attributes' => $attributes,
            'prices' => $prices,
            'brand' => $row['brand'],
            'type_name' => $row['type']
        ]);
        return $product;
    }
}
