export default [
  {
    title: 'Dashboard',
    route: 'new-dashboard',
    icon: 'SlackIcon',
    resource: 'ACL',
    action: 'read',
  },
  {
    header: 'Applications',
    icon: 'ServerIcon',
    children: [
      {
        title: 'Buku Telepon',
        route: 'apps-phonebook-list',
        icon: 'PhoneIcon',
        resource: 'AppPhonebook',
        action: 'read',
      },
      {
        title: 'HR',
        route: 'apps-hris-index',
        icon: 'BriefcaseIcon',
        children: [
          {
            title: 'Departemen',
            route: 'apps-hris-dept-list',
          },
          {
            title: 'Jabatan',
            route: 'apps-hris-position-list',
            resource: 'AppHRPosition',
            action: 'read',
          },
          {
            title: 'Monitoring Absensi',
            route: 'apps-hris-presence',
            resource: 'AppHRPresence',
            action: 'read',
          },
        ]
      },
      {
        title: 'Inventaris',
        route: 'apps-inventaris-list',
        icon: 'ArchiveIcon',
        children: [
          {
            title: 'Database',
            route: 'apps-inventaris-list',
            resource: 'AppInventaris',
            action: 'read',
          },
          {
            title: 'Tambah Data',
            route: 'apps-inventaris-add',
            resource: 'AppInventaris',
            action: 'create',
          },
          {
            title: 'Export',
            route: 'apps-inventaris-export',
            resource: 'AppInventaris',
            action: 'read',
          },
        ]
      },
      {
        title: 'OKM',
        icon: 'AwardIcon',
        children: [
          {
            title: 'Materi',
            route: 'apps-elearning-materials',
          },
          {
            title: 'Soal',
            route: 'apps-elearning-questions',
          },
          {
            title: 'Jadwal Ujian',
            route: 'apps-elearning-schedule',
          },
          {
            title: 'Raport',
            route: 'apps-elearning-raport-all',
          },
        ]
      },
      {
        title: 'Surat Jalan',
        route: 'apps-sj-list',
        icon: 'TruckIcon',
        children: [
          {
            title: 'List',
            route: 'apps-sj-list',
            resource: 'AppDelivery',
            action: 'read',
          },
          {
            title: 'Form',
            route: 'apps-sj-create',
            resource: 'AppDelivery',
            action: 'create',
          },
          {
            title: 'Export',
            route: 'apps-sj-export',
            resource: 'AppDelivery',
            action: 'export',
          },
        ]
      },
      {
        title: 'Todo',
        route: 'apps-todo',
        icon: 'CheckSquareIcon',
        resource: 'AppTodo',
        action: 'read',
      },
      {
        title: 'User Management',
        route: 'apps-usermgt-list',
        icon: 'UsersIcon',
        children: [
          {
            title: 'List',
            route: 'apps-usermgt-list',
            resource: 'AppUserMgt',
            action: 'read',
          },
          /**
           * Temporary disable for Backend reason.
           * {
           * title: 'Roles',
           * route: 'apps-usermgt-roles',
           * },
           */
          {
            title: 'Permissions',
            route: 'apps-usermgt-permissions',
            resource: 'AppUserMgt',
            action: 'read',
          },
        ]
      },
      {
        title: 'Test Page',
        route: 'testpage',
        icon: 'SettingsIcon',
      },
    ],
  },
]
