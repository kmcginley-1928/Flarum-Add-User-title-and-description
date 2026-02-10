<?php

namespace Kmcginley1928\AddUserTitleAndDescription\Listeners;

use Flarum\Foundation\ValidationException;
use Flarum\User\Event\Saving;
use Illuminate\Support\Arr;

class SaveUserExtras
{
    public function handle(Saving $event): void
    {
        $attributes = Arr::get($event->data, 'attributes', []);

        if (!array_key_exists('title', $attributes) &&
            !array_key_exists('short_description', $attributes)) {
            return;
        }

        $actor = $event->actor;
        $user  = $event->user;

        if ($actor->id !== $user->id && !$actor->can('edit', $user)) {
            throw new ValidationException([
                'permission' => ['You do not have permission to update these fields.']
            ]);
        }

        if (array_key_exists('title', $attributes)) {
            $title = trim((string) $attributes['title']);
            if (mb_strlen($title) > 100) {
                throw new ValidationException([
                    'title' => ['Title must be under 100 characters.']
                ]);
            }
            $user->title = $title ?: null;
        }

        if (array_key_exists('short_description', $attributes)) {
            $desc = trim((string) $attributes['short_description']);
            if (mb_strlen($desc) > 1000) {
                throw new ValidationException([
                    'short_description' => ['Description must be under 1000 characters.']
                ]);
            }
            $user->short_description = $desc ?: null;
        }
    }
}