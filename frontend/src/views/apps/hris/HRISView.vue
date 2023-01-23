<template>
  <div>
    
    <transition :name="transition">
      <router-view></router-view>
    </transition>
    <pop-up />
  </div>
</template>

<script>
import store from '@/store'
import PopUp from '@/component/utils/Loading.vue'

export default {
  components: {
    PopUp
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