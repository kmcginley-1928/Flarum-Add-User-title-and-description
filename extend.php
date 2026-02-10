<?php

use Flarum\Extend;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\Event\Saving;

$extenders = [
    // Locales
    (new Extend\Locales(__DIR__ . '/locale')),

    // Forum JS (safe even if file is missing; will just 404)
    (new Extend\Frontend('forum'))->js(__DIR__ . '/js/dist/forum.js'),

    // Expose fields in API
    (new Extend\Serializer(\Flarum\Api\Serializer\UserSerializer::class))
        ->attributes(function (\Flarum\Api\Serializer\UserSerializer $serializer, $user, array $attributes) {
            $attributes['title'] = $user->title;
            $attributes['short_description'] = $user->short_description;
            return $attributes;
        }),
];

// Guard the listener so boot never fatals if autoload/namespace is off
if (class_exists(\Kmcginley1928\AddUserTitleAndDescription\Listeners\SaveUserExtras::class)) {
    $extenders[] = (new Extend\Event())
        ->listen(Saving::class, \Kmcginley1928\AddUserTitleAndDescription\Listeners\SaveUserExtras::class);
}

return $extenders;