<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CurrencyType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Currency',
            'description' => 'Currency information',
            'fields' => function() {
                return [
                    'label' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The currency label (e.g., USD)'
                    ],
                    'symbol' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'The currency symbol (e.g., $)'
                    ]
                ];
            }
        ];
        
        parent::__construct($config);
    }
}
