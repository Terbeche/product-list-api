<?php

namespace App\Model;

use App\Model\Abstract\CategoryModel;

class Category extends CategoryModel
{
    public function fromArray(array $data): self
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->typeName = $data['type_name'] ?? null;
        
        return $this;
    }
}