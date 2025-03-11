<?php

namespace App\Model\Abstract;

abstract class CategoryModel
{
    protected string $id;
    protected string $name;
    protected ?string $typeName = null;
 
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getName(): string
    {
        return $this->name;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }
    
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type_name' => $this->typeName
        ];
    }

    abstract public function fromArray(array $data): self;
}