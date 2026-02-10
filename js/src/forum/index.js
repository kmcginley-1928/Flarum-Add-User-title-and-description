import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import UserPage from 'flarum/forum/components/UserPage';
import UserCard from 'flarum/forum/components/UserCard';
import Button from 'flarum/common/components/Button';

import EditProfileExtrasModal from './components/EditProfileExtrasModal';
import './extendUserDirectory'; // Safe, guards inside

app.initializers.add('keith-user-extras', () => {
  // Add a button on your own profile to edit the new fields
  extend(UserPage.prototype, 'navItems', function (items) {
    const viewingSelf = app.session?.user && this.user && app.session.user.id() === this.user.id();
    if (viewingSelf) {
      items.add(
        'edit-profile-extras',
        Button.component(
          {
            className: 'Button Button--link',
            onclick: () => app.modal.open(EditProfileExtrasModal, { user: this.user }),
          },
          app.translator.trans('keith-user-extras.forum.edit_button')
        ),
        90
      );
    }
  });

  // Show the title and short_description on the user card
  extend(UserCard.prototype, 'infoItems', function (items) {
    const user = this.attrs.user;

    const title = user.attribute('title');
    if (title) {
      items.add(
        'title',
        <div class="UserExtras-title">{title}</div>,
        5
      );
    }

    const desc = user.attribute('short_description');
    if (desc) {
      items.add(
        'short_description',
        <div class="UserExtras-shortDescription">{desc}</div>,
        4
      );
    }
  });
});