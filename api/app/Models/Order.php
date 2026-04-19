<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\ModelStates\HasStates;
use App\Support\States\OrderState\OrderState;

class Order extends Model
{
    use HasStates;
    protected $fillable = [
        'id',
        'customer_id',
        'status',
        'total_amount',
        'confirmed_at',
        'shipped_at',
    ];

    protected $casts = [
        'status' => OrderState::class,
    ];
    
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
