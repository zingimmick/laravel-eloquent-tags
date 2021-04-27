<?php

declare(strict_types=1);

use Zing\LaravelEloquentTags\Tag;

return [
    'load_migrations' => true,
    'models' => [
        'tag' => Tag::class,
    ],
    'table_names' => [
        'tags' => 'tags',
        'model_has_tags' => 'taggables',
    ],
];
