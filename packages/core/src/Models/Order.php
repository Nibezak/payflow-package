<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Payflow\Base\BaseModel;
use Payflow\Base\Casts\DiscountBreakdown;
use Payflow\Base\Casts\Price;
use Payflow\Base\Casts\ShippingBreakdown;
use Payflow\Base\Casts\TaxBreakdown;
use Payflow\Base\Traits\HasMacros;
use Payflow\Base\Traits\HasTags;
use Payflow\Base\Traits\LogsActivity;
use Payflow\Base\Traits\Searchable;
use Payflow\Database\Factories\OrderFactory;

/**
 * @property int $id
 * @property ?int $customer_id
 * @property ?int $user_id
 * @property int $channel_id
 * @property bool $new_customer
 * @property string $status
 * @property ?string $reference
 * @property ?string $customer_reference
 * @property int $sub_total
 * @property int $discount_total
 * @property array $discount_breakdown
 * @property array $shipping_breakdown
 * @property array $tax_breakdown
 * @property int $tax_total
 * @property int $total
 * @property ?string $notes
 * @property string $currency_code
 * @property ?string $compare_currency_code
 * @property float $exchange_rate
 * @property ?\Illuminate\Support\Carbon $placed_at
 * @property ?array $meta
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Order extends BaseModel implements Contracts\Order
{
    use HasFactory,
        HasMacros,
        HasTags,
        LogsActivity,
        Searchable;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'tax_breakdown' => TaxBreakdown::class,
        'meta' => AsArrayObject::class,
        'placed_at' => 'datetime',
        'sub_total' => Price::class,
        'discount_total' => Price::class,
        'discount_breakdown' => DiscountBreakdown::class,
        'shipping_breakdown' => ShippingBreakdown::class,
        'tax_total' => Price::class,
        'total' => Price::class,
        'shipping_total' => Price::class,
        'new_customer' => 'boolean',
    ];

    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

    protected static function newFactory()
    {
        return OrderFactory::new();
    }

    public function getStatusLabelAttribute(): string
    {
        $statuses = config('payflow.orders.statuses');

        return $statuses[$this->status]['label'] ?? $this->status;
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::modelClass());
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::modelClass());
    }

    public function lines(): HasMany
    {
        return $this->hasMany(OrderLine::modelClass());
    }

    public function physicalLines(): HasMany
    {
        return $this->lines()->whereType('physical');
    }

    public function digitalLines(): HasMany
    {
        return $this->lines()->whereType('digital');
    }

    public function shippingLines(): HasMany
    {
        return $this->lines()->whereType('shipping');
    }

    public function productLines(): HasMany
    {
        return $this->lines()->where('type', '!=', 'shipping');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::modelClass(), 'currency_code', 'code');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(OrderAddress::modelClass(), 'order_id');
    }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(OrderAddress::modelClass(), 'order_id')->whereType('shipping');
    }

    public function billingAddress(): HasOne
    {
        return $this->hasOne(OrderAddress::modelClass(), 'order_id')->whereType('billing');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::modelClass())->orderBy('created_at', 'desc');
    }

    public function captures(): HasMany
    {
        return $this->transactions()->whereType('capture');
    }

    public function intents(): HasMany
    {
        return $this->transactions()->whereType('intent');
    }

    public function refunds(): HasMany
    {
        return $this->transactions()->whereType('refund');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            config('auth.providers.users.model')
        );
    }

    public function isDraft(): bool
    {
        return ! $this->isPlaced();
    }

    public function isPlaced(): bool
    {
        return ! blank($this->placed_at);
    }

    public static function getDefaultLogExcept(): array
    {
        return [
            'status',
        ];
    }
}
