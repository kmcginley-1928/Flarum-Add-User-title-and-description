<?php

use Flarum\Extend;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\Event\Saving;
use Kmcginley1928\AddUserTitleAndDescription\Listeners\SaveUserExtras;

return [
    // Migration loader (Asirem / minimal Flarum compatible)
    (new Extend\Compat('flarum.migrations'))
        ->directory(__DIR__ . '/migrations'),

    // Localisation files
    (new Extend\Locales(__DIR__ . '/locale')),

    // Forum JS
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js'),

    // Add fields to API output
    (new Extend\Serializer(UserSerializer::class))
        ->attributes(function (UserSerializer $serializer, $user, array $attributes) {
            $attributes['title'] = $user->title;
            $attributes['short_description'] = $user->short_description;
            return $attributes;
        }),

    // Save on PATCH /users/:id
    (new Extend\Event())
        ->listen(Saving::class, SaveUserExtras::class),
];