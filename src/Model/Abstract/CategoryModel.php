<?php

namespace App\Model\Abstract;

abstract class CategoryModel
{
    protected string $name;
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function toArray(): array
    {
        return [
            'name' => $this->name
        ];
    }
    
    abstract public function fromArray(array $data): self;
}