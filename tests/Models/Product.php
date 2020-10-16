<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Zing\LaravelEloquentTags\HasTags;

/**
 * @method static \Zing\LaravelEloquentTags\Tests\Models\Product|\Illuminate\Database\Eloquent\Builder query()
 */
class Product extends Model
{
    use HasTags;
}
