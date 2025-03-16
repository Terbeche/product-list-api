<?php

namespace App\GraphQL\Resolver;

use App\Database\Connection;
use App\GraphQL\Type\Registry;
use App\Model\Category;
use GraphQL\Type\Definition\Type;
use App\Repository\CategoryRepository;

class CategoryResolver
{
    public static function getFields()
    {

        return [
            'categories' => [
                'type' => Type::listOf(Registry::categoryType()),
                'description' => 'Get all product categories',
                'resolve' => function($rootValue, $args, $context) {
                    $categoryRepository = new \App\Repository\CategoryRepository();
                    return $categoryRepository->findAll();
                }
            ],
            'category' => [
                'type' => Registry::categoryType(),
                'description' => 'Get a category by ID',
                'args' => [
                    'id' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The category ID'
                    ]
                ],

                'resolve' => function($rootValue, $args, $context) {
                    $categoryRepository = new \App\Repository\CategoryRepository();
                    return $categoryRepository->findById($args['id']);
                }
            ]
        ];
    }
}
