import gainventaris from "./gainventaris"
import usermanagement from "./usermanagement"
import suratjalan from "./suratjalan"
import phonebook from "./phonebook"
import todo from "./todo"
import news from "./news"
import hris from "./hris"
import elearning from "./elearning"

export default [
  {
    path: '/apps',
    component: () => import('@/views/apps/AppView.vue'),
    children: [
      {
        path: 'dashboard',
        name: 'dashboard',
        component: () => import('@/views/Dashboard.vue'),
        meta: {
          pageTitle: 'Dashboard',
          breadcrumb: [
            {
              text: 'Dashboard',
              active: true,
            },
          ],
          resource: 'ACL',
          action: 'read',
        },
      },
      ...elearning,
      ...todo,
      ...gainventaris,
      ...phonebook,
      ...suratjalan,
      ...usermanagement,
      ...hris,
      ...news,
    ]
  }
]