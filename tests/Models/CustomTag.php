<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class CustomTag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'name',
    ];
}
