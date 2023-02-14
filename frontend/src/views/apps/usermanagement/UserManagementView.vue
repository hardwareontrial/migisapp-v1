<template>
  <div>

    <transition :name="transition">
      <router-view
        :userdata="userdata">
      </router-view>
    </transition>

    <user-add-new />
    <pop-up />
  </div>
</template>

<script>
import store from '@/store'
import PopUp from '@/component/utils/Loading.vue'

export default {
  components: {
    UserAddNew: () => import('@/component/apps/usermanagement/UserAdd.vue'),
    PopUp,
  },
  computed: {
    transition(){
      return store.state.appConfig.layout.routerTransition
    },
    userdata(){
      let data = JSON.parse(localStorage.getItem('userdata'))
      return data
    }
  },
  beforeCreate() {
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'vertical')
  },
}
</script>

<style>

</style>