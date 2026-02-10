<?php

namespace Keith\UserExtras\Listeners;

use Flarum\Foundation\ValidationException;
use Flarum\User\Event\Saving;
use Illuminate\Support\Arr;

class SaveUserExtras
{
    public function handle(Saving $event)
    {
        $attributes = Arr::get($event->data, 'attributes', []);

        if (!array_key_exists('title', $attributes) && !array_key_exists('short_description', $attributes)) {
            return;
        }

        // Permission: allow user to edit own fields, or anyone who can edit the user
        $actor = $event->actor;
        $user  = $event->user;
        $canEdit = $actor->id === $user->id || $actor->can('edit', $user);

        if (!$canEdit) {
            throw new ValidationException([
                'permission' => ['You do not have permission to update profile extras.'],
            ]);
        }

        if (array_key_exists('title', $attributes)) {
            $title = $attributes['title'];
            if (!is_null($title)) {
                $title = trim((string) $title);
                if (mb_strlen($title) > 200) {
                    throw new ValidationException([
                        'title' => ['Title may not be greater than 200 characters.'],
                    ]);
                }
            }
            $user->title = $title ?: null;
        }

        if (array_key_exists('short_description', $attributes)) {
            $desc = $attributes['short_description'];
            if (!is_null($desc)) {
                $desc = trim((string) $desc);
                // Soft limit for sanity. Raise if needed.
                if (mb_strlen($desc) > 1000) {
                    throw new ValidationException([
                        'short_description' => ['Description may not be greater than 1000 characters.'],
                    ]);
                }
            }
            $user->short_description = $desc ?: null;
        }
    }
}