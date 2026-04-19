<?php

namespace App\Support\States\OrderState;

use App\Support\States\OrderState\OrderState;

class ConfirmedStatus extends OrderState
{
    public function label(): string
    {
        return 'Подтвержден';
    }
}