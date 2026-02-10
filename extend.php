<?php

use Flarum\Extend;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\Event\Saving;
use Kmcginley1928\AddUserTitleAndDescription\Listeners\SaveUserExtras;

return [
    // Load DB migrations
    (new Extend\Migrations())->add(__DIR__ . '/migrations'),

    // Localisation files
    (new Extend\Locales(__DIR__ . '/locale')),

    // Forum JS bundle
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js'),

    // Expose extra attributes on users
    (new Extend\Serializer(UserSerializer::class))
        ->attributes(function (UserSerializer $serializer, $user, array $attributes) {
            $attributes['title'] = $user->title;
            $attributes['short_description'] = $user->short_description;
            return $attributes;
        }),

    // Persist on PATCH /users/:id
    (new Extend\Event())
        ->listen(Saving::class, SaveUserExtras::class),
];