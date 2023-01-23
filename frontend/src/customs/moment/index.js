import Vue from 'vue'
import moment from 'moment'
import 'moment/locale/id'

moment.locale('id')
Vue.prototype.$moment = moment