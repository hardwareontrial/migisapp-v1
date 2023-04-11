import Vue from 'vue'
import { ToastPlugin, ModalPlugin } from 'bootstrap-vue'
import VueCompositionAPI from '@vue/composition-api'

import router from './router'
import store from './store'
import App from './App.vue'

// Global Components
import './global-components'

// 3rd party plugins
import '@/libs/portal-vue'
import '@/libs/toastification'
import '@/libs/sweet-alerts'
import '@/libs/vue-select'

import '@/customs/acl'
import '@/customs/moment'
import { BootstrapVueIcons } from 'bootstrap-vue'

// Laravel Websockets
// import Echo from 'laravel-echo';
// window.Pusher = require('pusher-js');
// const token = localStorage.getItem('token');
// window.Echo = new Echo({
//   broadcaster: 'pusher',
//   key: process.env.VUE_APP_WEBSOCKETS_KEY,
//   wsHost: process.env.VUE_APP_WEBSOCKETS_SERVER,
//   wsPort: 6001,
//   cluster: 'mt1',
//   forceTLS: false,
//   disableStats: true,
//   authEndpoint: process.env.VUE_APP_API_ENDPOINT+'/api/broadcasting/auth',
//   auth: {
//     headers: {
//       Authorization: 'Bearer ' +token,
//     }
//   },
// })

Vue.use(BootstrapVueIcons)
// BSV Plugin Registration
Vue.use(ToastPlugin)
Vue.use(ModalPlugin)

// Composition API
Vue.use(VueCompositionAPI)

require('@core/assets/fonts/feather/iconfont.css') // For form-wizard

// import core styles
require('@core/scss/core.scss')

// import assets styles
require('@/assets/scss/style.scss')

let called = new Vue()
window.called = called


Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app')
