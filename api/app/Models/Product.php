<?php

namespace App\Models;

use App\Models\Traits\HasActiveScope;
use App\Models\Traits\HasSlugGeneration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, HasSlugGeneration, HasActiveScope, Searchable;
    
    protected $fillable = [
        'id',
        'name',
        'slug',
        'sku',
        'price',
        'stock_quantity',
        'category_id',
        'is_active',
    ];

    protected function activeRelations(): array
    {
        return ['category'];
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'sku' => $this->sku,
        ];
    }
}
