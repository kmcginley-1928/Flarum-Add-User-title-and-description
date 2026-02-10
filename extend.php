<?php

namespace Keith\UserExtras;

use Flarum\Api\Serializer\UserSerializer;
use Flarum\Extend;
use Flarum\User\Event\Saving as UserSaving;
use Flarum\User\User;
use Keith\UserExtras\Listeners\SaveUserExtras;

return [
    // Database migration
    (new Extend\Database())->migrateFrom(__DIR__ . '/migrations'),

    // Locales
    (new Extend\Locales(__DIR__ . '/locale')),

    // Forum assets
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js'),

    // Expose attributes via API
    (new Extend\Serializer(UserSerializer::class))
        ->attributes(function (UserSerializer $serializer, User $user, array $attributes) {
            $attributes['title'] = $user->title;
            $attributes['short_description'] = $user->short_description;
            return $attributes;
        }),

    // Save attributes on PATCH /users/:id
    (new Extend\Event())
        ->listen(UserSaving::class, SaveUserExtras::class),
];