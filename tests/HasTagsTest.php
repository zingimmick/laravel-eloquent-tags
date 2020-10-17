<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags\Tests;

use Illuminate\Database\Eloquent\Collection;
use Zing\LaravelEloquentTags\Tag;
use Zing\LaravelEloquentTags\Tests\Models\Product;

class HasTagsTest extends \Zing\LaravelEloquentTags\Tests\TestCase
{
    protected function getTagClassName()
    {
        return Tag::class;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::query()->create();
    }

    protected $product;

    public function testDetachTags(): void
    {
        $this->product->attachTags(['foo', 'bar']);
        $this->product->detachTags(['foo']);
        self::assertSame(1, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
    }

    public function testAttachTags(): void
    {
        $this->product->attachTags(['foo', 'bar']);
        self::assertSame(2, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
    }

    public function testTags(): void
    {
        $this->product->attachTags(['foo', 'bar']);
        self::assertInstanceOf($this->getTagClassName(), $this->product->tags()->first());
        self::assertInstanceOf(Collection::class, $this->product->tags()->get());
    }

    public function testAttachTag(): void
    {
        $this->product->attachTag('foo');
        self::assertSame(1, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
    }

    public function testDetachTag(): void
    {
        $this->product->attachTags(['foo', 'bar']);
        $this->product->detachTag('foo');
        self::assertSame(1, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
    }

    public function testScopeWithAllTags(): void
    {
        $this->product->attachTag('foo');
        self::assertFalse(Product::query()->withAllTags(['foo', 'bar'])->exists());
        self::assertTrue(Product::query()->withAllTags(['foo'])->exists());
    }

    public function testSyncTags(): void
    {
        $this->product->attachTags(['foo', 'bar']);
        $this->product->syncTags([$this->product->tags()->first()]);
        self::assertSame(1, $this->product->tags()->count());
        $this->product->syncTags([]);
        self::assertSame(0, $this->product->tags()->count());
    }

    public function testScopeWithAnyTags(): void
    {
        $this->product->attachTag('foo');
        self::assertTrue(Product::query()->withAnyTags(['foo', 'bar'])->exists());
    }
}
