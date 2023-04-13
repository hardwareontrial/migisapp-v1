<template>
  <div>
    <transition :name="transition">
      <router-view
        :userdata="userdata">
      </router-view>
    </transition>
  </div>
</template>

<script>

import store from "@/store";

export default {
  computed: {
    transition(){
      return store.state.appConfig.layout.routerTransition
    },
    userdata(){
      let data = JSON.parse(localStorage.getItem('userdata'))
      return data
    }
  }
}
</script>

<style>

</style>