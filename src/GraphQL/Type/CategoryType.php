<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CategoryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Category',
            'description' => 'A product category',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The unique identifier of the category',
                        'resolve' => function($category) {
                            return $category->getId();
                        }
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The name of the category',
                        'resolve' => function($category) {
                            return $category->getName();
                        }
                    ],
                    'type_name' => [
                        'type' => Type::string(),
                        'description' => 'The type name of the category',
                        'resolve' => function($category) {
                            return $category->getTypeName();
                        }
                    ]
                ];
            }
        ];
        
        parent::__construct($config);
    }
}
