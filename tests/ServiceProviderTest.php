<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags\Tests;

/**
 * @internal
 */
final class ServiceProviderTest extends TestCase
{
    public function testConfig(): void
    {
        $this->assertIsArray(config('eloquent-tags'));
    }
}
