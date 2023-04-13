<template>
  <div>
    <b-card>

      <b-row>

        <!-- Alert -->
        <b-col md="12" class="mb-1">
          <b-alert
            :variant="alertProps.variant"
            :show="alertProps.show">
            <div class="alert-body">
              <feather-icon :icon="alertProps.icon" size="18" class="mr-50"/>
              <span v-html="alertProps.message" />
            </div>
          </b-alert>
        </b-col>
      </b-row>

      <b-row>

        <!-- Old Password -->
        <b-col md="6">
          <b-form-group
            label="Password Lama"
            label-for="asp-oldpass">
            <b-input-group class="input-group-merge">
              <b-form-input
                v-model="form.valuePasswordOld"
                :type="fieldPasswordOld"
                :disabled="processing"
                id="asp-oldpass"
                name="asp-oldpass"
                placeholder="Password Lama">
                <b-input-group-append
                  is-text>
                  <feather-icon class="cursor-pointer" :icon="passwordToggleIconOld" @click="passwordToggleOld"/>
                </b-input-group-append>
              </b-form-input>
            </b-input-group>
          </b-form-group>
        </b-col>

      </b-row>
      <b-row>

        <!-- New Password -->
        <b-col md="6">
          <b-form-group
            label="Password Baru"
            label-for="asp-newpass">
            <b-input-group class="input-group-merge">
              <b-form-input
                v-model="form.valuePasswordNew"
                :type="fieldPasswordNew"
                :disabled="processing"
                id="asp-newpass"
                name="asp-newpass"
                placeholder="Password Baru">
                <b-input-group-append
                  is-text>
                  <feather-icon class="cursor-pointer" :icon="passwordToggleIconNew" @click="passwordToggleNew"/>
                </b-input-group-append>
              </b-form-input>
            </b-input-group>
          </b-form-group>
        </b-col>

        <!-- Re-Type Password -->
        <b-col md="6">
          <b-form-group
            label="Ulangi Password Baru"
            label-for="asp-renewpass">
            <b-input-group class="input-group-merge">
              <b-form-input
                v-model="form.valueRePassword"
                :type="fieldRePassword"
                :disabled="processing"
                id="asp-renewpass"
                name="asp-renewpass"
                placeholder="Ulangi Password Baru">
                <b-input-group-append
                  is-text>
                  <feather-icon class="cursor-pointer" :icon="passwordToggleIconRe" @click="passwordToggleRe"/>
                </b-input-group-append>
              </b-form-input>
            </b-input-group>
          </b-form-group>
        </b-col>

        <!-- Submit -->
        <b-col cols="12">
          <b-button
            @click="submitForm()"
            :block="$store.getters['app/currentBreakPoint'] === 'xs'"
            :disabled="processing"
            variant="primary"
            class="mt-1 mr-1">
            <b-spinner
              v-if="processing"
              small
              class="mr-25" /> {{ processing ?  'Proses' : 'Update' }}
          </b-button>
          <b-button
            @click="cancelForm()"
            :disabled="processing"
            :block="$store.getters['app/currentBreakPoint'] === 'xs'"
            :variant="processing ? 'outline-secondary' : 'outline-danger'"
            class="mt-1">
            Batal
          </b-button>
        </b-col>

      </b-row>
    </b-card>
  </div>
</template>

<script>
import {
  BCard, BRow, BCol, BFormGroup, BInputGroup, BFormInput, BInputGroupAppend, BButton,
  BAlert, BSpinner,
} from 'bootstrap-vue'
import http from '@/customs/axios'

export default {
  name: "AccountSettingPassword",
  props: {
    UserData: { type: Object, required: true },
  },
  components: {
    BCard, BRow, BCol, BFormGroup, BInputGroup, BFormInput, BInputGroupAppend, BButton,
    BAlert, BSpinner,
  },
  data(){
    return{
      form: {
        valuePasswordOld: '',
        valuePasswordNew: '',
        valueRePassword: '',
      },

      fieldPasswordOld: 'password',
      fieldPasswordNew: 'password',
      fieldRePassword: 'password',

      processing: false,
      alertProps: {
        show: false,
        message: '',
        icon: 'InfoIcon',
        variant: 'primary',
      }
    }
  },
  methods: {
    passwordToggleOld(){
      this.fieldPasswordOld = this.fieldPasswordOld === 'password' ? 'text' : 'password'
    },
    passwordToggleNew(){
      this.fieldPasswordNew = this.fieldPasswordNew === 'password' ? 'text' : 'password'
    },
    passwordToggleRe(){
      this.fieldRePassword = this.fieldRePassword === 'password' ? 'text' : 'password'
    },
    cancelForm(){
      this.resetForm()
      this.$router.push({ name: 'dashboard' })
    },
    submitForm(){
      this.processing = true
      this.alertProps = {
        show: false,
          message: '',
          icon: 'InfoIcon',
          variant: 'primary',
      }
      http.post('user/auth/update/password/'+this.userNik, this.form, { params: { frompage: 'usersettings-resetpassword' } })
      .then((res) => {
        this.alertProps = {
          show: true,
          message: `Password berhasil diupdate: <b>`+`${this.form.valuePasswordNew}`+`</b> .`,
          icon: 'CheckCircleIcon',
          variant: 'info',
        }
        this.processing = false
        this.resetForm()
      })
      .catch((e) => {
        this.alertProps = {
          show: true,
          message: e.response.data.message +' !',
          icon: 'XCircleIcon',
          variant: 'danger',
        }
        this.processing = false
        console.error(e)
      })
    },
    resetForm(){
      this.form = {
        valuePasswordOld: '',
        valuePasswordNew: '',
        valueRePassword: '',
      }
    }
  },
  computed: {
    passwordToggleIconOld(){
      return this.fieldPasswordOld === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    passwordToggleIconNew(){
      return this.fieldPasswordNew === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    passwordToggleIconRe(){
      return this.fieldRePassword === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    userIsAdmin(){
      const userProps = this.UserData;
      return userProps.admin;
    },
    userDetail(){
      const userProps = this.UserData;
      return userProps.detailuser;
    },
    userNik(){
      const userProps = this.UserData;
      return userProps.nik;
    }
  },
}
</script>

<style scoped>

</style>
