<template>
  <!-- Error page-->
  <div class="misc-wrapper">
    <b-link class="brand-logo">
      <!-- <vuexy-logo /> -->
      <object type="image/svg+xml" :data="brandUrl" />
      <h2 class="brand-text text-primary ml-1">
        <!-- Vuexy -->
      </h2>
    </b-link>

    <div class="misc-inner p-2 p-sm-3">
      <div class="w-100 text-center">
        <h2 class="mb-1">
          404 😖
        </h2>
        <p class="mb-2">
          Alamat tidak ditemukan.
        </p>

        <b-button
          variant="primary"
          class="mb-2 btn-sm-block"
          :to="{path:'/'}"
        >
          Kembali ke Beranda
        </b-button>

        <!-- image -->
        <b-img
          fluid
          :src="imgUrl"
          alt="Error page"
        />
      </div>
    </div>
  </div>
<!-- / Error page-->
</template>

<script>
/* eslint-disable global-require */
import { BLink, BButton, BImg } from 'bootstrap-vue'
import VuexyLogo from '@core/layouts/components/Logo.vue'
import store from '@/store/index'

export default {
  components: {
    VuexyLogo,
    BLink,
    BButton,
    BImg,
  },
  data() {
    return {
      downImg: require('@/assets/images/pages/error.svg'),
      brandLogo: require('@/assets/images/logo/mig/logo.svg')
    }
  },
  computed: {
    imgUrl() {
      if (this.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.downImg = require('@/assets/images/pages/error-dark.svg')
        return this.downImg
      }
      return this.downImg
    },
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
