<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class CustomTag extends Model
{
    public function getTable(): string
    {
        return config('eloquent-tags.table_names.tags', parent::getTable());
    }

    protected $fillable = ['name'];
}
