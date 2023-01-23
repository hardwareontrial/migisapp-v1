<template>
  <div>
    
    <transition :name="transition">
      <router-view></router-view>
    </transition>

  </div>
</template>

<script>
import store from '@/store'

export default {
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