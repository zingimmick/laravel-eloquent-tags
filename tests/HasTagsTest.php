<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags\Tests;

use Illuminate\Database\Eloquent\Collection;
use Zing\LaravelEloquentTags\Tag;
use Zing\LaravelEloquentTags\Tests\Models\CustomTag;
use Zing\LaravelEloquentTags\Tests\Models\Product;

 /**
  * @internal
  */
 final class HasTagsTest extends TestCase
 {
     /**
      * @before
      */
     public function setUpProduct(): void
     {
         $this->afterApplicationCreated(function (): void {
             $this->product = Product::query()->create();
         });
     }

     /**
      * @before
      */
     public function setUpTagClass(): void
     {
         $this->afterApplicationCreated(function (): void {
             $data = $this->getProvidedData();
             if (isset($data[0])) {
                 config([
                     'eloquent-tags.models.tag' => $data[0],
                 ]);
             }
         });
     }

     /**
      * @return \Iterator<array{class-string<\Zing\LaravelEloquentTags\Tag>}|array{class-string<\Zing\LaravelEloquentTags\Tests\Models\CustomTag>}>
      */
     public function provideClasses(): iterable
     {
         yield [Tag::class];
         yield [CustomTag::class];
     }

     /**
      * @var \Zing\LaravelEloquentTags\Tests\Models\Product
      */
     private $product;

     /**
      * @dataProvider provideClasses
      *
      * @param class-string $tagClass
      */
     public function testDetachTags(string $tagClass): void
     {
         $this->product->attachTags(['foo', 'bar']);
         $this->product->detachTags(['foo']);
         self::assertSame(1, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
     }

     /**
      * @dataProvider provideClasses
      *
      * @param class-string $tagClass
      */
     public function testAttachTags(string $tagClass): void
     {
         $this->product->attachTags(['foo', 'bar']);
         self::assertSame(2, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
     }

     /**
      * @dataProvider provideClasses
      *
      * @param class-string $tagClass
      */
     public function testTags(string $tagClass): void
     {
         $this->product->attachTags(['foo', 'bar']);
         self::assertInstanceOf($tagClass, $this->product->tags()->first());
         self::assertInstanceOf(Collection::class, $this->product->tags()->get());
     }

     /**
      * @dataProvider provideClasses
      *
      * @param class-string $tagClass
      */
     public function testAttachTag(string $tagClass): void
     {
         $this->product->attachTag('foo');
         self::assertSame(1, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
     }

     /**
      * @dataProvider provideClasses
      *
      * @param class-string $tagClass
      */
     public function testDetachTag(string $tagClass): void
     {
         $this->product->attachTags(['foo', 'bar']);
         $this->product->detachTag('foo');
         self::assertSame(1, $this->product->tags()->whereIn('name', ['foo', 'bar'])->count());
     }

     /**
      * @dataProvider provideClasses
      *
      * @param class-string $tagClass
      */
     public function testScopeWithAllTags(string $tagClass): void
     {
         $this->product->attachTag('foo');
         self::assertFalse(Product::query()->withAllTags(['foo', 'bar'])->exists());
         self::assertTrue(Product::query()->withAllTags(['foo'])->exists());
     }

     /**
      * @dataProvider provideClasses
      *
      * @param class-string $tagClass
      */
     public function testSyncTags(string $tagClass): void
     {
         $this->product->attachTags(['foo', 'bar']);
         $this->product->syncTags([$this->product->tags()->firstOrFail()]);
         self::assertSame(1, $this->product->tags()->count());
         $this->product->syncTags([]);
         self::assertSame(0, $this->product->tags()->count());
     }

     /**
      * @dataProvider provideClasses
      *
      * @param class-string $tagClass
      */
     public function testScopeWithAnyTags(string $tagClass): void
     {
         $this->product->attachTag('foo');
         self::assertTrue(Product::query()->withAnyTags(['foo', 'bar'])->exists());
     }
 }
