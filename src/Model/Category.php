<?php

namespace App\Model;

use App\Model\Abstract\CategoryModel;

class Category extends CategoryModel
{
    public function fromArray(array $data): self
    {
        $this->name = $data['name'];
        
        return $this;
    }
}