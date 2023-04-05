import Vue from 'vue'
import VueRouter from 'vue-router'

import apps from './apps'
import errors from './errors'

// import { canNavigate } from '@/libs/acl/routeProtection'
// import { isUserLoggedIn, getHomeRouteForLoggedInUser } from '@/auth/utils'

import { canNavigate } from '@/customs/acl/routeProtection'
import { isUserLoggedIn } from '@/customs/acl/utils'
import store from '@/store'
import misc from './misc'
import dashboard from './dashboard'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/Home.vue'),
      meta: {
        type: 'horizontal',
        pageTitle: 'Home',
        breadcrumb: [
          {
            text: 'Home',
            active: true,
          },
        ],
        resource: 'Auth',
        action: 'read',
      },
      redirect: { name: 'login' },
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/Login.vue'),
      meta: {
        layout: 'full',
        resource: 'Auth',
        redirectIfLoggedIn: true,
      },
    },
    ...apps,
    ...errors,
    ...misc,
    ...dashboard,
    {
      path: '*',
      redirect: 'error-404',
      meta: {
        resource: 'Auth',
        // action: 'read',
      },
    },
  ],
})

router.beforeEach((to, _, next) => {
  const isLoggedIn = isUserLoggedIn()
  if (!canNavigate(to)) {
    if (!isLoggedIn) return next({ name: 'login' })
    return next({ name: 'not-authorize' })
  }
  // Redirect if logged in
  if (to.meta.redirectIfLoggedIn && isLoggedIn) {
    next({ name: 'new-dashboard' })
  }

  let documentTitle = `${ process.env.VUE_APP_TITLE }`
  if(to.meta.title){
    documentTitle += ` | ${ to.meta.title }`
  }
  document.title = documentTitle
  return next()
})

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'none'
  }
})

export default router
