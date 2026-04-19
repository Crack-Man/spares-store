<?php

namespace App\Services\Catalog;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

class FilterProductService
{
    public function filter($request, ?Category $category = null): Collection
    {
        if ($request->has('search') && $request->search) {
            $searchResults = Product::search($request->search)
                ->get()
                ->pluck('id');
        }
        
        if ($request->has('category_slug') && $request->category_slug) {
            $category = Category::where('slug', $request->category_slug)->firstOrFail();
        }

        return Product::query()
            ->active()
            ->when($category?->id, fn ($query) => $query->where('category_id', $category->id))
            ->when($searchResults ?? null, fn ($query) => $query->whereIn('id', $searchResults))
            ->get();
    }
}