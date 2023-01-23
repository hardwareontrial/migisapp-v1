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
  computed: {
    transition(){
      return store.state.appConfig.layout.routerTransition
    }
  },
}
</script>

<style>

</style>