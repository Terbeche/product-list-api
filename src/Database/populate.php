<?php

namespace App\Database;

use PDO;

require_once __DIR__ . '/../../vendor/autoload.php';

class DatabasePopulator
{
    private PDO $connection;
    private array $data;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
        $jsonContent = file_get_contents(__DIR__ . '/../data.json');
        $this->data = json_decode($jsonContent, true)['data'];
    }

    public function populate(): void
    {
        $this->connection->beginTransaction();

        try {
            $this->populateCategories();
            $this->populateProducts();
            $this->connection->commit();
            echo "Database populated successfully!\n";
        } catch (\Exception $e) {
            $this->connection->rollBack();
            echo "Error populating database: " . $e->getMessage() . "\n";
        }
    }

    private function populateCategories(): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO categories (id, name, type_name) 
            VALUES (:id, :name, :type_name)
        ");

        foreach ($this->data['categories'] as $category) {
            $stmt->execute([
                'id' => $category['name'],
                'name' => $category['name'],
                'type_name' => $category['__typename']
            ]);
        }
    }

    private function populateProducts(): void
    {
        $productStmt = $this->connection->prepare("
            INSERT INTO products (id, name, in_stock, description, category_id, brand, type)
            VALUES (:id, :name, :in_stock, :description, :category_id, :brand, :type)
        ");

        $galleryStmt = $this->connection->prepare("
            INSERT INTO product_gallery (product_id, image_url)
            VALUES (:product_id, :image_url)
        ");

        $attrSetStmt = $this->connection->prepare("
            INSERT INTO attribute_sets (id, name, type)
            VALUES (:id, :name, :type)
        ");

        $attrItemStmt = $this->connection->prepare("
            INSERT INTO attribute_items (id, attribute_set_id, display_value, value)
            VALUES (:id, :attribute_set_id, :display_value, :value)
        ");

        $prodAttrStmt = $this->connection->prepare("
            INSERT INTO product_attributes (product_id, attribute_item_id)
            VALUES (:product_id, :attribute_item_id)
        ");

        $priceStmt = $this->connection->prepare("
            INSERT INTO prices (product_id, amount, currency_label, currency_symbol)
            VALUES (:product_id, :amount, :currency_label, :currency_symbol)
        ");

        foreach ($this->data['products'] as $product) {
            // Insert product
            $productStmt->execute([
                'id' => $product['id'],
                'name' => $product['name'],
                'in_stock' => (int)$product['inStock'],
                'description' => $product['description'],
                'category_id' => $product['category'],
                'brand' => $product['brand'],
                'type' => $product['__typename']
            ]);

            // Insert gallery images
            foreach ($product['gallery'] as $imageUrl) {
                $galleryStmt->execute([
                    'product_id' => $product['id'],
                    'image_url' => $imageUrl
                ]);
            }

            // Insert attributes
            foreach ($product['attributes'] as $attribute) {
                $attrSetId = $attribute['id'];
                
                // Check if attribute set exists
                $checkAttrSet = $this->connection->prepare("SELECT id FROM attribute_sets WHERE id = ?");
                $checkAttrSet->execute([$attrSetId]);
                
                if (!$checkAttrSet->fetch()) {
                    // Insert attribute set only if it doesn't exist
                    $attrSetStmt->execute([
                        'id' => $attrSetId,
                        'name' => $attribute['name'],
                        'type' => $attribute['type']
                    ]);
                }

                // Insert attribute items
                foreach ($attribute['items'] as $item) {
                    // Check if attribute item exists
                    $checkAttrItem = $this->connection->prepare("SELECT id FROM attribute_items WHERE id = ?");
                    $checkAttrItem->execute([$item['id']]);
                    
                    if (!$checkAttrItem->fetch()) {
                        $attrItemStmt->execute([
                            'id' => $item['id'],
                            'attribute_set_id' => $attrSetId,
                            'display_value' => $item['displayValue'],
                            'value' => $item['value']
                        ]);
                    }

                    // Check if product-attribute link exists
                    $checkProdAttr = $this->connection->prepare("SELECT product_id FROM product_attributes WHERE product_id = ? AND attribute_item_id = ?");
                    $checkProdAttr->execute([$product['id'], $item['id']]);
                    
                    if (!$checkProdAttr->fetch()) {
                        // Link product to attribute only if it doesn't exist
                        $prodAttrStmt->execute([
                            'product_id' => $product['id'],
                            'attribute_item_id' => $item['id']
                        ]);
                    }
                }
            }

            // Insert prices
            foreach ($product['prices'] as $price) {
                $priceStmt->execute([
                    'product_id' => $product['id'],
                    'amount' => $price['amount'],
                    'currency_label' => $price['currency']['label'],
                    'currency_symbol' => $price['currency']['symbol']
                ]);
            }
        }
    }
}

// Run the population
$populator = new DatabasePopulator();
$populator->populate();
