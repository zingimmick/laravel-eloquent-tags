<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\Zing\LaravelEloquentTags\Tag[] $tags
 *
 * @method static static|\Illuminate\Database\Eloquent\Builder withAllTags($tags)
 * @method static static|\Illuminate\Database\Eloquent\Builder withAnyTags($tags)
 */
trait HasTags
{
    protected static function getTagClassName()
    {
        return config('laravel-eloquent-tags.models.tag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(static::getTagClassName(), 'taggable', config('laravel-eloquent-tags.table_names.model_has_tags'), config('laravel-eloquent-tags.column_names.taggable_morph_key'), 'tag_id');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|\ArrayAccess|\Zing\LaravelEloquentTags\Tag $tags
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllTags(Builder $query, $tags): Builder
    {
        $tags = static::parseTags($tags);
        $tags->each(
            function (Model $tag) use ($query): void {
                $query->whereHas(
                    'tags',
                    function (Builder $query) use ($tag): void {
                        $query->whereKey($tag->getKey());
                    }
                );
            }
        );

        return $query;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|\ArrayAccess|\Zing\LaravelEloquentTags\Tag $tags
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyTags(Builder $query, $tags): Builder
    {
        $tags = static::parseTags($tags);

        return $query->whereHas(
            'tags',
            function (Builder $query) use ($tags): void {
                $query->whereKey($tags->modelKeys());
            }
        );
    }

    /**
     * @param array|\ArrayAccess|\Zing\LaravelEloquentTags\Tag $tags
     *
     * @return $this
     */
    public function attachTags($tags)
    {
        $this->tags()->attach(static::parseTags($tags));

        return $this;
    }

    /**
     * @param string|\Zing\LaravelEloquentTags\Tag $tag
     *
     * @return $this
     */
    public function attachTag($tag)
    {
        $this->attachTags([$tag]);

        return $this;
    }

    /**
     * @param array|\ArrayAccess $tags
     *
     * @return $this
     */
    public function detachTags($tags)
    {
        $this->tags()->detach(static::parseTags($tags));

        return $this;
    }

    /**
     * @param string|\Zing\LaravelEloquentTags\Tag $tag
     *
     * @return $this
     */
    public function detachTag($tag)
    {
        $this->detachTags([$tag]);

        return $this;
    }

    /**
     * @param array|\ArrayAccess $tags
     *
     * @return $this
     */
    public function syncTags($tags)
    {
        $this->tags()->sync(static::parseTags($tags));

        return $this;
    }

    protected static function parseTags($values)
    {
        return Collection::make($values)->map(
            function ($value) {
                return self::parseTag($value);
            }
        );
    }

    protected static function parseTag($value): Model
    {
        if ($value instanceof Tag) {
            return $value;
        }

        return forward_static_call([static::getTagClassName(), 'query'])->firstOrCreate(
            [
                'name' => $value,
            ]
        );
    }

    /**
     * @param $ids
     *
     * @return \Zing\LaravelEloquentTags\HasTags
     */
    protected function syncTagIds($ids)
    {
        $this->tags()->sync($ids);

        return $this;
    }
}
