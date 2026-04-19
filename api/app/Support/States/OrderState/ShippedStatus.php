<?php

namespace App\Support\States\OrderState;

use App\Support\States\OrderState\OrderState;

class ShippedStatus extends OrderState
{
    public function label(): string
    {
        return 'Отправлен';
    }
}