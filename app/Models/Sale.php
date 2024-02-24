<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder<Sale>
 * @property-read int $id
 * @property int $quantity
 * @property int $unit_cost
 * @property int $selling_price
 * @property int $product_id
 * @property-read Product $product
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Support\Carbon $deleted_at
 */
class Sale extends Model
{
    use HasFactory;

    /** @var string[] */
    protected $fillable = [
        'quantity',
        'unit_cost',
        'selling_price',
        'product_id',
    ];

    /**
     * @return BelongsTo<Product, Sale>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
