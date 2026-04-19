<?php

namespace App\Support\States\OrderState;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class OrderState extends State
{
    abstract public function label(): string;

    protected static array $stateMap = [
        'Новый' => NewStatus::class,
        'Подтвержден' => ConfirmedStatus::class,
        'В обработке' => ProcessingStatus::class,
        'Отправлен' => ShippedStatus::class,
        'Завершен' => CompletedStatus::class,
        'Отменен' => CancelledStatus::class,
    ];

    public static function resolveByName(string $name): string
    {
        return static::$stateMap[$name]
            ?? throw new \InvalidArgumentException("Unknown order status: {$name}");
    }

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(NewStatus::class)
            ->allowTransition(NewStatus::class, CancelledStatus::class)
            ->allowTransition(NewStatus::class, ConfirmedStatus::class)
            ->allowTransition(ConfirmedStatus::class, CancelledStatus::class)
            ->allowTransition(ConfirmedStatus::class, ProcessingStatus::class)
            ->allowTransition(ProcessingStatus::class, ShippedStatus::class)
            ->allowTransition(ShippedStatus::class, CompletedStatus::class);
    }
}
