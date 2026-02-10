import app from 'flarum/forum/app';

// This file safely integrates with FoF User Directory if it is present.
// It tries to require the helper only if the module exists.
app.initializers.add('keith-user-extras-user-directory', () => {
  // If FoF User Directory is not enabled, exit quietly.
  // The following guard works at runtime, while the require is guarded in a try..catch.
  const hasFof = app.data?.extensions?.['fof-user-directory'];
  if (!hasFof) return;

  let addColumn;
  try {
    // Optional dependency
    // eslint-disable-next-line global-require
    addColumn = require('fof-user-directory/utils/addColumn').default;
  } catch (e) {
    // Module not available
    return;
  }

  // Add a Title column
  addColumn('title', {
    name: 'title',
    label: app.translator.trans('keith-user-extras.directory.col_title'),
    component: (user) => user.attribute('title') || '',
    sortable: false,
  });

  // Add a Description column
  addColumn('short_description', {
    name: 'short_description',
    label: app.translator.trans('keith-user-extras.directory.col_description'),
    component: (user) => user.attribute('short_description') || '',
    sortable: false,
  });
});