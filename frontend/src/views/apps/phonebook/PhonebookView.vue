<template>
  <div>
    
    <transition :name="transition">
      <router-view></router-view>
    </transition>

    <PhonebookAdd />
    <PhonebookDetail />
  </div>
</template>

<script>
import store from '@/store'
import PhonebookAdd from '@/component/apps/phonebook/PhonebookAdd.vue'

export default {
  components: {
    // PhonebookAdd: () => import('@/component/apps/phonebook/PhonebookAdd.vue'),
    PhonebookDetail: () => import('@/component/apps/phonebook/PhonebookDetail.vue'),
    PhonebookAdd,
  },
  beforeCreate() {
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'vertical')
  },
  computed: {
    transition(){
      return store.state.appConfig.layout.routerTransition
    }
  },
}
</script>

<style>

</style>