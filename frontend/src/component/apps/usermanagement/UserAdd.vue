<template>
  <div>
    <b-sidebar
      :visible="visible"
      bg-variant="white"
      sidebar-class="sidebar-lg"
      backdrop
      right
      no-header
      no-close-on-backdrop
      no-close-on-esc>
      <template #default>
        <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
          <h5 class="mb-0">
            Tambah User Login
          </h5>
          <feather-icon
            class="ml-1 cursor-pointer"
            icon="XIcon"
            size="16"
            @click="closeForm"/>
        </div>

        <b-alert
          :show="error.show"
          variant="danger">
          <div class="alert-body">
            <div v-for="(err, i) in error.message" :key="i">
              <div v-for="(e, j) in err" :key="j">
                <feather-icon
                  class="mr-25"
                  icon="AlertCircleIcon"/>
                <span class="ml-25">
                  {{ e }}
                </span>
              </div>
            </div>
          </div>
        </b-alert>

        <b-form
          class="p-2">
          <b-form-group>
            <div class="demo-inline-spacing">
              <b-form-radio
                v-model="addtype"
                value="A">
                New User
              </b-form-radio>
              <b-form-radio
                v-model="addtype"
                value="B">
                New Login
              </b-form-radio>
            </div>
          </b-form-group>

          <div v-if="addtype === 'A'">
            <b-form-group
              label="Status"
              label-for="user-status">
              <div class="demo-inline-spacing" style="margin-top: -12px;">
                <b-form-radio
                  value="Admin"
                  v-model="formuser.statusUser">
                  Admin
                </b-form-radio>
                <b-form-radio
                  value="User"
                  v-model="formuser.statusUser">
                  User
                </b-form-radio>
                <b-form-radio
                  value="External"
                  v-model="formuser.statusUser">
                  External
                </b-form-radio>
              </div>
            </b-form-group>
            <b-form-group
              label="NIK"
              label-for="user-nik">
              <b-form-input
                :readonly="formuser.statusUser === 'Admin' || formuser.statusUser === 'External'"
                v-model="formuser.nik"
                @keypress="isNumber($event)"
                id="user-nik"/>
            </b-form-group>
            <b-form-group
              label="Nama"
              label-for="user-name">
              <b-form-input
                v-model="formuser.name"
                id="user-name"/>
            </b-form-group>
            <b-form-group
              label="Email"
              label-for="user-login-email">
              <b-form-input
                v-model="formuser.email"
                id="user-login-email"/>
            </b-form-group>
            <b-form-group
              label="Password"
              label-for="user-login-pass">
              <b-form-input
                v-model="formuser.password"
                id="user-login-pass"/>
            </b-form-group>
            <b-form-group
              label="User Active"
              label-for="user-active">
              <b-form-select
                v-model="formuser.userActive"
                id="user-active"
                class="form-control">
                <b-form-select-option value="0">Tidak Aktif</b-form-select-option>
                <b-form-select-option value="1">Aktif</b-form-select-option>
              </b-form-select>
            </b-form-group>
            <b-form-group
              label="Login Active"
              label-for="login-active">
              <b-form-select
                v-model="formuser.loginActive"
                id="login-active"
                class="form-control">
                <b-form-select-option value="0">Tidak Aktif</b-form-select-option>
                <b-form-select-option value="1">Aktif</b-form-select-option>
              </b-form-select>
            </b-form-group>
            <b-form-group
              label="Role"
              label-for="user-login-user">
              <v-select
                disabled
                :clearable="false"
                id="user-login-user"/>
            </b-form-group>
          </div>

          <div v-if="addtype === 'B'">
            <b-form-group
              label="User"
              label-for="user-login-user">
              <v-select
                v-model="formlogin.nik"
                :clearable="false"
                :options="userlist"
                label="name"
                :reduce="name => name.nik"
                id="user-login-user"/>
            </b-form-group>
            <b-form-group
              label="Email"
              label-for="user-login-email">
              <b-form-input
                v-model="formlogin.email"
                id="user-login-email"/>
            </b-form-group>
            <b-form-group
              label="Password"
              label-for="user-login-pass">
              <b-form-input
                v-model="formlogin.new_password"
                id="user-login-pass"/>
            </b-form-group>
            <b-form-group
              label="Ulangi Password"
              label-for="user-login-pass">
              <b-form-input
                v-model="formlogin.rep_password"
                id="user-login-pass"/>
            </b-form-group>
            <b-form-group
              label="Login Active"
              label-for="user-active">
              <b-form-select
                v-model="formlogin.loginActive"
                id="user-active"
                class="form-control">
                <b-form-select-option value="0">Tidak Aktif</b-form-select-option>
                <b-form-select-option value="1">Aktif</b-form-select-option>
              </b-form-select>
            </b-form-group>
          </div>
        </b-form>

        <div class="d-flex mt-2 p-2">
          <b-button
            type="button"
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            :variant="processing ? 'secondary' : 'primary'"
            @click="addtype === 'A' ? submitNewUser() : submitNewLogin()"
            class="mr-1"
            :disabled="processing">
            {{ processing ? 'Proses' : 'Tambah' }}
          </b-button>
          <b-button
            type="button"
            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
            variant="outline-secondary"
            @click="closeForm"
            :disabled="processing">
            Batal
          </b-button>
        </div>
      </template>
    </b-sidebar>
  </div>
