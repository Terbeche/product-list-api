<?php

namespace App\GraphQL\Type;

use App\GraphQL\Type\Registry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Repository\CategoryRepository;

class ProductType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Product',
            'description' => 'A product in the catalog',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The unique identifier of the product',
                        'resolve' => function($product) {
                            return $product->getId();
                        }
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The name of the product',
                        'resolve' => function($product) {
                            return $product->getName();
                        }
                    ],
                    'inStock' => [
                        'type' => Type::nonNull(Type::boolean()),
                        'description' => 'Whether the product is in stock',
                        'resolve' => function($product) {
                            return $product->isInStock();
                        }
                    ],
                    'gallery' => [
                        'type' => Type::listOf(Type::string()),
                        'description' => 'Product image gallery URLs',
                        'resolve' => function($product) {
                            return $product->getGallery();
                        }
                    ],
                    'description' => [
                        'type' => Type::string(),
                        'description' => 'Product description',
                        'resolve' => function($product) {
                            return $product->getDescription();
                        }
                    ],
                    'category' => [
                        'type' => Registry::categoryType(),
                        'description' => 'The category the product belongs to',
                        'resolve' => function($product, $args, $context) {
                            if (!$product->getCategory()) {
                                return null;
                            }
                            $categoryRepository = new \App\Repository\CategoryRepository();
                            return $categoryRepository->findById($product->getCategory());
                        }
                    ],
                    'attributes' => [
                        'type' => Type::listOf(Registry::attributeSetType()),
                        'description' => 'Product attributes',
                        'resolve' => function($product, $args, $context) {
                            return $product->getAttributes();
                        }
                    ],
                    'prices' => [
                        'type' => Type::listOf(Registry::priceType()),
                        'description' => 'Product prices',
                        'resolve' => function($product, $args, $context) {
                            return $product->getPrices();
                        }
                    ],
                    'brand' => [
                        'type' => Type::string(),
                        'description' => 'Product brand',
                        'resolve' => function($product) {
                            return $product->getBrand();
                        }
                    ],
                    'type' => [
                        'type' => Type::string(),
                        'description' => 'Product type',
                        'resolve' => function($product) {
                            return $product->getTypeName() ?? get_class($product);
                        }
                    ]
                ];
            }
        ];
        
        parent::__construct($config);
    }
}