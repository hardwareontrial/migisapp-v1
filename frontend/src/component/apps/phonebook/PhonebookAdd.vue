<template>
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
            {{ edit ? 'Edit' : 'Tambah' }} Data Buku Telepon
          </h5>
          <feather-icon
            class="ml-1 cursor-pointer"
            icon="XIcon"
            size="16"/>
        </div>

        <b-overlay :show="overlay" rounded="sm" :opacity="0.90">
          <b-container>
            <!-- <b-alert
              variant="danger"
              :show="error.show">
              <h4 class="alert-heading">
                ({{error.code}})
              </h4>
              <div class="alert-body">
                <ul>
                  <li v-for="(err, i) in error.message" :key="i">{{ err[0] }}</li>
                </ul>
              </div>
            </b-alert> -->
          </b-container>

          <b-form
            class="m-1">
            <validation-observer
              ref="formphone"
              #default="{invalid}">
              <b-form-group>
                <label for="status-brg">Tipe Data Kontak: </label>
                <validation-provider
                  #default="{errors}"
                  name="Type"
                  vid="sjv-type"
                  rules="required">
                  <!-- <b-form-radio-group
                    :options="uoms"
                    v-model="form.uom"
                    class="inline-spacing"/> -->
                  <div class="demo-inline-spacing">
                    <b-form-radio
                      :disabled="edit || contacttype === 'B'"
                      v-model="contacttype"
                      value="A">
                      External
                    </b-form-radio>
                    <b-form-radio
                      :disabled="edit || contacttype === 'A'"
                      v-model="contacttype"
                      value="B">
                      Internal
                    </b-form-radio>
                  </div>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <div id="pb-ext" v-if="contacttype === 'A'">
                <b-form-group>
                  <!-- <label for="status-brg">Tipe Data Kontak: </label> -->
                  <div class="demo-inline-spacing" style="margin-top: -12px;">
                    <b-form-radio
                      :disabled="edit"
                      v-model="form.typeinput"
                      :value="1"
                      name="opt-stock">
                      Personal
                    </b-form-radio>
                    <b-form-radio
                      :disabled="edit"
                      v-model="form.typeinput"
                      :value="2"
                      name="opt-user">
                      Perusahaan
                    </b-form-radio>
                  </div>
                </b-form-group>

                <div v-if="form.typeinput === 1">
                  <b-form-group
                    label="Nama"
                    label-for="pb-nama">
                    <validation-provider
                      #default="{errors}"
                      name="Nama"
                      vid="pbv-nama"
                      rules="required">
                      <b-form-input
                        v-model="form.name"
                        id="pb-nama"
                        autofocus/>
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                  <b-form-group
                    label="Alamat"
                    label-for="pb-address">
                    <validation-provider
                      #default="{errors}"
                      name="Alamat"
                      vid="pbv-address"
                      rules="required">
                      <b-form-textarea
                        v-model="form.address"
                        id="pb-address"/>
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </div>
                <div v-else>
                  <b-form-group
                    label="Nama Perusahaan"
                    label-for="pb-cor-name">
                    <validation-provider
                      #default="{errors}"
                      name="Nama Perusahaan"
                      vid="pbv-cor-name"
                      rules="required">
                      <b-form-input
                        v-model="form.company"
                        id="pb-cor-name"
                        autofocus/>
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                  <b-form-group
                    label="Alamat Perusahaan"
                    label-for="pb-cor-address">
                    <validation-provider
                      #default="{errors}"
                      name="Alamat Perusahaan"
                      vid="pbv-cor-address"
                      rules="required">
                      <b-form-textarea
                        v-model="form.company_address"
                        id="pb-cor-address"/>
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </div>
                <b-form-group
                  label="Kota"
                  label-for="pb-city">
                  <b-form-input
                    v-model="form.city"
                    id="pb-city"/>
                </b-form-group>
                <b-form-group
                  label="Email"
                  label-for="pb-email">
                  <b-form-input
                    v-model="form.email"
                    id="pb-email"/>
                </b-form-group>
                <b-form-group
                  label="Note"
                  label-for="pb-note">
                  <b-form-textarea
                    v-model="form.keterangan"
                    id="pb-note"/>
                </b-form-group>
                <hr>
                <b-row
                  id="pb-inputs"
                  v-for="(input, i) in form.inputs"
                  :key="i"
                  :counter="testcounter"
                  class="mb-1">
                  <b-col cols="12" v-show="false">
                    <b-form-group
                      label="ID"
                      label-for="f-id">
                      <b-form-input
                        id="f-id"
                        v-model="input.id"/>
                    </b-form-group>
                  </b-col>
                  <b-col cols="12">
                    <b-form-group
                      label="Telepon/Fax"
                      label-for="f-number">
                      <validation-provider
                        #default="{errors}"
                        name="Nomor"
                        vid="pbv-phone-no"
                        rules="required">
                        <b-form-input
                          id="f-number"
                          v-model="input.number"
                          @keypress="isNumber($event)"/>
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col cols="12">
                    <b-form-group
                      label="PIC"
                      label-for="f-pic">
                      <validation-provider
                        #default="{errors}"
                        name="Nomor"
                        vid="pbv-pic"
                        rules="required">
                        <b-form-input
                          id="f-pic"
                          v-model="input.pic"/>
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col cols="12">
                    <feather-icon
                      @click="addfield()"
                      icon="PlusCircleIcon"
                      class="mr-1 text-success"/>
                    <feather-icon
                      @click="removefield(i)"
                      icon="MinusCircleIcon"
                      class="mr-1 text-danger"/>
                  </b-col>
                </b-row>
                <hr>
              </div>

              <div id="pb-int" v-else>
                <b-form-group
                  label="User"
                  label-for="pbinternal-user">
                  <v-select 
                    :options="userlist"
                    label="name"
                    :clearable="false"
                    :reduce="name => name.id"
                    id="pbinternal-user"
                    v-model="form2.user_id" />
                </b-form-group>
                <b-form-group
                  label="Extension"
                  label-for="pbinternal-ext">
                  <b-form-input
                    id="pbinternal-ext"
                    v-model="form2.ext"
                    @keypress="isNumber($event)"
                    placeholder="Extension"/>
                </b-form-group>
                <b-form-group
                  label="Lokasi"
                  label-for="pbinternal-lokasi">
                  <v-select 
                    :options="resolveLocation"
                    label="text"
                    :clearable="false"
                    :reduce="text => text.value"
                    id="pbinternal-lokasi"
                    v-model="form2.lokasi_id" />
                </b-form-group>
              </div>

              <div>
                <b-button
                  type="button"
                  :disabled="processing || invalid"
                  :variant="processing ? 'outline-secondary' : 'outline-primary'"
                  @click="edit ? ( contacttype === 'A' ? handleUpdate(dataid) : handleUpdateInt() ) : ( contacttype === 'A' ? handleStore() : handleStoreInt() )"
                  class="mr-1">
                  {{ edit ? ( processing ? 'Process' : 'Update' ) : ( processing ? 'Proses' : 'Simpan' ) }}
                </b-button>
                <b-button
                  type="button"
                  :disabled="processing"
                  @click="contacttype === 'A' ? closeform() : closeformInt()"
                  :variant="processing ? 'secondary' : 'danger'"
                  class="mr-1">
                  Tutup
                </b-button>
              </div>
            </validation-observer>
          </b-form> 
        </b-overlay>
      </template>
  </b-sidebar>
