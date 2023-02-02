<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rubric()
    {
        return $this->belongsTo(Rubric::class);
    }

    public function getName()
    {
        return $this->rubric()->exists()? $this->rubric->name: $this->category->name;
    }

    public function getUrl()
    {
        return $this->rubric()->exists()? url("category/{$this->category->slug}/{$this->rubric->slug}"): url("category/{$this->category->slug}");
    }
}
