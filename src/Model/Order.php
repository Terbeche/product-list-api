<?php

namespace App\Model;

class Order
{
    private string $id;
    private array $items;
    private string $createdAt;
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }
    
    public function getItems(): array
    {
        return $this->items;
    }
    
    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }
    
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'items' => $this->items,
            'createdAt' => $this->createdAt
        ];
    }
    
    public function fromArray(array $data): self
    {
        $this->id = $data['id'] ?? uniqid();
        $this->items = $data['items'] ?? [];
        $this->createdAt = $data['createdAt'] ?? date('Y-m-d H:i:s');
        
        return $this;
    }
}