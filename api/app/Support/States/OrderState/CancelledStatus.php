<?php

namespace App\Support\States\OrderState;

use App\Support\States\OrderState\OrderState;

class CancelledStatus extends OrderState
{
    public function label(): string
    {
        return 'Отменен';
    }
}