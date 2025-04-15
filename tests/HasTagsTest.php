<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags\Tests;

use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DataProvider;
use Zing\LaravelEloquentTags\Tag;
use Zing\LaravelEloquentTags\Tests\Models\CustomTag;
use Zing\LaravelEloquentTags\Tests\Models\Product;

/**
 * @internal
 */
final class HasTagsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::query()->create();
    }

    /**
     * @before
     */
    #[Before]
    public function setUpTagClass(): void
    {
        $this->afterApplicationCreated(function (): void {
            $data = method_exists($this, 'getProvidedData') ? $this->getProvidedData() : $this->providedData();
            if (isset($data[0])) {
                config([
                    'eloquent-tags.models.tag' => $data[0],
                ]);
            }
        });
    }

    private Product $product;

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $tagClass
     */
    #[DataProvider('provideClasses')]
    public function testDetachTags(string $tagClass): void
    {
        $this->product->attachTags(['foo', 'bar']);
        $this->product->detachTags(['foo']);
        $this->assertSame(1, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $tagClass
     */
    #[DataProvider('provideClasses')]
    public function testAttachTags(string $tagClass): void
    {
        $this->product->attachTags(['foo', 'bar']);
        $this->assertSame(2, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $tagClass
     */
    #[DataProvider('provideClasses')]
    public function testTags(string $tagClass): void
    {
        $this->product->attachTags(['foo', 'bar']);
        $this->assertInstanceOf($tagClass, $this->product->tags()->first());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $tagClass
     */
    #[DataProvider('provideClasses')]
    public function testAttachTag(string $tagClass): void
    {
        $this->product->attachTag('foo');
        $this->assertSame(1, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $tagClass
     */
    #[DataProvider('provideClasses')]
    public function testDetachTag(string $tagClass): void
    {
        $this->product->attachTags(['foo', 'bar']);
        $this->product->detachTag('foo');
        $this->assertSame(1, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $tagClass
     */
    #[DataProvider('provideClasses')]
    public function testScopeWithAllTags(string $tagClass): void
    {
        $this->product->attachTag('foo');
        $this->assertFalse(Product::query()->withAllTags(['foo', 'bar'])->exists());
        $this->assertTrue(Product::query()->withAllTags(['foo'])->exists());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $tagClass
     */
    #[DataProvider('provideClasses')]
    public function testSyncTags(string $tagClass): void
    {
        $this->product->attachTags(['foo', 'bar']);

        /** @var \Zing\LaravelEloquentTags\Tag $tag */
        $tag = $this->product->tags()
            ->firstOrFail();
        $this->product->syncTags([$tag]);
        $this->assertSame(1, $this->product->tags()->count());
        $this->product->syncTags([]);
        $this->assertSame(0, $this->product->tags()->count());
    }

    /**
     * @dataProvider provideClasses
     *
     * @param class-string $tagClass
     */
    #[DataProvider('provideClasses')]
    public function testScopeWithAnyTags(string $tagClass): void
    {
        $this->product->attachTag('foo');
        $this->assertTrue(Product::query()->withAnyTags(['foo', 'bar'])->exists());
    }

    /**
     * @return \Iterator<array{class-string<\Zing\LaravelEloquentTags\Tag>}|array{class-string<\Zing\LaravelEloquentTags\Tests\Models\CustomTag>}>
     */
    public static function provideClasses(): \Iterator
    {
        yield [Tag::class];

        yield [CustomTag::class];
    }
}
