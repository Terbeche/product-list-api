<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Attribute',
            'description' => 'A product attribute',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The unique identifier of the attribute'
                    ],
                    'displayValue' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The display value of the attribute',
                        'resolve' => function($attribute) {
                            return $attribute['display_value'];
                        }
                    ],
                    'value' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The actual value of the attribute',
                        'resolve' => function($attribute) {
                            return $attribute['value'] ?? null;
                        }
                    ]
                ];
            }
        ];
        
        parent::__construct($config);
    }
}
