<?php

namespace App\Support\States\OrderState;

use App\Support\States\OrderState\OrderState;

class NewStatus extends OrderState
{
    public function label(): string
    {
        return 'Новый';
    }
}