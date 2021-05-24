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
                'eloquent-tags-config'
            );
            $this->publishes(
                [
                    $this->getMigrationsPath() => database_path('migrations'),
                ],
                'eloquent-tags-migrations'
            );
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'eloquent-tags');
        if (! $this->app->runningInConsole()) {
            return;
        }
        if (! $this->shouldLoadMigrations()) {
            return;
        }
        $this->loadMigrationsFrom($this->getMigrationsPath());
    }

    protected function getMigrationsPath(): string
    {
        return __DIR__ . '/../migrations';
    }

    private function shouldLoadMigrations(): bool
    {
        return (bool) config('eloquent-tags.load_migrations');
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/eloquent-tags.php';
    }
}
