<template>
  <div>
    
    <transition :name="transition">
      <router-view></router-view>
    </transition>

    <ModalInvoice />
    <ModalPrint />

  </div>
</template>

<script>
import store from '@/store'

export default {
  components: {
    ModalInvoice: () => import('@/component/apps/suratjalan/SuratJalanInvoice.vue'),
    ModalPrint: () => import('@/component/apps/suratjalan/SuratJalanPrint.vue'),
  },
  computed: {
    transition(){
      return store.state.appConfig.layout.routerTransition
    }
  },
  beforeCreate() {
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'vertical')
  },
}
</script>

<style>

</style>