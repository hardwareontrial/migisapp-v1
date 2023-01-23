export default [
  {
    path: 'hr',
    name: 'apps-hris',
    component: () => import('@/views/apps/hris/HRISView.vue'),
    children: [
      { 
        path: 'index',
        name: 'apps-hris-index',
        component: () => import('@/component/apps/hris/HRISIndex.vue'),
        meta: {
          pageTitle: 'HR',
          title: 'HR',
          breadcrumb: [
          { text: 'HRIS', active: true }
          ],
        }
      },
      { 
        path: 'departemen',
        name: 'apps-hris-dept-list',
        component: () => import('@/component/apps/hris/departemen/HRISDeptList.vue'),
        meta: {
          pageTitle: 'Departemen',
          title: 'HR',
          breadcrumb: [
            { text: 'HRIS', to: { name: 'apps-hris-index'} },
            { text: 'Departemen', active: true }
          ],
        }
      },
      { 
        path: 'jabatan',
        name: 'apps-hris-position-list',
        component: () => import('@/component/apps/hris/position/HRISPositionList.vue'),
        meta: {
          pageTitle: 'Jabatan',
          title: 'HR',
          breadcrumb: [
            { text: 'HRIS', to: { name: 'apps-hris-index'} },
            { text: 'Jabatan', active: true }
          ],
          resource: 'AppHRPosition',
          action: 'read',
        }
      },
      { 
        path: 'presence',
        name: 'apps-hris-presence',
        component: () => import('@/component/apps/hris/presence/HRISPresenceList.vue'),
        meta: {
          pageTitle: 'Monitoring Absensi',
          title: 'HR',
          breadcrumb: [
            { text: 'HR', to: { name: 'apps-hris-index'} },
            { text: 'Monitoring Absensi', active: true }
          ],
          resource: 'AppHRPresence',
          action: 'read',
        }
      }
    ],
  }
]