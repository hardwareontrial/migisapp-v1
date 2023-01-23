export default [
  {
    path: 'inventaris',
    name: 'apps-inventaris',
    component: () => import('@/views/apps/gainventaris/InventarisView.vue'),
    redirect: { name: 'apps-inventaris-list' },
    children: [
      { 
        path: 'list',
        component: () => import('@/component/apps/gainventaris/InventarisList.vue'),
        name: 'apps-inventaris-list',
        meta: {
          pageTitle: 'Inventaris',
          title: 'Inventaris',
          breadcrumb: [
            { text: 'Inventaris', active: true }
          ],
          resource: 'AppInventaris',
          action: 'read',
        },
      },
      { 
        path: 'add',
        component: () => import('@/component/apps/gainventaris/InventarisForm.vue'),
        name: 'apps-inventaris-add',
        meta: {
          pageTitle: 'Form',
          title: 'Inventaris',
          breadcrumb: [
            { text: 'Inventaris', to: { name: 'apps-inventaris-list'} },
            { text: 'Add', active: true }
          ],
          resource: 'AppInventaris',
          action: 'create',
        },
      },
      { 
        path: 'edit/:id',
        component: () => import('@/component/apps/gainventaris/InventarisForm.vue'),
        name: 'apps-inventaris-edit',
        meta: {
          pageTitle: 'Form',
          title: 'Inventaris',
          breadcrumb: [
            { text: 'Inventaris', to: { name: 'apps-inventaris-list'} },
            { text: 'Edit', active: true }
          ],
          resource: 'AppInventaris',
          action: 'edit',
        },
        props: true,
      },
      { 
        path: 'export',
        component: () => import('@/component/apps/gainventaris/InventarisExport.vue'),
        name: 'apps-inventaris-export',
        meta: {
          pageTitle: 'Export',
          title: 'Inventaris',
          breadcrumb: [
            { text: 'Inventaris', to: { name: 'apps-inventaris-list'} },
            { text: 'Export', active: true }
          ],
          resource: 'AppInventaris',
          action: 'read',
        },
      },
    ],
  },
  {
    /** Shoe Detail From Scan QR */
    path: '/inventaris/show/:id',
    component: () => import('@/component/apps/gainventaris/InventarisDetail.vue'),
    name: 'apps-inventaris-showfromqr',
    meta: {
      layout: 'full',
      resource: 'Auth',
    },
  }
]