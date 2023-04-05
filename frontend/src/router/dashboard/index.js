export default [
  {
    path: '/dashboard',
    name: 'new-dashboard',
    component: () => import('@/views/dashboard/DashboardView.vue'),
    meta: {
      pageTitle: 'New Dashboard',
      breadcrumb: [
        {
          text: 'New Dashboard',
          active: true,
        },
      ],
      resource: 'ACL',
      action: 'read',
    }
  },
]