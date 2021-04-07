<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Zing\LaravelEloquentTags\Tag;

class TagTest extends TestCase
{
    use WithFaker;

    public function testFillable(): void
    {
        $name = $this->faker->name;
        Tag::query()->create([
            'name' => $name,
        ]);
        $this->assertDatabaseHas(Tag::query()->getModel()->getTable(), [
            'name' => $name,
        ]);
    }
}
