export default [
  {
    name: 'app-usermgt',
    path: 'user',
    component: () => import('@/views/apps/usermanagement/UserManagementView.vue'),
    children: [
      { 
        path: 'all',
        component: () => import('@/component/apps/usermanagement/UserList.vue'),
        name: 'apps-usermgt-list',
        meta: {
          pageTitle: 'User Management',
          title: 'User Mgmt.',
          breadcrumb: [
            { text: 'User Management', active: true }
          ],
          resource: 'AppUserMgt',
          action: 'read',
        },
      },
      { 
        path: 'edit/:id',
        component: () => import('@/component/apps/usermanagement/UserEdit.vue'),
        name: 'apps-usermgt-useredit',
        meta: {
          pageTitle: 'User Management',
          title: 'User Mgmt.',
          breadcrumb: [
            { text: 'User Management', to: { name: 'apps-usermgt-list'} },
            { text: 'Edit', active: true }
          ],
          resource: 'AppUserMgt',
          action: 'edit',
        },
      },
      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      //                                        Roles and Permission Management                                           //
      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      { 
        path: 'roles',
        component: () => import('@/component/apps/usermanagement/permissions/RolesList.vue'),
        name: 'apps-usermgt-roles',
        meta: {
          pageTitle: 'User Management',
          title: 'User Mgmt.',
          breadcrumb: [
            { text: 'User Management', to: { name: 'apps-usermgt-list'} },
            { text: 'Roles', active: true }
          ],
        },
      },
      { 
        path: 'permissions',
        component: () => import('@/component/apps/usermanagement/permissions/PermissionsList.vue'),
        name: 'apps-usermgt-permissions',
        meta: {
          pageTitle: 'Daftar Permissions',
          title: 'User Mgmt.',
          breadcrumb: [
            { text: 'User Management', to: { name: 'apps-usermgt-list'} },
            { text: 'Permissions', active: true }
          ],
          resource: 'AppUserMgt',
          action: 'read',
        },
      },
      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      //                                                User Settings                                                     //
      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      { 
        path: ':id/user-settings/:nik',
        component: () => import('@/component/apps/usermanagement/account-settings/AccountSettings.vue'),
        name: 'apps-usermgt-accountsettings',
        meta: {
          pageTitle: 'Pengaturan',
          title: 'User Management.',
          breadcrumb: [
            { text: 'Pengaturan', active: true }
          ],
        },
      },
    ],
  },
]