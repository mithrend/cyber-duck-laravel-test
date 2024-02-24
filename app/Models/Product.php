<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder<Product>
 * @property-read int $id
 * @property string $name
 * @property int $percent_profit_margin
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Support\Carbon $deleted_at
 */
class Product extends Model
{
    use HasFactory;
}
