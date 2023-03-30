export default [
  {
    path: 'elearning',
    name: 'apps-elearning',
    component: () => import('@/views/apps/elearning/ElearningView.vue'),
    children: [
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
      {
        path: 'dashboard-admin',
        name: 'apps-elearning-dashboard-admin',
        component: () => import('@/component/apps/elearning/ElearningDashboardAdmin.vue'),
        meta: {
          pageTitle: 'Dashboard',
          breadcrumb: [
            { text: 'OKM', active: true },
          ],
          resource: 'AppOKMAdmin',
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
          resource: 'AppOKMRaport',
          action: 'all',
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
            { text: 'Raport Detail', active: true },
          ],
          resource: 'AppOKMRaport',
          action: 'single',
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard-admin'} },
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard-admin'} },
            { text: 'Materi', to: { name: 'apps-elearning-materials'} },
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard-admin'} },
            { text: 'Materi', to: { name: 'apps-elearning-materials'} },
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard-admin'} },
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard-admin'} },
            { text: 'Daftar Soal', to: { name: 'apps-elearning-questions'} },
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
          action: 'read',
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
            { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
            { text: 'Jadwal Ujian', to: { name: 'apps-elearning-schedule'} },
            { text: 'Form Jadwal Ujian', active: true },
          ],
          resource: 'AppOKMSchedule',
          action: 'create',
        },
      },
      {
        path: 'schedule/detail/:id',
        name: 'apps-elearning-schedule-detail',
        component: () => import('@/component/apps/elearning/ElearningScheduleDetail.vue'),
        meta: {
          pageTitle: 'Detail Ujian',
          title: 'OKM',
          resource: 'AppOKMSchedule',
          action: 'read',
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