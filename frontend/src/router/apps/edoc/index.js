export default [
  {
    path: 'edoc',
    name: 'apps-edoc',
    component: () => import('@/views/apps/edoc/EdocView.vue'),
    children: [
      {
        path: 'dashboard',
        name: 'apps-edoc-dashboard',
        component: () => import('@/component/apps/edoc/EdocDashboard.vue')
      }
    ]
  }
]