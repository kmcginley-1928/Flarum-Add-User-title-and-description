<?php

use Flarum\Extend;
use Flarum\User\Event\Saving;
use Kmcginley1928\AddUserTitleAndDescription\Listeners\SaveUserExtras;

$extenders = [

    // Locales are safe everywhere
    (new Extend\Locales(__DIR__ . '/locale')),

    // Forum JS (safe even if file missing)
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js'),

    // Add API attributes (correct extender for your Flarum build)
    (new Extend\ApiSerializer(\Flarum\Api\Serializer\UserSerializer::class))
        ->attribute('title', function ($serializer, $user) {
            return $user->title;
        })
        ->attribute('short_description', function ($serializer, $user) {
            return $user->short_description;
        }),
];

// Add listener only if class exists (prevents boot fatal)
if (class_exists(\Kmcginley1928\AddUserTitleAndDescription\Listeners\SaveUserExtras::class)) {
    $extenders[] = (new Extend\Event())
        ->listen(Saving::class, \Kmcginley1928\AddUserTitleAndDescription\Listeners\SaveUserExtras::class);
}

return $extenders;