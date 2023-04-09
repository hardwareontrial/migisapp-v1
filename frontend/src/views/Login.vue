<template>
  <div class="auth-wrapper auth-v2">
    <b-row class="auth-inner m-0">
      <b-link class="brand-logo">
        <object type="image/svg+xml" :data="brandUrl" />
        <h2 class="brand-text text-primary ml-1">
        </h2>
      </b-link>
      <b-col
        lg="8"
        class="d-none d-lg-flex align-items-center p-5">
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
          <b-img
            fluid
            :src="imgUrl"
            alt="Login V2"
          />
        </div>
      </b-col>
      <b-col
        lg="4"
        class="d-flex align-items-center auth-bg px-2 p-lg-5">
        <b-col
          sm="8"
          md="6"
          lg="12"
          class="px-xl-2 mx-auto">
          <b-card-title
            title-tag="h2"
            class="font-weight-bold mb-1">
            Selamat Datang 👋
          </b-card-title>
          <b-card-text class="mb-2">
            Silahkan masuk dengan menggunakan NIK/Email Anda.
          </b-card-text>
          
          <b-alert
            :show="dismissCountDown"
            class="mb-1"
            variant="danger"
            @dismissed="dismissCountDown = 0"
            @dismiss-count-down="countDownChanged">
            <div class="alert-body">
              <span><strong>({{ errors.code }}) </strong>{{ errors.message }}</span>
            </div>
          </b-alert>

          <b-form-group
            label="Username"
            label-for="username">
              <b-form-input
                id="username"
                v-model="form.username"
                name="username"
                placeholder="email/nik"
              />
          </b-form-group>
          <b-form-group>
            <div class="d-flex justify-content-between">
              <label for="login-password">Password</label>
            </div>
              <b-input-group
                class="input-group-merge">
                <b-form-input
                  id="login-password"
                  v-model="form.password"
                  class="form-control-merge"
                  :type="passwordFieldType"
                  name="login-password"
                  placeholder="············"
                />
                <b-input-group-append is-text>
                  <feather-icon
                    class="cursor-pointer"
                    :icon="passwordToggleIcon"
                    @click="togglePasswordVisibility"
                  />
                </b-input-group-append>
              </b-input-group>
          </b-form-group>
          <b-button
            @click="signin()"
            type="submit"
            class="mt-3"
            variant="primary"
            :disabled="processing"
            block>
            <b-spinner
              v-if="processing"
              small
              class="mr-25"/>
            {{ processing ? 'Proses':'Masuk' }}
          </b-button>
        </b-col>
      </b-col>
    </b-row>
  </div>
</template>

<script>
/* eslint-disable global-require */
import {
  BRow, BCol, BLink, BFormGroup, BFormInput, BInputGroupAppend, BInputGroup, BFormCheckbox, BCardText, BCardTitle, BImg, BForm, BButton,
  BAlert, BSpinner,
} from 'bootstrap-vue'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import store from '@/store/index'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import http from '@/customs/axios'

export default {
  components: {
    BRow, BCol,
    BForm, BFormGroup, BFormInput, BInputGroupAppend, BInputGroup, BFormCheckbox,
    BCardText, BCardTitle,
    BLink, BImg, BButton, BAlert, BSpinner,
  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      sideImg: require('@/assets/images/pages/login-v2.svg'),
      brandLogo: require('@/assets/images/logo/mig/logo.svg'),
      form: {
        username: '',
        password: ''
      },
      processing: false,
      errors: {
        show: false ,
        message: '',
        code: '',
      },
      dismissSecs: 10,
      dismissCountDown: 0
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    imgUrl() {
      if (store.state.appConfig.layout.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.sideImg = require('@/assets/images/pages/login-v2-dark.svg')
        return this.sideImg
      }
      return this.sideImg
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
  methods: {
    signin(){
      this.errors.message = '';
      this.errors.code = '';
      this.processing = true;
      http.post('user/auth/login', this.form)
      .then((res) => {
        localStorage.setItem('userdata', JSON.stringify(res.data.userdata))
        localStorage.setItem('token', JSON.stringify(res.data.token))
        let datauser = JSON.parse(localStorage.getItem('userdata')) 
        this.$ability.update(datauser.ability)
        this.$router.replace(this.$route.query.redirect || { name: 'new-dashboard' }).then(()=>{
          this.$toast({
            component: ToastificationContent,
            props: { text: res.data.message, icon: 'CoffeeIcon', variant: 'success', title: `Selamat Datang ${datauser.detailuser.name}`},
          },{
            position: 'top-right'
          })
        })
        this.processing = false
      })
      .catch((e) => {
        this.processing = false
        this.showAlert()
        this.errors.message = e.response === undefined ? 'Internal Server Error':e.response.data.message;
        this.errors.code = e.response === undefined ? '500':e.response.status;
      });
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert() {
      this.dismissCountDown = this.dismissSecs
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
  destroyed(){
    //
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/pages/page-auth.scss';
</style>

<style lang="scss" scoped>
  object {
    height: 50px
  }
</style>
