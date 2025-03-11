<?php

namespace App\GraphQL\Type;

use App\GraphQL\Type\Registry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderItemType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderItem',
            'description' => 'An item in a customer order',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Type::nonNull(Type::id()),
                        'description' => 'The unique identifier of the order item'
                    ],
                    'product' => [
                        'type' => Registry::productType(),
                        'description' => 'The product ordered',
                        'resolve' => function($orderItem) {
                            $db = \App\Database\Connection::getInstance();
                            $stmt = $db->prepare('SELECT * FROM products WHERE id = ?');
                            $stmt->execute([$orderItem['product_id']]);
                            
                            return $stmt->fetch();
                        }
                    ],
                    'quantity' => [
                        'type' => Type::nonNull(Type::int()),
                        'description' => 'The quantity ordered'
                    ],
                    'selectedAttributes' => [
                        'type' => Type::string(),
                        'description' => 'Selected product attributes as JSON string'
                    ]
                ];
            }
        ];
        
        parent::__construct($config);
    }
}
