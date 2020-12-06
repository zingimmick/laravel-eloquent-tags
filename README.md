# Laravel Eloquent Tags
<p align="center">
<a href="https://github.com/zingimmick/laravel-eloquent-tags/actions"><img src="tag" alt="Build Status"></a>
<a href="https://codecov.io/gh/zingimmick/laravel-eloquent-tags"><img src="https://codecov.io/gh/zingimmick/laravel-eloquent-tags/branch/master/graph/badge.svg" alt="Code Coverage" /></a>
<a href="https://packagist.org/packages/zing/laravel-eloquent-tags"><img src="https://poser.pugx.org/zing/laravel-eloquent-tags/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-eloquent-tags"><img src="https://poser.pugx.org/zing/laravel-eloquent-tags/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/zing/laravel-eloquent-tags"><img src="https://poser.pugx.org/zing/laravel-eloquent-tags/v/unstable.svg" alt="Latest Unstable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-eloquent-tags"><img src="https://poser.pugx.org/zing/laravel-eloquent-tags/license" alt="License"></a>
<a href="https://codeclimate.com/github/zingimmick/laravel-eloquent-tags/maintainability"><img src="https://api.codeclimate.com/v1/badges/68a450555e0ccf3ffa72/maintainability" alt="Code Climate" /></a>
</p>

> **Requires [PHP 7.2.0+](https://php.net/releases/)**

Require Laravel Eloquent Tags using [Composer](https://getcomposer.org):

```bash
composer require zing/laravel-eloquent-tags
```

## Usage

```php
use Zing\LaravelEloquentTags\Tests\Models\Product;
use Zing\LaravelEloquentTags\Tag;

$product = Product::query()->first();
// Add tag(s) to model
$product->attachTag("tag");
$product->attachTags([
    "tag",
    Tag::query()->first()
]);
// Remove tag(s) from model
$product->detachTag("tag");
$product->detachTags([
    "tag",
    Tag::query()->first()
]);
// Reset tags of model
$product->syncTags([
    "tag",
    Tag::query()->first()
]);
// Get tags of model
$product->tags;
// Eager load tags
$products = Product::query()->with('tags')->withCount('tags')->get();
$products->each(function (Product $product){
    $product->tags->dump();
    $product->tags_count;
});
// Query by tag
Product::query()->withAnyTags(['tag', 'github'])->exists(); // true
Product::query()->withAllTags(['tag', 'github'])->exists(); // false
```

## License

Laravel Eloquent Tags is an open-sourced software licensed under the [MIT license](LICENSE).
