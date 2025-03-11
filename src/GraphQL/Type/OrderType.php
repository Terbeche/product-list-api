<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Order',
            'description' => 'A customer order',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Type::nonNull(Type::id()),
                        'description' => 'The unique identifier of the order'
                    ],
                    'total' => [
                        'type' => Type::nonNull(Type::float()),
                        'description' => 'Order total'
                    ],
                    'items' => [
                        'type' => Type::listOf(Registry::orderItemType()),
                        'description' => 'Order items',
                        'resolve' => function($order) {
                            $db = \App\Database\Connection::getInstance();
                            $stmt = $db->prepare('SELECT * FROM order_items WHERE order_id = ?');
                            $stmt->execute([$order['id']]);
                            
                            return $stmt->fetchAll();
                        }
                    ]
                ];
            }
        ];
        
        parent::__construct($config);
    }
}
