<?php

namespace App\Services\Catalog;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class FilterProductService
{
    public function filter($request, ?Category $category = null): Builder
    {
        if ($request->has('search') && $request->search) {
            $searchResults = Product::search($request->search)
                ->get()
                ->pluck('id');
        }

        return Product::query()
            ->active()
            ->when($category?->id, fn ($query) => $query->where('category_id', $category->id))
            ->when($searchResults ?? null, fn ($query) => $query->whereIn('id', $searchResults));
    }
}