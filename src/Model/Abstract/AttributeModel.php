<?php

namespace App\Model\Abstract;

abstract class AttributeModel
{
    protected string $id;
    protected string $name;
    protected array $items;
    
    abstract public function getType(): string;
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getItems(): array
    {
        return $this->items;
    }
    
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'items' => $this->items,
            'type' => $this->getType()
        ];
    }
    
    abstract public function fromArray(array $data): self;
}