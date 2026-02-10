# User Extras for Flarum

Adds two user fields - `title` and `short_description` - exposes them via API, shows them on user cards, provides a self-edit modal on the profile page, and integrates with FoF User Directory to add both as columns.

## Install

```bash
# From the extension root
composer install
composer require kmcginley-1928/flarum-add-user-title-and-description:*   # or path repos in composer.json
php flarum cache:clear
php flarum migrate