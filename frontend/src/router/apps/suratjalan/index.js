export default [
  {
    path: 'delivery',
    name: 'apps-delivery',
    component: () => import('@/views/apps/suratjalan/SuratJalanView.vue'),
    children: [
      { 
        path: 'data',
        component: () => import('@/component/apps/suratjalan/SuratJalanList.vue'),
        name: 'apps-sj-list',
        meta: {
          pageTitle: 'Surat Jalan',
          title: 'Surat Jalan',
          breadcrumb: [
            { text: 'Surat Jalan', active: true }
          ],
          resource: 'AppDelivery',
          action: 'read',
        },
      },
      { 
        path: 'create',
        component: () => import('@/component/apps/suratjalan/SuratJalanForm.vue'),
        name: 'apps-sj-create',
        meta: {
          pageTitle: 'Form',
          title: 'Surat Jalan',
          breadcrumb: [
            { text: 'Surat Jalan', to: { name: 'apps-sj-list'} },
            { text: 'Buat', active: true }
          ],
          resource: 'AppDelivery',
          action: 'create',
        },
      },
      { 
        path: 'edit/:id',
        component: () => import('@/component/apps/suratjalan/SuratJalanForm.vue'),
        name: 'apps-sj-edit',
        meta: {
          pageTitle: 'Edit Surat Jalan',
          title: 'Surat Jalan',
          breadcrumb: [
            { text: 'Surat Jalan', to: { name: 'apps-sj-list'} },
            { text: 'Edit', active: true }
          ],
          resource: 'AppDelivery',
          action: 'edit',
        },
        props: true,
      },
      { 
        path: 'export',
        component: () => import('@/component/apps/suratjalan/SuratJalanExport.vue'),
        name: 'apps-sj-export',
        meta: {
          pageTitle: 'Export Surat Jalan',
          title: 'Surat Jalan',
          breadcrumb: [
            { text: 'Surat Jalan', to: { name: 'apps-sj-list'} },
            { text: 'Export', active: true }
          ],
          resource: 'AppDelivery',
          action: 'export',
        },
      },
    ]
  },
]