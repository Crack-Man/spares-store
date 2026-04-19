<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\CategoryResource;
use App\Http\Resources\Catalog\ProductListResource;
use App\Models\Category;
use App\Services\Catalog\FilterProductService;
use App\Http\Requests\Catalog\GetProductsRequest;

class ProductController extends Controller
{
    protected FilterProductService $filterProductService;

    public function __construct(FilterProductService $filterProductService)
    {
        $this->filterProductService = $filterProductService;
    }

    public function getProducts(GetProductsRequest $request)
    {
        $category = Category::where('slug', $request->category_slug)->firstOrFail();

        $products = $this->filterProductService->filter($request, $category)
            ->paginate(10);

        return ProductListResource::collection($products)->additional([
            'category' => new CategoryResource($category),
        ]);
    }
}
