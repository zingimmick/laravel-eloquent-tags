<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\Zing\LaravelEloquentTags\Tag[] $tags
 * @property-read int|null $tags_count
 *
 * @method static static|\Illuminate\Database\Eloquent\Builder withAllTags($tags)
 * @method static static|\Illuminate\Database\Eloquent\Builder withAnyTags($tags)
 */
trait HasTags
{
    /**
     * @return class-string<\Zing\LaravelEloquentTags\Tag>
     */
    protected static function getTagClassName(): string
    {
        return config('eloquent-tags.models.tag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany<\Zing\LaravelEloquentTags\Tag>
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(
            static::getTagClassName(),
            'taggable',
            config('eloquent-tags.table_names.model_has_tags'),
            config('eloquent-tags.column_names.taggable_morph_key'),
            'tag_id'
        );
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentTags\Tag|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentTags\Tag|string>|\Zing\LaravelEloquentTags\Tag $tags
     */
    public function scopeWithAllTags(
        Builder $query,
        iterable|\Illuminate\Contracts\Support\Arrayable|Tag $tags
    ): Builder {
        $tags = static::parseTags($tags);
        $tags->each(
            static function (Model $tag) use ($query): void {
                $query->whereHas(
                    'tags',
                    static function (Builder $query) use ($tag): void {
                        $query->whereKey($tag->getKey());
                    }
                );
            }
        );

        return $query;
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentTags\Tag|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentTags\Tag|string>|\Zing\LaravelEloquentTags\Tag $tags
     */
    public function scopeWithAnyTags(
        Builder $query,
        iterable|\Illuminate\Contracts\Support\Arrayable|Tag $tags
    ): Builder {
        $tags = static::parseTags($tags);

        return $query->whereHas(
            'tags',
            static function (Builder $query) use ($tags): void {
                $query->whereKey($tags->modelKeys());
            }
        );
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentTags\Tag|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentTags\Tag|string>|\Zing\LaravelEloquentTags\Tag $tags
     *
     * @return $this
     */
    public function attachTags(iterable|\Illuminate\Contracts\Support\Arrayable|Tag $tags)
    {
        $this->tags()
            ->attach(static::parseTags($tags));

        return $this;
    }

    /**
     * @return $this
     */
    public function attachTag(string|Tag $tag)
    {
        $this->attachTags([$tag]);

        return $this;
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentTags\Tag|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentTags\Tag|string> $tags
     *
     * @return $this
     */
    public function detachTags(iterable|\Illuminate\Contracts\Support\Arrayable $tags)
    {
        $this->tags()
            ->detach(static::parseTags($tags));

        return $this;
    }

    /**
     * @return $this
     */
    public function detachTag(string|Tag $tag)
    {
        $this->detachTags([$tag]);

        return $this;
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentTags\Tag|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentTags\Tag|string> $tags
     *
     * @return $this
     */
    public function syncTags(iterable|\Illuminate\Contracts\Support\Arrayable $tags)
    {
        $this->tags()
            ->sync(static::parseTags($tags));

        return $this;
    }

    /**
     * @param iterable<int, \Zing\LaravelEloquentTags\Tag|string>|\Illuminate\Contracts\Support\Arrayable<int, \Zing\LaravelEloquentTags\Tag|string> $values
     */
    protected static function parseTags(iterable|\Illuminate\Contracts\Support\Arrayable $values): Collection
    {
        return Collection::make($values)->map(static fn ($value): Model => self::parseTag($value));
    }

    /**
     * @return \Zing\LaravelEloquentTags\Tag
     */
    protected static function parseTag(Model|string $value): Model
    {
        if (\is_string($value)) {
            return forward_static_call([static::getTagClassName(), 'query'])->firstOrCreate([
                'name' => $value,
            ]);
        }

        if (is_a($value, self::getTagClassName(), false)) {
            return $value;
        }

        throw new \RuntimeException('Unsupported type for tag');
    }
}
