<?php

namespace App\Models;

use App\Models\Traits\HasActiveScope;
use App\Models\Traits\HasSlugGeneration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, HasSlugGeneration, HasActiveScope;
    
    protected $fillable = [
        'id',
        'name',
        'slug',
        'is_active',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
