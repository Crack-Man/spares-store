<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    public function __construct(
        public readonly int $productId,
        public readonly string $productName,
        public readonly int $requested,
        public readonly int $available,
    ) {
        parent::__construct(
            "Недостаточно товара «{$productName}» на складе: запрошено {$requested}, доступно {$available}"
        );
    }

    public function render()
    {
        return response()->json([
            'status' => 'error',
            'message' => $this->getMessage(),
            'product_id' => $this->productId,
            'requested' => $this->requested,
            'available' => $this->available,
        ], 422);
    }
}
