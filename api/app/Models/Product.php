<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasSlug, Searchable;
    
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
    
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug')
            ->usingLanguage('ru');
    }

    public function scopeActive($query): Builder
    {
        return $query->where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->active();
            });
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
