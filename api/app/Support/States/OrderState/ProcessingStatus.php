<?php

namespace App\Support\States\OrderState;

use App\Support\States\OrderState\OrderState;

class ProcessingStatus extends OrderState
{
    public function label(): string
    {
        return 'В обработке';
    }
}