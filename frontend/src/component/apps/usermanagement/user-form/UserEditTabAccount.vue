<template>
  <div>
    <div>
      <div class="d-flex mt-0">
        <feather-icon
          icon="BriefcaseIcon"
          size="19" />
        <h4 class="mb-1 ml-50"> Login Info </h4>
      </div>
      <validation-observer
        ref="createaccount"
        #default="{invalid}">
        <b-row>
          <b-col cols="12" md="4">
            <b-form-group
              label="NIK"
              label-for="f-nik">
              <validation-provider
                #default="{errors}"
                name="nik"
                vid="nik"
                rules="required">
                <b-form-input
                  readonly
                  v-model="formlogin.nik"
                  :state="errors.length > 0 ? false:null"
                  name="f-nik"
                  id="f-nik" />
                  <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4">
            <b-form-group
              label="Email"
              label-for="f-email">
              <validation-provider
                #default="{errors}"
                name="email"
                vid="email"
                rules="required|email">
                <b-form-input
                  v-model="formlogin.email"
                  :state="errors.length > 0 ? false:null"
                  name="f-email"
                  id="f-email" />
                  <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4">
            <b-form-group
              label="Active"
              label-for="f-active-login">
              <validation-provider
                #default="{errors}"
                name="active-login"
                vid="active-login"
                rules="required">
                <v-select
                  :reduce="text=>text.value"
                  label="text"
                  v-model="formlogin.loginActive"
                  :clearable="false"
                  :options="optionsactive"
                  :state="errors.length > 0 ? false:null"
                  id="f-active-login"/>
                  <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4" v-if="!editmode">
            <b-form-group
              label="Password"
              label-for="f-password">
              <validation-provider
                #default="{errors}"
                name="password"
                vid="password"
                rules="required|password">
                <b-input-group
                  class="input-group-merge"
                  :class="errors.length > 0 ? 'is-invalid':null">
                  <b-form-input
                    id="login-password"
                    v-model="formlogin.new_password"
                    :state="errors.length > 0 ? false:null"
                    class="form-control-merge"
                    :type="passwordFieldType"
                    name="login-password"
                    placeholder="Password"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                      class="cursor-pointer"
                      :icon="passwordToggleIcon"
                      @click="togglePasswordVisibility"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4" v-if="!editmode">
            <b-form-group
              label="Confirm Password"
              label-for="f-cpassword">
              <validation-provider
                #default="{errors}"
                name="cpassword"
                vid="cpassword"
                rules="required|confirmed:password">
                <b-input-group
                  class="input-group-merge"
                  :class="errors.length > 0 ? 'is-invalid':null">
                  <b-form-input
                    v-model="formlogin.rep_password"
                    id="f-cpassword"
                    :state="errors.length > 0 ? false:null"
                    class="form-control-merge"
                    :type="passwordFieldType"
                    name="f-cpassword"
                    placeholder="Password"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                      class="cursor-pointer"
                      :icon="passwordToggleIcon"
                      @click="togglePasswordVisibility"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4" v-if="editmode">
            <b-button
              :block="$store.getters['app/currentBreakPoint'] === 'xs'"
              :disabled="processing"
              @click="resetpassword()"
              class="mt-1"
              variant="outline-danger">
              <feather-icon icon="RefreshCwIcon" class="mr-50"/>
              <span>Reset Password</span>
            </b-button>
          </b-col>
        </b-row>
        <b-row class="mt-3">
          <b-col>
            <b-button
              type="button"
              :disabled="invalid || processing"
              @click="editmode ? updatedatalogin() : createLogin()"
              variant="outline-primary"
              class="mb-1 mb-sm-0 mr-0 mr-sm-1"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'">
              {{ editmode ? 'Update' : 'Buat Baru' }}
            </b-button>
            <b-button
              type="button"
              :disabled="processing"
              @click="closeForm()"
              variant="danger"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'">
              Tutup
            </b-button>
          </b-col>
        </b-row>
      </validation-observer>
    </div>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
  BButton, BMedia, BAvatar, BRow, BCol, BFormGroup, BFormInput, BForm, BTable, BCard, BCardHeader, BCardTitle,
  BAlert, BFormSelect, BFormSelectOption, BInputGroup, BInputGroupAppend, BOverlay,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import http from '@/customs/axios'
import { required, email, password, confirmed } from '@validations'

export default {
  props: {
    userNik: { type: Number },
    userAccount: { type: Object, default: null },
  },
  components:{
    ValidationProvider, ValidationObserver,
    BRow, BCol,
    BFormGroup, BFormInput, BForm, BFormSelect, BFormSelectOption, BInputGroup, BInputGroupAppend,
    vSelect,
    BButton,
  },
  data(){
    return{
      formlogin: {
        nik: this.userNik,
        email: null,
        loginActive: 1,
        new_password: null,
        rep_password: null,
      },
      optionsactive: [
        { value: 1, text: 'Aktif'},
        { value: 0, text: 'Non-Aktif'}
      ],
      processing: false,
      required, 
      email, 
      password, 
      confirmed,
      editmode: false
    }
  },
  mixins: [togglePasswordVisibility],
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    }
  },
  watch: {
    'userAccount': {
      immediate: true,
      deep: true,
      handler(n){
        if(n !== null){
          this.editmode = true
          this.formlogin = {
            nik: this.userNik,
            email: this.userAccount.email,
            loginActive: this.userAccount.active
          }
        }
      }
    }
  },
  methods:{
    closeForm(){
      this.formlogin = {
        nik: null,
        email: null,
        loginActive: null,
        new_password: null,
        rep_password: null,
      }
      this.$router.replace({ name: 'apps-usermgt-list' })
    },
    createLogin(){
      this.$refs.createaccount.validate().then(success => {
        if(success){
          this.processing = true
          http.post('user/auth/register', this.formlogin)
          .then((res) => {
            this.processing = false
            called.$emit('userlistfetchData')
            this.closeForm()
          })
          .catch((e) => {
            console.error(e)
          })
        }
      })
    },
    resetpassword(){
      this.processing = true
      http
      .post('user/auth/password/'+nik, { resetpassword: 'mig123!' })
      .then((res) => {
        this.processing = false
        called.$emit('userlistfetchData')
        this.closeForm()
      })
      .catch((e) => {
        console.error(e)
      })
    },
    updatedatalogin(){
      this.$refs.createaccount.validate().then(success => {
        if(success){
          this.processing = true
          http.put('user/auth/update/'+this.userNik, this.formlogin)
          .then((res) => {
            this.processing = false
            called.$emit('userlistfetchData')
            this.closeForm()
          })
          .catch((e) => {
            console.error(e)
          })
        }
      })
    }
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
</style>