<?php

namespace App\Model\Abstract;

abstract class ProductModel
{
    protected string $id;
    protected string $name;
    protected bool $inStock;
    protected array $gallery;
    protected string $description;
    protected string $category;
    protected array $attributes;
    protected array $prices;
    protected string $brand;

    abstract public function getType(): string;
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function isInStock(): bool
    {
        return $this->inStock;
    }
    
    public function getGallery(): array
    {
        return $this->gallery;
    }
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function getCategory(): string
    {
        return $this->category;
    }
    
    public function getAttributes(): array
    {
        return $this->attributes;
    }
    
    public function getPrices(): array
    {
        return $this->prices;
    }
    
    public function getBrand(): string
    {
        return $this->brand;
    }
    
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'inStock' => $this->inStock,
            'gallery' => $this->gallery,
            'description' => $this->description,
            'category' => $this->category,
            'attributes' => $this->attributes,
            'prices' => $this->prices,
            'brand' => $this->brand,
            'type' => $this->getType()
        ];
    }
    
    abstract public function fromArray(array $data): self;
}
