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
        resource: 'AppHRPosition',
        action: 'read',
      },
      {
        title: 'Inventaris',
        route: 'apps-inventaris-list',
        icon: 'ArchiveIcon',
        resource: 'AppInventaris',
        action: 'read',
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
        resource: 'AppDelivery',
        action: 'read',
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
        resource: 'AppUserMgt',
        action: 'read',
      },
      {
        title: 'Test Page',
        route: 'testpage',
        icon: 'SettingsIcon',
      },
    ],
  },
]
