import app from 'flarum/forum/app';
import Modal from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';

export default class EditProfileExtrasModal extends Modal {
  oninit(vnode) {
    super.oninit(vnode);
    this.user = this.attrs.user || app.session.user;

    this.titleVal = this.user.attribute('title') || '';
    this.descVal = this.user.attribute('short_description') || '';

    this.saving = false;
  }

  className() {
    return 'Modal--small';
  }

  title() {
    return app.translator.trans('keith-user-extras.forum.modal.title');
  }

  content() {
    return (
      <div class="Modal-body">
        <div class="Form">
          <div class="Form-group">
            <label>{app.translator.trans('keith-user-extras.forum.modal.field_title')}</label>
            <input
              class="FormControl"
              type="text"
              maxlength="100"
              bidi={{
                get: () => this.titleVal,
                set: (v) => (this.titleVal = v),
              }}
              placeholder=""
            />
          </div>

          <div class="Form-group">
            <label>{app.translator.trans('keith-user-extras.forum.modal.field_short_description')}</label>
            <textarea
              class="FormControl"
              rows="4"
              maxlength="1000"
              bidi={{
                get: () => this.descVal,
                set: (v) => (this.descVal = v),
              }}
              placeholder=""
            />
          </div>

          <div class="Form-group">
            <Button
              className="Button Button--primary"
              loading={this.saving}
              onclick={this.save.bind(this)}
            >
              {this.saving
                ? app.translator.trans('keith-user-extras.forum.modal.saving')
                : app.translator.trans('keith-user-extras.forum.modal.save')}
            </Button>
          </div>
        </div>
      </div>
    );
  }

  save() {
    this.saving = true;
    const data = {
      title: this.titleVal.trim() || null,
      short_description: this.descVal.trim() || null,
    };

    return this.user
      .save(data)
      .then(() => {
        this.saving = false;
        m.redraw();
        this.hide();
      })
      .catch((e) => {
        this.saving = false;
        throw e;
      });
  }
}
