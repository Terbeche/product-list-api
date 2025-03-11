<?php

namespace App\GraphQL\Type;

use App\GraphQL\Type\CategoryType;
use App\GraphQL\Type\ProductType;
use App\GraphQL\Type\AttributeSetType;
use App\GraphQL\Type\AttributeType;
use App\GraphQL\Type\PriceType;
use App\GraphQL\Type\CurrencyType;
use App\GraphQL\Type\OrderType;
use App\GraphQL\Type\OrderItemType;

class Registry
{
    private static $categoryType;
    private static $productType;
    private static $attributeSetType;
    private static $attributeType;
    private static $priceType;
    private static $currencyType;
    private static $orderType;
    private static $orderItemType;
    
    public static function categoryType()
    {
        return self::$categoryType ?: (self::$categoryType = new CategoryType());
    }
    
    public static function productType()
    {
        return self::$productType ?: (self::$productType = new ProductType());
    }
    
    public static function attributeSetType()
    {
        return self::$attributeSetType ?: (self::$attributeSetType = new AttributeSetType());
    }
    
    public static function attributeType()
    {
        return self::$attributeType ?: (self::$attributeType = new AttributeType());
    }
    
    public static function priceType()
    {
        return self::$priceType ?: (self::$priceType = new PriceType());
    }
    
    public static function currencyType()
    {
        return self::$currencyType ?: (self::$currencyType = new CurrencyType());
    }
    
    public static function orderType()
    {
        return self::$orderType ?: (self::$orderType = new OrderType());
    }
    
    public static function orderItemType()
    {
        return self::$orderItemType ?: (self::$orderItemType = new OrderItemType());
    }
}
