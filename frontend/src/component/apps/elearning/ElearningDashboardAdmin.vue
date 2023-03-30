<template>
  <div>
    Dashboard Admin
  </div>
</template>

<script>
  export default{
    beforeCreate() {
      this.$store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'vertical')
    }
  }
</script>

<style></style>