<template>
  <div class="navbar-header d-xl-block d-none">
    <ul class="nav navbar-nav">
      <li class="nav-item">
        <b-link
          class="navbar-brand"
          to="/"
        >
          <!-- <span class="brand-logo">
            <b-img
              :src="appLogoImage"
              alt="logo"
            />
          </span> -->

          <object type="image/svg+xml" :data="brandUrl" />
          
          <!-- <h2 class="brand-text mb-0">
            {{ appName }}
          </h2> -->
        </b-link>
      </li>
    </ul>
  </div>
</template>

<script>
import { BLink, BImg, } from 'bootstrap-vue'
import { $themeConfig } from '@themeConfig'
import store from '@/store'

export default {
  components: {
    BLink,
    BImg,
  },
  data(){
    return{
      brandLogo: require('@/assets/images/logo/mig/logo.svg')
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
  },
  watch: {
    'skin': {
      handler(newValue){
        if(newValue === 'dark') return this.brandLogo = require('@/assets/images/logo/mig/logo-dark.svg')
        if(newValue === 'light') return this.brandLogo = require('@/assets/images/logo/mig/logo.svg')
      }
    }
  }
  // setup() {
  //   // App Name
  //   const { appName, appLogoImage } = $themeConfig.app
  //   return {
  //     appName,
  //     appLogoImage,
  //   }
  // },
}
</script>

<style>

</style>

<style lang="scss" scoped>
  object {
    height: 40px
  }
</style>
