export default [
  {
    header: 'Main Menu',
    permission: 'app',
    resource: 'Auth',
    action: 'read',
  },
    /** Dashboard */
    {
      title: 'Dashboard',
      route: 'dashboard',
      icon: 'SlackIcon',
      resource: 'ACL',
      action: 'read',
    },
  {
    header: 'Applications',
    resource: 'Auth',
    action: 'read',
  },
  /** Phonebook */
  {
    title: 'Buku Telepon',
    route: '',
    icon: 'PhoneIcon',
    children: [
      {
        title: 'External',
        route: 'apps-phonebook-list',
        resource: 'AppPhonebook',
        action: 'read',
      },
      /**
       * Temporary disable for Backend reason.
       * 
       * {
       * title: 'Internal',
       * route: 'apps-phonebook-list-extension',
       * resource: 'AppPhoneExt',
       * action: 'read',
       * },
       * 
      */
    ]
  },
  /** HRIS */
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
  /** GA Inventaris */
  {
    title: 'Inventaris',
    route: 'apps-inventaris',
    icon: 'BookIcon',
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
  /** OKM */
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
        title: 'Rekap Raport',
        route: 'apps-elearning-raport-all',
      },
    ]
  },
  // {
  //   title: 'OKM',
  //   route: 'apps-elearning',
  //   icon: 'AwardIcon',
  //   children: [
  //       {
  //         title: 'Dashboard',
  //         route: 'apps-elearning-dashboard',
  //         resource: 'AppOKM',
  //         action: 'read',
  //       },
  //       {
  //         title: 'Raport',
  //         route: 'apps-elearning-raport',
  //         resource: 'AppOKMRaport',
  //         action: 'read',
  //       }
  //   ]
  // },
  // {
  //   title: 'OKM Admin',
  //   route: 'apps-elearning-dashboard-admin',
  //   icon: 'AwardIcon',
  //   resource: 'AppOKMAdmin',
  //   action: 'read',
  //   children: [
  //     {
  //       title: 'Materi',
  //       route: 'apps-elearning-materials',
  //       resource: 'AppOKMMaterial',
  //       action: 'read',
  //     },
  //     {
  //       title: 'Soal',
  //       route: 'apps-elearning-questions',
  //       resource: 'AppOKMQuestion',
  //       action: 'read',
  //     },
  //     {
  //       title: 'Ujian',
  //       route: 'apps-elearning-schedule',
  //       resource: 'AppOKMSchedule',
  //       action: 'read',
  //     },
  //     {
  //       title: 'Rekap Raport',
  //       route: 'apps-elearning-raport',
  //       resource: 'AppOKMRaport',
  //       action: 'read',
  //     },
  //   ],
  // },
  /** News */
  {
    title: 'News ',
    route: 'apps-news-admin',
    icon: 'RssIcon',
    children: [
      /**
       * Temporary disable for Backend reason.
       * 
       * {
       * title: 'List',
       * route: 'apps-news-data-list',
       * },
       * {
       * title: 'Form',
       * route: 'apps-news-create',
       * },
       */
    ]
  },
  /** Surat Jalan */
  {
    title: 'Surat Jalan',
    route: 'apps-sj',
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
  /** Todo */
  {
    title: 'Todo',
    route: 'apps-todo',
    icon: 'CheckSquareIcon',
    resource: 'AppTodo',
    action: 'read',
  },
  /** User Management */
  {
    title: 'User Management',
    route: 'apps-usermgt',
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
]
