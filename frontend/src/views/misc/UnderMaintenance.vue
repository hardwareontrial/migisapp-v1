<template>
  <div class="misc-wrapper">
    <b-link class="brand-logo">
      <object type="image/svg+xml" :data="brandimg" />
    </b-link>

    <div class="misc-inner p-2 p-sm-3">
      <div class="w-100 text-center">
        <h2 class="mb-1">
          Under Maintenance 🛠
        </h2>

        <p class="mb-3">
          Web apps untuk saat ini sedang dalam perbaikan, mohon maaf atas kendala yang terjadi.
        </p>

        <!-- img -->
        <b-img
          fluid
          :src="imgUrl"
          alt="Under maintenance page"
        />
      </div>
    </div>
  </div>
</template>

<script>
import {
  BLink, BImg,
} from 'bootstrap-vue'
import store from '@/store/index'

export default {
  components: {
    BLink,
    BImg,
  },
  data() {
    return {
      downImg: require('@/assets/images/pages/under-maintenance.svg'),
      brandLogo: require('@/assets/images/logo/mig/logo.svg'),
    }
  },
  computed: {
    imgUrl() {
      if (store.state.appConfig.layout.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.downImg = require('@/assets/images/pages/under-maintenance-dark.svg')
        return this.downImg
      }
      return this.downImg
    },
    brandimg() {
      if (this.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.brandLogo = require('@/assets/images/logo/mig/logo-dark.svg')
        return this.brandLogo
      }
      return this.brandLogo
    },
  },
  watch: {
    'skin': {
      handler(newValue){
        if(newValue === 'dark') return this.brandLogo = require('@/assets/images/logo/mig/logo-dark.svg')
        if(newValue === 'light') return this.brandLogo = require('@/assets/images/logo/mig/logo.svg')
      }
    }
  },
}
</script>

<style lang="scss">
  @import '@core/scss/vue/pages/page-misc.scss';
</style>

<style lang="scss" scoped>
  object {
    height: 50px
  }
</style>

<style lang="scss">
@import '@core/scss/vue/pages/page-misc.scss';
</style>