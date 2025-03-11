<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PriceType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Price',
            'description' => 'A product price',
            'fields' => function() {
                return [
                    'amount' => [
                        'type' => Type::nonNull(Type::float()),
                        'description' => 'The price amount'
                    ],
                    'currency' => [
                        'type' => Registry::currencyType(),
                        'description' => 'The currency information',
                        'resolve' => function($price, $args, $context) {
                            return $price['currency'] ?? null;
                        }
                    ]
                ];
            }
        ];
        
        parent::__construct($config);
    }
}
