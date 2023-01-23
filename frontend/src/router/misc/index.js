export default [
    {
      path: '/comingsoon',
      name: 'comming-soon',
      component: () => import('@/views/misc/ComingSoon.vue'),
      meta: {
        resource: 'Auth',
        layout: 'full'
      },
    },
    {
        path: '/undermaintenance',
        name: 'undermaintenance',
        component: () => import('@/views/misc/UnderMaintenance.vue'),
        meta: {
          resource: 'Auth',
          layout: 'full'
        },
      },
  ]