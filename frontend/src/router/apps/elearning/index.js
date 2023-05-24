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
          resource: 'AppOKM',
          action: 'read',
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
          resource: 'AppOKMMaterial',
          action: 'read',
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
          resource: 'AppOKMMaterial',
          action: 'create',
        },
      },
      {
        path: 'materials/show/:id/:slug',
        name: 'apps-elearning-materials-detail',
        component: () => import('@/component/apps/elearning/ElearningMaterialDetail.vue'),
        meta: {
          pageTitle: 'Detail Materi',
          title: 'OKM',
          resource: 'AppOKMMaterial',
          action: 'read',
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
          resource: 'AppOKMMaterial',
          action: 'update',
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
          resource: 'AppOKMQuestion',
          action: 'read',
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
          resource: 'AppOKMQuestion',
          action: 'create',
        },
      },
      {
        path: 'questions/show/:id/:slug',
        name: 'apps-elearning-questions-detail',
        component: () => import('@/component/apps/elearning/ElearningQuestionDetail.vue'),
        meta: {
          pageTitle: 'Detail Soal',
          title: 'OKM',
          resource: 'AppOKMQuestion',
          action: 'update',
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
          resource: 'AppOKMSchedule',
          action: 'read',
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
          resource: 'AppOKMSchedule',
          action: 'create',
        },
      },
      {
        path: 'schedule/detail/:id',
        name: 'apps-elearning-schedule-detail',
        component: () => import('@/component/apps/elearning/old_ElearningScheduleDetail.vue'),
        meta: {
          pageTitle: 'Detail Ujian',
          title: 'OKM',
          resource: 'AppOKMSchedule',
          action: 'update',
        },
      },
    ]
  },
]