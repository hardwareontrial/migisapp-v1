import { Route } from 'vue-router'

export default [
  {
    path: 'news',
    name: 'apps-news',
    component: () => import('@/views/apps/news/NewsView.vue'),
    redirect: { name: 'apps-news-list' },
    children: [
      { 
        path: 'news/index',
        component: () => import('@/component/apps/news/NewsList.vue'),
        name: 'apps-news-list',
        meta: {
          pageTitle: 'News',
          breadcrumb: [
            { text: 'News', active: true }
          ],
          dlayout: 'horizontal',
          resource: 'Auth',
          action: 'read',
        },
      },
      {
        path: ':id',
        component: () => import('@/component/apps/news/NewsDetail.vue'),
        name: 'apps-news-detail',
        meta: {
          pageTitle: 'News',
          dlayout: 'horizontal',
          resource: 'Auth',
          action: 'read',
        },
      },
    ],
  },
  {
    path: 'news/admin',
    name: 'apps-news-admin',
    component: () => import('@/views/apps/news/NewsAdminView.vue'),
    redirect: { name: 'apps-news-data-list' },
    children: [
      { 
        path: 'datanews',
        component: () => import('@/component/apps/news/admin/NewsList.vue'),
        name: 'apps-news-data-list',
        meta: {
          pageTitle: 'News',
          breadcrumb: [
            { text: 'News', active: true }
          ],
          dlayout: 'vertical',
        },
      },
      { 
        path: 'create',
        component: () => import('@/component/apps/news/admin/NewsForm.vue'),
        name: 'apps-news-create',
        meta: {
          pageTitle: 'News',
          breadcrumb: [
            { text: 'News', to: { name: 'apps-news-admin'} },
            { text: 'Create', active: true }
          ],
          dlayout: 'vertical',
        },
      },
      { 
        path: 'edit/:id',
        component: () => import('@/component/apps/news/admin/NewsForm.vue'),
        name: 'apps-news-edit',
        meta: {
          pageTitle: 'News',
          breadcrumb: [
            { text: 'News', to: { name: 'apps-news-admin'} },
            { text: 'Edit', active: true }
          ],
          dlayout: 'vertical',
        },
        props: { default: true },
      },
    ],




  //   path: '',
  //   name: '',
  //   component: () => import('@/views/apps/news/NewsView.vue'),
  //   children: [

  //     ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     //                                           HANDLE NEWS APP                                              //
  //     ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     { 
  //       path: 'apps/news',
  //       component: () => import('@/component/apps/news/NewsList.vue'),
  //       name: 'apps-news',
  //       meta: {
  //         pageTitle: 'News',
  //         breadcrumb: [
  //           { text: 'News', active: true }
  //         ],
  //         dlayout: 'horizontal',
  //         resource: 'Auth',
  //         action: 'read',
  //       },
  //     },
  //     {
  //       path: 'apps/news/:id',
  //       component: () => import('@/component/apps/news/NewsDetail.vue'),
  //       name: 'apps-news-detail',
  //       meta: {
  //         // breadcrumb: [
  //         //   { text: 'News', to: { name: 'apps-news'} },
  //         //   { text: '', active: true }
  //         // ],
  //         pageTitle: 'News',
  //         dlayout: 'horizontal',
  //         resource: 'Auth',
  //         action: 'read',
  //       },
  //     },
  //     ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     //                                          HANDLE ADMIN NEWS APP                                         //
  //     ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     { 
  //       path: 'apps/news/admin',
  //       component: () => import('@/component/apps/news/admin/NewsList.vue'),
  //       name: 'apps-news-admin',
  //       meta: {
  //         pageTitle: 'News',
  //         breadcrumb: [
  //           { text: 'Application', to: { name: 'applications'} },
  //           { text: 'News', active: true }
  //         ],
  //         dlayout: 'vertical',
  //       },
  //     },
  //     { 
  //       path: 'apps/news/admin/create',
  //       component: () => import('@/component/apps/news/admin/NewsForm.vue'),
  //       name: 'apps-news-create',
  //       meta: {
  //         pageTitle: 'News',
  //         breadcrumb: [
  //           { text: 'Application', to: { name: 'applications'} },
  //           { text: 'News', to: { name: 'apps-news-admin'} },
  //           { text: 'Create', active: true }
  //         ],
  //         dlayout: 'vertical',
  //       },
  //     },
  //     { 
  //       path: 'apps/news/admin/edit/:id',
  //       component: () => import('@/component/apps/news/admin/NewsForm.vue'),
  //       name: 'apps-news-edit',
  //       meta: {
  //         pageTitle: 'News',
  //         breadcrumb: [
  //           { text: 'Application', to: { name: 'applications'} },
  //           { text: 'News', to: { name: 'apps-news-admin'} },
  //           { text: 'Edit', active: true }
  //         ],
  //         dlayout: 'vertical',
  //       },
  //       props: { default: true },
  //     },
  //   ]
  }
]