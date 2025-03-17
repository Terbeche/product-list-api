<?php

namespace App\GraphQL\Type;

use App\GraphQL\Type\Registry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeSetType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'AttributeSet',
            'description' => 'A set of attributes for a product',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The unique identifier of the attribute set',
                        'resolve' => function($attributeSet) {
                            return $attributeSet['id'] ?? null;
                        }
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The name of the attribute set',
                        'resolve' => function($attributeSet) {
                            return $attributeSet['name'] ?? null;
                        }
                    ],
                    'type' => [
                        'type' => Type::string(),
                        'description' => 'The type of the attribute (text, swatch, etc)',
                        'resolve' => function($attributeSet) {
                            return $attributeSet['type'] ?? null;
                        }
                    ],
                    'items' => [
                        'type' => Type::listOf(Registry::attributeType()),
                        'description' => 'The attribute items in this set',
                        'resolve' => function($attributeSet, $args, $context) {
                            // Check if we're already dealing with a structured attribute set with items
                            if (isset($attributeSet['items'])) {
                                return $attributeSet['items'];
                            }
                            
                            // Otherwise fetch from database
                            $db = \App\Database\Connection::getInstance();
                            $stmt = $db->prepare('SELECT * FROM attribute_items WHERE attribute_set_id = ?');
                            $stmt->execute([$attributeSet['id']]);
                            
                            return $stmt->fetchAll();
                        }
                    ]
                ];
            }
        ];
        
        parent::__construct($config);
    }
}
