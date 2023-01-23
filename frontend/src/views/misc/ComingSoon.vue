<template>
  <div class="misc-wrapper">
    <b-link class="brand-logo">
      <object type="image/svg+xml" :data="brandimg" />
    </b-link>

    <div class="misc-inner p-2 p-sm-3">
      <div class="w-100 text-center">
        <h2 class="mb-1">
          Kita akan segera launching 🚀
        </h2>
        <p class="mb-3">
          Kita sedang memperbarui tampilan dan peningkatan performa web apps. Dan akan segera siap!
        </p>

        <b-img
          fluid
          :src="imgUrl"
          alt="Coming soon page"
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
      downImg: require('@/assets/images/pages/coming-soon.svg'),
      brandLogo: require('@/assets/images/logo/mig/logo.svg'),
    }
  },
  computed: {
    imgUrl() {
      if (store.state.appConfig.layout.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.downImg = require('@/assets/images/pages/coming-soon-dark.svg')
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