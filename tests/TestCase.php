<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Zing\LaravelEloquentTags\EloquentTagsServiceProvider;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        Schema::create(
            'products',
            function (Blueprint $table): void {
                $table->bigIncrements('id');
                $table->timestamps();
            }
        );
    }

    protected function getEnvironmentSetUp($app): void
    {
        config([
            'database.default' => 'testing',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [EloquentTagsServiceProvider::class];
    }
}
