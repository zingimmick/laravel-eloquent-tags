<?php

declare(strict_types=1);

namespace Zing\LaravelEloquentTags;

use Illuminate\Support\ServiceProvider;

class EloquentTagsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    $this->getConfigPath() => config_path('eloquent-tags.php'),
                ],
                'config'
            );
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'eloquent-tags');
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        }
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/eloquent-tags.php';
    }
}
