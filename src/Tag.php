<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Zing\LaravelEloquentTags\Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Zing\LaravelEloquentTags\Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Zing\LaravelEloquentTags\Tag query()
 */
class Tag extends Model
{
    public function getTable(): string
    {
        return config('eloquent-tags.table_names.tags', parent::getTable());
    }

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['name'];
}
