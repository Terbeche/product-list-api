<?php

namespace App\Model\Product;

use App\Model\Abstract\ProductModel;

class Product extends ProductModel
{    
    public function fromArray(array $data): self
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->inStock = $data['inStock'] ?? true;
        $this->gallery = $data['gallery'] ?? [];
        $this->description = $data['description'] ?? '';
        $this->category = $data['category'] ?? '';
        $this->attributes = $data['attributes'] ?? [];
        $this->prices = $data['prices'] ?? [];
        $this->brand = $data['brand'] ?? '';
        $this->typeName = $data['type_name'] ?? null;

        return $this;
    }
}