export default [
  /**
  {
    title: 'Home',
    route: 'home',
    icon: 'HomeIcon',
    resource: 'Auth',
    action: 'read',
  },
   */
  {
    title: 'Dashboard',
    route: 'dashboard',
    icon: 'SlackIcon',
    resource: 'ACL',
    action: 'read',
  },
  /**
  {
    title: 'News',
    route: 'apps-news',
    icon: 'RssIcon',
    resource: 'Auth',
    action: 'read',
  },
   */
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
      /**
      {
        title: 'Buku Telepon Extension',
        route: 'apps-phonebook-list-extension',
        icon: 'PhoneIcon',
        resource: 'AppPhoneExt',
        action: 'read',
      },
       */
      {
        title: 'HR',
        route: 'apps-hris-index',
        icon: 'BriefcaseIcon',
      },
      {
        title: 'Inventaris',
        route: 'apps-inventaris-list',
        icon: 'BookIcon',
        resource: 'AppInventaris',
        action: 'read',
      },
      /**
      {
        title: 'News Admin',
        route: 'apps-news-admin',
        icon: 'RssIcon',
      },
       */
      {
        title: 'OKM',
        route: 'apps-elearning-dashboard',
        icon: 'AwardIcon',
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
      },
    ],
  },
]
