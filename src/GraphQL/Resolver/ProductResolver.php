<?php

namespace App\GraphQL\Resolver;

use App\Database\Connection;
use App\Model\Product\Product;
use App\GraphQL\Type\Registry;
use GraphQL\Type\Definition\Type;
use App\Repository\ProductRepository;

use PDO;

class ProductResolver
{
    public static function getFields()
    {
        return [   
            'products' => [
                'type' => Type::listOf(Registry::productType()),
                'description' => 'Get all products',
                'args' => [
                    'category' => [
                        'type' => Type::string(),
                        'description' => 'Filter products by category ID'
                    ]
                ],
                'resolve' => function($rootValue, $args, $context) {
                    $productRepository = new \App\Repository\ProductRepository();
                    
                    if (isset($args['category']) && $args['category'] !== "all") {
                        return $productRepository->findByCategory($args['category']);
                    } else {
                        return $productRepository->findAll();
                    }
                }
            ],

            'product' => [
                'type' => Registry::productType(),
                'description' => 'Get a product by ID',
                'args' => [
                    'id' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The product ID'
                    ]
                ],
                'resolve' => function($rootValue, $args, $context) {
                    $productRepository = new \App\Repository\ProductRepository();
                    return $productRepository->findById($args['id']);
                }
            ]
        ];
    }
}