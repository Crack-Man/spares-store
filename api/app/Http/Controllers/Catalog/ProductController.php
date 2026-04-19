<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\ProductListResource;
use App\Services\Catalog\FilterProductService;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\Catalog\GetProductsRequest;
use App\Models\Category;

class ProductController extends Controller
{
    protected FilterProductService $filterProductService;

    public function __construct(FilterProductService $filterProductService)
    {
        $this->filterProductService = $filterProductService;
    }

    public function getProducts(GetProductsRequest $request): JsonResource
    {
        $products = $this->filterProductService->filter($request);
        return ProductListResource::collection($products);
    }
}
