export default [
  /** ----------------------------- Raport Area ------------------------------------ */
  {
    path: 'raport',
    name: 'apps-elearning-raport',
    component: () => import('@/component/apps/elearning/ElearningRaport.vue'),
    children: [
      {
        path: 'all',
        name: 'apps-elearning-raport-all',
        component: () => import('@/component/apps/elearning/_RaportAll.vue'),
        meta: {
          pageTitle: 'Raport',
          title: 'OKM',
          breadcrumb: [
            { text: 'Semua Raport', active: true },
          ],
          resource: '',
          action: '',
        },
      },
      {
        path: ':id/:nik',
        name: 'apps-elearning-raport-nik',
        component: () => import('@/component/apps/elearning/_RaportUser.vue'),
        meta: {
          pageTitle: 'Raport',
          title: 'OKM',
          breadcrumb: [
            { text: 'Raport', active: true },
          ],
          resource: 'ACL',
          action: 'read',
        },
      }
    ],
  },
  /** ----------------------------- Quiz Area ------------------------------------ */
  {
    path: 'quiz/:id/:slug',
    name: 'apps-elearning-quiz',
    component: () => import('@/component/apps/elearning/ElearningQuiz.vue'),
    meta: {
      pageTitle: 'Quiz',
      title: 'OKM',
      resource: 'ACL',
      action: 'read',
    },
  },
  {
    path: 'elearning',
    name: 'apps-elearning',
    component: () => import('@/views/apps/elearning/ElearningView.vue'),
    children: [
      /** ----------------------------- Material Area ------------------------------------ */
      {
        path: 'materials',
        name: 'apps-elearning-materials',
        component: () => import('@/component/apps/elearning/ElearningMaterials.vue'),
        meta: {
          pageTitle: 'Materi',
          title: 'OKM',
          breadcrumb: [
            { text: 'OKM', to: { name: 'apps-elearning-materials'} },
            { text: 'Materi', active: true },
          ],
          resource: '',
          action: '',
        },
      },
      {
        path: 'materials/add',
        name: 'apps-elearning-materials-create',
        component: () => import('@/component/apps/elearning/ElearningMaterialsForm.vue'),
        meta: {
          pageTitle: 'Tambah Materi',
          title: 'OKM',
          breadcrumb: [
            { text: 'OKM', to: { name: 'apps-elearning-materials'} },
            { text: 'Tambah Materi', active: true },
          ],
          resource: '',
          action: '',
        },
      },
      {
        path: 'materials/show/:id/:slug',
        name: 'apps-elearning-materials-detail',
        component: () => import('@/component/apps/elearning/ElearningMaterialDetail.vue'),
        meta: {
          pageTitle: 'Detail Materi',
          title: 'OKM',
          resource: '',
          action: '',
        },
      },
      {
        path: 'materials/edit/:id',
        name: 'apps-elearning-materials-edit',
        component: () => import('@/component/apps/elearning/ElearningMaterialsForm.vue'),
        meta: {
          pageTitle: 'Edit Materi',
          title: 'OKM',
          breadcrumb: [
            { text: 'OKM', to: { name: 'apps-elearning-materials'} },
            { text: 'Edit Materi', active: true },
          ],
          resource: '',
          action: '',
        },
      },
      /** ----------------------------- Question Area ------------------------------------ */
      {
        path: 'questions',
        name: 'apps-elearning-questions',
        component: () => import('@/component/apps/elearning/ElearningQuestions.vue'),
        meta: {
          pageTitle: 'Daftar Soal',
          title: 'OKM',
          breadcrumb: [
            { text: 'OKM', to: { name: 'apps-elearning-questions'} },
            { text: 'Soal', active: true },
          ],
          resource: '',
          action: '',
        },
      },
      {
        path: 'questions/add',
        name: 'apps-elearning-questions-create',
        component: () => import('@/component/apps/elearning/ElearningQuestionsForm.vue'),
        meta: {
          pageTitle: 'Tambah Soal',
          title: 'OKM',
          breadcrumb: [
            { text: 'OKM', to: { name: 'apps-elearning-questions'} },
            { text: 'Tambah Soal', active: true },
          ],
          resource: '',
          action: '',
        },
      },
      {
        path: 'questions/show/:id/:slug',
        name: 'apps-elearning-questions-detail',
        component: () => import('@/component/apps/elearning/ElearningQuestionDetail.vue'),
        meta: {
          pageTitle: 'Detail Soal',
          title: 'OKM',
          resource: '',
          action: '',
        },
      },
      /** ----------------------------- Schedule Area ------------------------------------ */
      {
        path: 'schedule',
        name: 'apps-elearning-schedule',
        component: () => import('@/component/apps/elearning/ElearningSchedules.vue'),
        meta: {
          pageTitle: 'Jadwal Ujian',
          title: 'OKM',
          breadcrumb: [
            { text: 'OKM', to: { name: 'apps-elearning-schedule'} },
            { text: 'Jadwal Ujian', active: true },
          ],
          resource: '',
          action: '',
        },
      },
      {
        path: 'schedule/add',
        name: 'apps-elearning-schedule-create',
        component: () => import('@/component/apps/elearning/ElearningScheduleForm.vue'),
        meta: {
          pageTitle: 'Form Ujian',
          title: 'OKM',
          breadcrumb: [
            { text: 'OKM', to: { name: 'apps-elearning-schedule'} },
            { text: 'Form Jadwal Ujian', active: true },
          ],
          resource: '',
          action: '',
        },
      },
      {
        path: 'schedule/detail/:id',
        name: 'apps-elearning-schedule-detail',
        component: () => import('@/component/apps/elearning/ElearningScheduleDetail.vue'),
        meta: {
          pageTitle: 'Detail Ujian',
          title: 'OKM',
          resource: '',
          action: '',
        },
      },
    ]
  },
  // {
  //   path: 'elearning',
  //   name: 'apps-elearning',
  //   component: () => import('@/views/apps/elearning/ElearningView.vue'),
  //   children: [
  //     /** ----------------------------- Dashboard Area ------------------------------------ */
  //     // {
  //     //   path: '',
  //     //   name: '',
  //     //   component: () => import('@/component/apps/elearning/ElearningDashboard.vue'),
  //     //   meta: {
  //     //     pageTitle: 'Dashboard',
  //     //     breadcrumb: [
  //     //       { text: 'OKM', active: true },
  //     //     ],
  //     //     resource: '',
  //     //     action: '',
  //     //   },
  //     // },
  //     // {
  //     //   path: '',
  //     //   name: '',
  //     //   component: () => import('@/component/apps/elearning/ElearningDashboardAdmin.vue'),
  //     //   meta: {
  //     //     pageTitle: 'Dashboard',
  //     //     breadcrumb: [
  //     //       { text: 'OKM', active: true },
  //     //     ],
  //     //     resource: '',
  //     //     action: '',
  //     //   },
  //     // },
  //     /** ----------------------------- Raport Area ------------------------------------ */
      
  //     // {
  //     //   path: 'raport/:id/detail/:nik',
  //     //   name: 'apps-elearning-raport-detail',
  //     //   component: () => import('@/component/apps/elearning/ElearningDetailRaport.vue'),
  //     //   meta: {
  //     //     pageTitle: 'Raport',
  //     //     title: 'OKM',
  //     //     breadcrumb: [
  //     //       { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
  //     //       { text: 'Raport Detail', active: true },
  //     //     ],
  //     //     resource: '',
  //     //     action: '',
  //     //   },
  //     // },
  //     /** ----------------------------- Material Area ------------------------------------ */
  //     {
  //       path: 'materials',
  //       name: 'apps-elearning-materials',
  //       component: () => import('@/component/apps/elearning/ElearningMaterials.vue'),
  //       meta: {
  //         pageTitle: 'Materi',
  //         title: 'OKM',
  //         breadcrumb: [
  //           { text: 'OKM', to: { name: 'apps-elearning-dashboard-admin'} },
  //           { text: 'Materi', active: true },
  //         ],
  //         resource: '',
  //         action: '',
  //       },
  //     },
  //     {
  //       path: 'materials/add',
  //       name: 'apps-elearning-materials-create',
  //       component: () => import('@/component/apps/elearning/ElearningMaterialsForm.vue'),
  //       meta: {
  //         pageTitle: 'Tambah Materi',
  //         title: 'OKM',
  //         breadcrumb: [
  //           { text: 'OKM', to: { name: 'apps-elearning-dashboard-admin'} },
  //           { text: 'Materi', to: { name: 'apps-elearning-materials'} },
  //           { text: 'Tambah Materi', active: true },
  //         ],
  //         resource: '',
  //         action: '',
  //       },
  //     },
  //     {
  //       path: 'materials/show/:id/:slug',
  //       name: 'apps-elearning-materials-detail',
  //       component: () => import('@/component/apps/elearning/ElearningMaterialDetail.vue'),
  //       meta: {
  //         pageTitle: 'Detail Materi',
  //         title: 'OKM',
  //         resource: 'AppOKMMaterial',
  //         action: '',
  //       },
  //     },
  //     {
  //       path: 'materials/edit/:id',
  //       name: 'apps-elearning-materials-edit',
  //       component: () => import('@/component/apps/elearning/ElearningMaterialsForm.vue'),
  //       meta: {
  //         pageTitle: 'Edit Materi',
  //         title: 'OKM',
  //         breadcrumb: [
  //           { text: 'OKM', to: { name: 'apps-elearning-dashboard-admin'} },
  //           { text: 'Materi', to: { name: 'apps-elearning-materials'} },
  //           { text: 'Edit Materi', active: true },
  //         ],
  //         resource: '',
  //         action: '',
  //       },
  //     },
  //     /** ----------------------------- Question Area ------------------------------------ */
  //     {
  //       path: 'questions',
  //       name: 'apps-elearning-questions',
  //       component: () => import('@/component/apps/elearning/ElearningQuestions.vue'),
  //       meta: {
  //         pageTitle: 'Daftar Soal',
  //         title: 'OKM',
  //         breadcrumb: [
  //           { text: 'OKM', to: { name: 'apps-elearning-dashboard-admin'} },
  //           { text: 'Soal', active: true },
  //         ],
  //         resource: '',
  //         action: '',
  //       },
  //     },
  //     {
  //       path: 'questions/add',
  //       name: 'apps-elearning-questions-create',
  //       component: () => import('@/component/apps/elearning/ElearningQuestionsForm.vue'),
  //       meta: {
  //         pageTitle: 'Tambah Soal',
  //         title: 'OKM',
  //         breadcrumb: [
  //           { text: 'OKM', to: { name: 'apps-elearning-dashboard-admin'} },
  //           { text: 'Daftar Soal', to: { name: 'apps-elearning-questions'} },
  //           { text: 'Tambah Soal', active: true },
  //         ],
  //         resource: '',
  //         action: '',
  //       },
  //     },
  //     {
  //       path: 'questions/show/:id/:slug',
  //       name: 'apps-elearning-questions-detail',
  //       component: () => import('@/component/apps/elearning/ElearningQuestionDetail.vue'),
  //       meta: {
  //         pageTitle: 'Detail Soal',
  //         title: 'OKM',
  //         resource: '',
  //         action: '',
  //       },
  //     },
  //     /** ----------------------------- Schedule Area ------------------------------------ */
  //     {
  //       path: 'schedule',
  //       name: 'apps-elearning-schedule',
  //       component: () => import('@/component/apps/elearning/ElearningSchedules.vue'),
  //       meta: {
  //         pageTitle: 'Jadwal Ujian',
  //         title: 'OKM',
  //         breadcrumb: [
  //           { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
  //           { text: 'Jadwal Ujian', active: true },
  //         ],
  //         resource: '',
  //         action: '',
  //       },
  //     },
  //     {
  //       path: 'schedule/add',
  //       name: 'apps-elearning-schedule-create',
  //       component: () => import('@/component/apps/elearning/ElearningScheduleForm.vue'),
  //       meta: {
  //         pageTitle: 'Form Ujian',
  //         title: 'OKM',
  //         breadcrumb: [
  //           { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
  //           { text: 'Jadwal Ujian', to: { name: 'apps-elearning-schedule'} },
  //           { text: 'Form Jadwal Ujian', active: true },
  //         ],
  //         resource: '',
  //         action: '',
  //       },
  //     },
  //     {
  //       path: 'schedule/detail/:id',
  //       name: 'apps-elearning-schedule-detail',
  //       component: () => import('@/component/apps/elearning/ElearningScheduleDetail.vue'),
  //       meta: {
  //         pageTitle: 'Detail Ujian',
  //         title: 'OKM',
  //         resource: '',
  //         action: '',
  //       },
  //     },
  //     /** ----------------------------- Quiz Area ------------------------------------ */
  //     {
  //       path: 'quiz/:id/:slug',
  //       name: 'apps-elearning-quiz',
  //       component: () => import('@/component/apps/elearning/ElearningQuiz.vue'),
  //       meta: {
  //         pageTitle: 'Quiz',
  //         title: 'OKM',
  //         resource: '',
  //         action: '',
  //       },
  //     },
  //   ]
  // }
]