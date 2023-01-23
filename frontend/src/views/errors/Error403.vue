<template>
  <div class="misc-wrapper">
    <b-link class="brand-logo">
      <!-- <vuexy-logo /> -->
      <object type="image/svg+xml" :data="brandUrl" />
      <h2 class="brand-text text-primary ml-1">
      </h2>
    </b-link>

    <div class="misc-inner p-2 p-sm-3">
      <div class="w-100 text-center">
        <h2 class="mb-1">
          Otorisasi diperlukan! 🔐
        </h2>
        <p class="mb-2">
          Anda tidak memiliki izin akses halaman ini.
        </p>
        <b-button
          variant="primary"
          class="mb-1 btn-sm-block"
          :to="{ name: 'home' }"
        >Kembali ke Beranda</b-button>
        <b-img
          fluid
          :src="imgUrl"
          alt="Not authorized page"
        />
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable global-require */
import { BLink, BImg, BButton } from 'bootstrap-vue'
import VuexyLogo from '@core/layouts/components/Logo.vue'
import store from '@/store/index'

export default {
  components: {
    BLink, BImg, BButton, VuexyLogo,
  },
  data() {
    return {
      downImg: require('@/assets/images/pages/not-authorized.svg'),
      brandLogo: require('@/assets/images/logo/mig/logo.svg'),
    }
  },
  computed: {
    skin(){
      return store.state.appConfig.layout.skin
    },
    brandUrl() {
      if (this.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.brandLogo = require('@/assets/images/logo/mig/logo-dark.svg')
        return this.brandLogo
      }
      return this.brandLogo
    },
    imgUrl() {
      if (this.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.downImg = require('@/assets/images/pages/not-authorized-dark.svg')
        return this.downImg
      }
      return this.downImg
    },
  },
  watch: {
    'skin': {
      handler(newValue){
        if(newValue === 'dark') return this.brandLogo = require('@/assets/images/logo/mig/logo-dark.svg')
        if(newValue === 'light') return this.brandLogo = require('@/assets/images/logo/mig/logo.svg')
      }
    }
  }
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