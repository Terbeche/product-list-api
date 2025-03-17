<?php

namespace App\Model\Attribute;

use App\Model\Abstract\AttributeModel;

class CapacityAttribute extends AttributeModel
{
    public function getType(): string
    {
        return 'text';
    }
    
    public function fromArray(array $data): self
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->items = $data['items'] ?? [];
        
        return $this;
    }
}