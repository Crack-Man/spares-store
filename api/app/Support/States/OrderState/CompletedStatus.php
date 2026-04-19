<?php

namespace App\Support\States\OrderState;

use App\Support\States\OrderState\OrderState;

class CompletedStatus extends OrderState
{
    public function label(): string
    {
        return 'Завершен';
    }
}