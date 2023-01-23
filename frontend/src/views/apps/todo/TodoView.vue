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
    // this.$store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'horizontal')
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