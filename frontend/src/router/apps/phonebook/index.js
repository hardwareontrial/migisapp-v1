export default [
  {
    path: 'phonebook',
    name: 'apps-phonebook',
    component: () => import('@/views/apps/phonebook/PhonebookView.vue'),
    children: [
      { 
        path: 'external',
        component: () => import('@/component/apps/phonebook/PhonebookList.vue'),
        name: 'apps-phonebook-list',
        meta: {
          pageTitle: 'Buku Telepon',
          title: 'Buku Telepon',
          breadcrumb: [
            { text: 'Buku Telepon', to: { name: ''} },
            { text: 'External', active: true }
          ],
          resource: 'AppPhonebook',
          action: 'read',
        },
      },
      { 
        path: 'extension',
        component: () => import('@/component/apps/phonebook/PhonebookListExtension.vue'),
        name: 'apps-phonebook-list-extension',
        meta: {
          pageTitle: 'Buku Telepon Internal',
          title: 'Buku Telepon Ext.',
          breadcrumb: [
            { text: 'Buku Telepon', to: { name: ''} },
            { text: 'Internal', active: true }
          ],
          resource: 'AppPhoneExt',
          action: 'read',
        },
      },
    ]
  }
]