</template>

<script>
import {
  BSidebar,
  BForm, BFormInput, BFormGroup, BFormRadio, BFormTextarea,
  BButton,
  BRow, BCol,
  BAlert,
  BContainer,
  BOverlay,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import http from '@/customs/axios'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'

export default {
  components: {
    BSidebar,
    BForm, BFormInput, BFormGroup, BFormRadio, BFormTextarea,
    BButton,
    BRow, BCol,
    BAlert,
    BContainer,
    BOverlay,
    vSelect,
    ValidationProvider, ValidationObserver,
  },
  directives: { Ripple },
  data(){
    return{
      required,
      visible: false,
      form: {
        name: '',
        address: '',
        company: '',
        company_address: '',
        city: '',
        email: '',
        keterangan: '',
        inputs: [],
        typeinput: ''
      },
      contacttype: '',
      processing: false,
      edit: false,
      error: {
        show: false,
        code: '',
        message: ''
      },
      dataid: '',
      overlay: false,
      userlist: [],
      resolveLocation: [],
      form2: {
        user_id: '',
        ext: '',
        lokasi_id: ''
      }
    }
  },
  mounted(){
    called.$on('phonebookaddOpenForm', () => { this.openform() })
    called.$on('phonebookaddOpenEditForm', (value) => { this.openformedit(value) })
    called.$on('phonebookaddOpenFormInt', () => { this.openformInt() })
    called.$on('phonebookaddOpenEditFormInt', (value) => { this.openformeditInt(value) })
    called.$on('phonebookaddExtResolveLocation', (val) => { this.resolveLocation = val })
    this.getUserList()
  },
  methods: {
    isNumber(e){
      e = (e) ? e : window.event;
      let cCode = (e.which) ? e.which : e.kCode;
      if ((cCode > 31 && (cCode < 48 || cCode > 57)) && (cCode < 40 || cCode > 41) && cCode !== 43 && cCode !== 45) {
        e.preventDefault();
      } else {
        return true;
      }
    },
    openform(){
      this.visible = true
      this.contacttype = 'A'
      this.error = {
        show: false,
        code: '',
        message: ''
      }
    },
    closeform(){
      this.error = {
        show: false,
        code: '',
        message: ''
      }
      this.contacttype = ''
      this.edit = false
      this.form.typeinput = ''
      this.form.inputs = []
      this.form.name = ''
      this.form.address = ''
      this.form.company = ''
      this.form.company_address = ''
      this.form.city = ''
      this.form.email = ''
      this.form.keterangan = ''
      this.dataid = ''
      this.visible = false
    },
    openformedit(val){
      this.error = {
        show: false,
        code: '',
        message: ''
      }
      this.visible = true
      this.contacttype = 'A'
      this.edit = true
      this.overlay = true
      http
      .get('phonebook/external/data/'+val)
      .then((data) => {
        const res = data.data
        this.form = {
          name: res.name,
          address: res.address,
          company: res.company_name,
          company_address: res.company_address,
          city: res.city,
          email: res.email,
          keterangan: res.note,
          inputs: res.detail,
          typeinput: res.type
        }
        this.dataid = res.id
        this.overlay = false
      })
      .catch((e) => {
        console.error(e)
      })
    },
    addfield(){
      if(this.testcounter === 5){
        alert('maksimal 5 nomor')
        return false
      }
      this.form.inputs.push({id: '', number: '', pic: ''})
    },
    removefield(i){
      if(!this.edit){
        if(this.testcounter === 1){
          alert('minimal 1 field')
          return false
        }
        this.form.inputs.splice(i, 1)
      }else{
        if(this.testcounter > 1 && this.testcounter <= 5){
          if(this.form.inputs[i].id === '' || this.form.inputs[i].number === '' || this.form.inputs[i].pic === ''){
            this.form.inputs.splice(i , 1)
          }else{
            let detailid = this.form.inputs[i].id
            this.$swal({
              text: 'Yakin akan dihapus?',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Hapus',
              cancelButtonText: 'Batal',
              customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-primary ml-1',
              },
            }).then((confirmation) => {
              if(confirmation.value){
                this.processing = true
                http
                .delete('phonebook/external/data/'+this.dataid+'/detail/'+detailid)
                .then((r) =>{
                  called.$emit('phonebooklistHandleFetchData')
                  this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: r.data.message, icon: 'CheckCircleIcon', variant: 'success' },
                  },{ position: 'top-right' })
                  this.processing = false
                  this.openformedit(this.dataid)
                })
                .catch((e) => {
                  console.error(e)
                })
              }
            })
          }
        }
        if(this.testcounter === 1){
          alert('minimal 1 field')
          return false
        }
      }
    },
    handleStore(){
      this.error = {
        show: false,
        code: '',
        message: ''
      }
      this.processing = true
      http
      .post('phonebook/external/new', this.form)
      .then((data) => {
        this.processing = false
        called.$emit('phonebooklistHandleFetchData')
        this.$toast({
          component: ToastificationContent,
          props: { icon: 'CheckCircleIcon', variant: 'success', title: data.data.message },
        }, { position: 'top-right' })
        this.closeform()
      })
      .catch((e) => {
        console.error(e.response.data)
        this.processing = false
        this.error = {
          show: true,
          code: e.response.status,
          message: e.response.data.errors
        }
      })
    },
    handleUpdate(val){
      this.error = {
        show: false,
        code: '',
        message: ''
      }
      this.processing = true
      http
      .put('phonebook/external/data/'+val, this.form)
      .then((data) => {
        this.processing = false
        called.$emit('phonebooklistHandleFetchData')
        this.$toast({
          component: ToastificationContent,
          props: {
            icon: 'CheckCircleIcon',
            variant: 'success',
            title: data.data.message
          },
        },
        { position: 'top-right' })
        this.closeform()
      })
      .catch((e) => {
        // console.error(e)
        this.processing = false
        this.error = {
          show: true,
          code: e.response.status,
          message: e.response.data.errors
        }
      })
    },
    getUserList(){
      http
      .get('misc/list/userlist')
      .then((res) => { 
        // console.log(res.data)
        this.userlist = res.data
      })
      .catch((e) => { console.error(e) })
    },
    openformInt(){
      this.visible = true
      this.contacttype = 'B'
      this.error = {
        show: false,
        code: '',
        message: ''
      }
    },
    closeformInt(){
      this.form2 = {
        user_id: '',
        ext: '',
        lokasi_id: ''
      }
      this.error = {
        show: false,
        code: '',
        message: ''
      }
      this.contacttype = ''
      this.edit = false
      this.visible = false
      this.dataid = ''
    },
    handleStoreInt(){
      this.error = {
        show: false,
        code: '',
        message: ''
      }
      this.processing = true
      http
      .post('phonebook/extension/data', this.form2)
      .then((res) => {
        this.processing = false
        called.$emit('phonebooklistExtensionHandleFetchData')
        this.$toast({
          component: ToastificationContent,
          props: {
            // title: res.status.toString(),
            icon: 'CheckCircleIcon',
            variant: 'success',
            // text: res.data.message
            title: data.data.message
          },
        },
        { position: 'top-right' })
        this.closeform()
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
    openformeditInt(val){
      this.error = {
        show: false,
        code: '',
        message: ''
      }
      this.visible = true
      this.contacttype = 'B'
      this.edit = true
      this.overlay = true
      http
      .get('phonebook/extension/data/'+val)
      .then((data) => {
        const res = data.data
        this.form2 = {
          user_id: res.user_id,
          ext: res.ext,
          lokasi_id: res.lokasi_id,
        }
        this.dataid = res.id
        this.overlay = false
      })
      .catch((e) => {
        console.error(e)
        this.overlay = false
      })
    },
    handleUpdateInt(){
      this.error = {
        show: false,
        code: '',
        message: ''
      }
      this.processing = true
      http
      .put('phonebook/extension/data/'+this.dataid, this.form2)
      .then((res) => {
        // console.log(res)
        this.processing = false
        called.$emit('phonebooklistExtensionHandleFetchData')
        this.$toast({
          component: ToastificationContent,
          props: {
            // title: res.status.toString(),
            icon: 'CheckCircleIcon',
            variant: 'success',
            title: res.data.message
          },
        },
        { position: 'top-right' })
        this.closeformInt()
      })
    }
  },
  created(){
    // console.clear()
  },
  watch: {
    'form.typeinput': {
      handler(n){
        if(n === 1 && !this.edit) {
          this.form = {
            name: '',
            address: '',
            company: '',
            company_address: '',
            email: '',
            city: '',
            keterangan: '',
            inputs: [],
            typeinput: 1,
          },
          this.form.inputs.push({id: '', number: '', pic: ''})
        }
        if(n === 2 && !this.edit) {
          this.form = {
            name: '',
            address: '',
            company: '',
            company_address: '',
            email: '',
            city: '',
            keterangan: '',
            inputs: [],
            typeinput: 2,
          },
          this.form.inputs.push({id: '', number: '', pic: ''})
        }
      }
    },
    'contacttype': {
      handler(n){
        if(n === 'A' && !this.edit) this.form.typeinput = 1
      }
    }
  },
  computed: {
    testcounter(){
      return this.form.inputs.length
    }
  }
}
</script>

<style lang="scss" scoped>

</style>