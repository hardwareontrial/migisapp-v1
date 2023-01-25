export default [
  {
    path: 'elearning',
    name: 'apps-elearning',
    component: () => import('@/views/apps/elearning/ElearningView.vue'),
    children: [
      /** ----------------------------- Test Page Area ------------------------------------ */
      {
        path: 'testpage',
        name: 'apps-elearning-testpage',
        component: () => import('@/component/apps/elearning/ElearningTestPage.vue'),
        meta: {
          pageTitle: 'Tespage',
          breadcrumb: [
            { text: 'OKM', active: true },
          ],
        },
      },
      /** ----------------------------- Dashboard Area ------------------------------------ */
      {
        path: 'dashboard',
        name: 'apps-elearning-dashboard',
        component: () => import('@/component/apps/elearning/ElearningDashboard.vue'),
        meta: {
          pageTitle: 'Dashboard',
          breadcrumb: [
            { text: 'OKM', active: true },
          ],
          resource: 'AppOKM',
          action: 'read',
        },
      },
      /** ----------------------------- Raport Area ------------------------------------ */
      {
        path: 'raport',
        name: 'apps-elearning-raport',
        component: () => import('@/component/apps/elearning/ElearningRaport.vue'),
        meta: {
          pageTitle: 'Raport',
          title: 'OKM',
          breadcrumb: [
            { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
            { text: 'Raport', active: true },
          ],
          resource: 'AppOKM',
          action: 'read',
        },
      },
      {
        path: 'raport/:id/detail/:nik',
        name: 'apps-elearning-raport-detail',
        component: () => import('@/component/apps/elearning/ElearningDetailRaport.vue'),
        meta: {
          pageTitle: 'Raport',
          title: 'OKM',
          breadcrumb: [
            { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
            { text: 'Raport', to: { name: 'apps-elearning-raport'} },
            { text: 'Raport Detail', active: true },
          ],
          resource: 'AppOKM',
          action: 'read',
        },
      },
      /** ----------------------------- Material Area ------------------------------------ */
      {
        path: 'materials',
        name: 'apps-elearning-materials',
        component: () => import('@/component/apps/elearning/ElearningMaterials.vue'),
        meta: {
          pageTitle: 'Materi',
          title: 'OKM',
          breadcrumb: [
            { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
            { text: 'Materi', active: true },
          ],
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
            { text: 'Materi', to: { name: 'apps-elearning-materials'} },
            { text: 'Tambah Materi', active: true },
          ],
        },
      },
      {
        path: 'materials/show/:id/:slug',
        name: 'apps-elearning-materials-detail',
        component: () => import('@/component/apps/elearning/ElearningMaterialDetail.vue'),
        meta: {
          pageTitle: 'Detail Materi',
          title: 'OKM',
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
            { text: 'Materi', to: { name: 'apps-elearning-materials'} },
            { text: 'Edit Materi', active: true },
          ],
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
            { text: 'Soal', active: true },
          ],
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
            { text: 'Daftar Soal', to: { name: 'apps-elearning-questions'} },
            { text: 'Tambah Soal', active: true },
          ],
        },
      },
      {
        path: 'questions/show/:id/:slug',
        name: 'apps-elearning-questions-detail',
        component: () => import('@/component/apps/elearning/ElearningQuestionDetail.vue'),
        meta: {
          pageTitle: 'Detail Soal',
          title: 'OKM',
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
            { text: 'Jadwal Ujian', active: true },
          ],
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
            { text: 'Jadwal Ujian', to: { name: 'apps-elearning-schedule'} },
            { text: 'Form Jadwal Ujian', active: true },
          ],
        },
      },
      {
        path: 'schedule/detail/:id',
        name: 'apps-elearning-schedule-detail',
        component: () => import('@/component/apps/elearning/ElearningScheduleDetail.vue'),
        meta: {
          pageTitle: 'Detail Ujian',
          title: 'OKM',
        },
      },
      /** ----------------------------- Quiz Area ------------------------------------ */
      {
        path: 'quiz/:id/:slug',
        name: 'apps-elearning-quiz',
        component: () => import('@/component/apps/elearning/ElearningQuiz.vue'),
        meta: {
          pageTitle: 'Quiz',
          title: 'OKM',
          resource: 'AppOKM',
          action: 'read',
        },
      },
    ]
  }
]