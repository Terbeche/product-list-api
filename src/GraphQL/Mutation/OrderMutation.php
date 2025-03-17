<?php

namespace App\GraphQL\Mutation;

use App\Database\Connection;
use App\GraphQL\Type\Registry;
use GraphQL\Type\Definition\Type;

class OrderMutation
{
    public static function getFields()
    {
        return [
            'createOrder' => [
                'type' => Registry::orderType(),
                'description' => 'Create a new customer order',
                'args' => [
                    'items' => [
                        'type' => Type::nonNull(Type::listOf(Type::nonNull(Type::string()))),
                        'description' => 'Order items as JSON strings containing product_id, quantity, and selected_attributes'
                    ]
                ],
                'resolve' => function($rootValue, $args) {
                    $db = Connection::getInstance();
                    $db->beginTransaction();
                    
                    try {
                        // Calculate order total
                        $total = 0;
                        $items = [];
                        
                        foreach ($args['items'] as $itemJson) {
                            $item = json_decode($itemJson, true);
                            
                            if (!isset($item['product_id']) || !isset($item['quantity'])) {
                                throw new \Exception('Invalid item format: product_id and quantity are required');
                            }

                            // Get product price
                            $stmt = $db->prepare('SELECT * FROM prices WHERE product_id = ? LIMIT 1');
                            $stmt->execute([$item['product_id']]);
                            $price = $stmt->fetch();
                            
                            if (!$price) {
                                throw new \Exception("Product price not found for product: " . $item['product_id']);
                            }
                            
                            $itemTotal = $price['amount'] * $item['quantity'];
                            $total += $itemTotal;
                            
                            $items[] = [
                                'product_id' => $item['product_id'],
                                'quantity' => $item['quantity'],
                                'selected_attributes' => isset($item['selected_attributes']) ? $item['selected_attributes'] : null
                            ];
                        }
                        
                        // Generate a unique order ID
                        $orderId = uniqid('order_');
                        
                        // Insert order
                        $stmt = $db->prepare('
                            INSERT INTO orders (id, total_amount)
                            VALUES (:id, :total_amount)
                        ');
                        
                        $stmt->execute([
                            'id' => $orderId,
                            'total_amount' => $total
                        ]);
                        
                        // Insert order items
                        $itemStmt = $db->prepare('
                            INSERT INTO order_items (order_id, product_id, quantity, selected_attributes)
                            VALUES (:order_id, :product_id, :quantity, :selected_attributes)
                        ');
                        
                        foreach ($items as $item) {
                            $itemStmt->execute([
                                'order_id' => $orderId,
                                'product_id' => $item['product_id'],
                                'quantity' => $item['quantity'],
                                'selected_attributes' => json_encode($item['selected_attributes'])
                            ]);
                        }
                        
                        $db->commit();
                        
                        // Return the created order
                        $orderStmt = $db->prepare('SELECT * FROM orders WHERE id = ?');
                        $orderStmt->execute([$orderId]);
                        
                        return $orderStmt->fetch();
                    } catch (\Exception $e) {
                        $db->rollBack();
                        throw $e;
                    }
                }
            ]
        ];
    }
}
