export default [
  {
    path: 'todo',
    name: 'apps-todo',
    component: () => import('@/component/apps/todo/TodoList.vue'),
    meta: {
      title: 'ToDo',
      contentRenderer: 'sidebar-left',
      contentClass: 'todo-application',
      resource: 'AppTodo',
      action: 'read',
    },
  },
  {
    path: 'todo/:filter',
    name: 'apps-todo-filter',
    component: () => import('@/component/apps/todo/TodoList.vue'),
    meta: {
      title: 'ToDo',
      contentRenderer: 'sidebar-left',
      contentClass: 'todo-application',
      navActiveLink: 'apps-todo',
      resource: 'AppTodo',
      action: 'read',
    },
    beforeEnter(to, _, next) {
      if (['important', 'completed', 'deleted'].includes(to.params.filter)) next()
      else next({ name: 'error-404' })
    },
  },
  {
    path: 'todo/tag/:tag',
    name: 'apps-todo-tag',
    component: () => import('@/component/apps/todo/TodoList.vue'),
    meta: {
      title: 'ToDo',
      contentRenderer: 'sidebar-left',
      contentClass: 'todo-application',
      navActiveLink: 'apps-todo',
      resource: 'AppTodo',
      action: 'read',
    },
    beforeEnter(to, _, next) {
      if (['low', 'medium', 'high', 'update'].includes(to.params.tag)) next()
      else next({ name: 'error-404' })
    },
  },
]