</template>

<script>
import {
  BSidebar,
  BForm, BFormInput, BFormGroup, BFormRadio, BFormSelect, BFormSelectOption,
  BButton,
  BRow, BCol,
  BAlert,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import http from '@/customs/axios'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    BSidebar,
    BForm, BFormInput, BFormGroup, BFormRadio, BFormSelect, BFormSelectOption,
    BButton,
    BRow, BCol,
    BAlert,
    vSelect,
  },
  directives: { Ripple },
  data(){
    return{
      processing: false,
      visible: false,
      addtype: 'A',
      userlist: [],
      formuser: {
        statusUser: '',
        nik: '',
        name: '',
        email: '',
        password: '',
        userActive: 1,
        loginActive: 1
      },
      formlogin: {
        nik: '',
        email: '',
        new_password: '',
        rep_password: '',
        loginActive: 1
      },
      error: {
        show: false,
        code: '',
        message: []
      },
      providenik: {
        admin: null,
        ext: null
      }
    }
  },
  methods: {
    openForm(){
      this.visible = true
    },
    closeForm(){
      this.visible = false
      this.processing = false,
      this.addtype = 'A',
      this.formuser = {
        statusUser: '',
        nik: '',
        name: '',
        email: '',
        password: '',
        userActive: 1,
        loginActive: 1
      },
      this.formlogin = {
        nik: '',
        email: '',
        new_password: '',
        rep_password: '',
        loginActive: 1
      },
      this.error = {
        show: false,
        code: '',
        message: []
      }
    },
    getuserlist(){
      http
      .get('misc/list/usernologin')
      .then((res) => {
        // console.log(res)
        this.userlist = res.data
      })
      .catch((e) => { console.error(e) })
    },
    getnumbernik(){
      http.get('user/new/number')
      .then((res) => {
        this.providenik = {
          admin: res.data.num_admin,
          ext: res.data.num_external
        }
      })
      .catch((e) => { console.error(e) })
    },
    // getnikadmin(){
    //   this.nik = ''
    //   http
    //   .get('user/data/nik-admin')
    //   .then((res) => {
    //     this.formuser.nik = res.data
    //   })
    //   .catch((e) => { console.error(e) })
    // },
    // getnikexternal(){
    //   this.nik = ''
    //   http
    //   .get('user/data/nik-external')
    //   .then((res) => {
    //     this.formuser.nik = res.data
    //   })
    //   .catch((e) => { console.error(e) })
    // },
    isNumber(e){
      e = (e) ? e : window.event;
      let cCode = (e.which) ? e.which : e.kCode;
      if ((cCode > 31 && (cCode < 48 || cCode > 57)) && (cCode < 40 || cCode > 41)) {
        e.preventDefault();
      } else {
        return true;
      }
    },
    submitNewUser(){
      this.processing = true
      http
      .post('user/store', this.formuser)
      .then((res) => {
        this.processing = false
        called.$emit('userlistfetchData')
        this.$toast({
          component: ToastificationContent,
          props: {
            icon: 'CheckCircleIcon',
            variant: 'success',
            title: res.data.message
          },
        },
        { position: 'top-right' })
        this.closeForm()
      })
      .catch((e) => {
        console.error(e)
        this.processing = false
        this.error = {
          show: true,
          code: e.response.status,
          message: e.response.data.errors
        }
      })
    },
    submitNewLogin(){
      this.processing = true
      http
      .post('user/auth/register', this.formlogin)
      .then((res) => {
        this.processing = false
        called.$emit('userlistfetchData')
        this.$toast({
          component: ToastificationContent,
          props: {
            icon: 'CheckCircleIcon',
            variant: 'success',
            title: res.data.message
          },
        },
        { position: 'top-right' })
        this.closeForm()
      })
      .catch((e) => {
        console.error(e)
        this.processing = false
        this.error = {
          show: true,
          code: e.response.status,
          message: e.response.data.errors
        }
      })
    }
  },
  mounted() {
    called.$on('usermanagementUserAdd', () => { this.openForm() })
    this.getuserlist()
    this.getnumbernik()
  },
  watch: {
    'formuser.statusUser': {
      handler(n){
        if(n === 'Admin') { this.formuser.nik = this.providenik.admin }
        else if(n === 'External') { this.formuser.nik = this.providenik.ext }
        else { this.formuser.nik = '' }
      }
    }
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
</style>
