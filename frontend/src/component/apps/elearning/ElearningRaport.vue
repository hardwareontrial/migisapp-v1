<template>
  <router-view></router-view>
</template>

<script>
export default {
  beforeCreate(){
    this.$store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'horizontal')
  },
}
</script>

<style>

</style>
