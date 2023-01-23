<template>
  <div>
    
    <transition :name="transition">
      <router-view
        :level-options="levelOptions"></router-view>
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
  props: {
    levelOptions: {
      type: Array,
    }
  }
}
</script>

<style>

</style>