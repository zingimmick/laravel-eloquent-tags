<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags\Tests;

use Zing\LaravelEloquentTags\Tests\Models\CustomTag;

class CustomTagTest extends HasTagsTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        config([
            'eloquent-tags.models.tag' => $this->getTagClassName(),
        ]);
    }

    protected function getTagClassName()
    {
        return CustomTag::class;
    }
}
