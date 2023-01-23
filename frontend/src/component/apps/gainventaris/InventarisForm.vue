<template>
  <div>
    <b-overlay :show="showOverlay" rounded="sm" :opacity="0.90">
      <b-card>
        <validation-observer
          ref="formdelivery"
          #default="{invalid}">
          <b-row>
            <b-col cols="12" md="6" v-if="edit">
              <b-form-group>
                <label for="kode-barang">Kode Barang: </label>
                <b-form-input
                  id="kode-barang"
                  readonly
                  v-model="form.kode_brg"
                  placeholder="Kode"/>
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group>
                <label for="nama-barang">Nama Barang:</label>
                <validation-provider
                  #default="{errors}"
                  name="Nama Barang"
                  vid="invv-namabrg"
                  rules="required">
                  <b-form-input
                    :state="errors.length > 0 ? false:null"
                    id="nama-barang"
                    v-model="form.nama_brg"
                    :disabled="statusmtc"
                    placeholder="Nama Barang"/>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group>
                <label for="dept-pemilik">Penanggung Jawab:</label>
                <validation-provider
                  #default="{errors}"
                  name="Penanggung Jawab"
                  vid="invv-deptbeli"
                  rules="required">
                  <v-select
                    :options="listresdept"
                    label="text"
                    :reduce="text=>text.value"
                    :state="errors.length > 0 ? false:null"
                    id="dept-pemilik"
                    :disabled="statusmtc"
                    v-model="form.deptbeli"/>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group>
                <label for="tglbeli">Tanggal Beli:</label>
                <validation-provider
                  #default="{errors}"
                  name="Tanggal Beli"
                  vid="invv-tglbeli"
                  rules="required">
                  <flat-pickr
                    id="tglbeli"
                    class="form-control"
                    :disabled="statusmtc"
                    v-model="form.tglbeli"
                    placeholder="Pilih Tanggal"
                    :config="{ altInput: true, altFormat: 'F j, Y', dateFormat: 'Y-m-d'}"/>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group>
                <label for="nama-toko">Toko:</label>
                <b-form-input
                  id="nama-toko"
                  :disabled="statusmtc"
                  v-model="form.toko"
                  placeholder="Nama Toko"/>
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group>
                <label for="harga-barang">Harga:</label>
                <b-form-input
                  id="harga-barang"
                  v-model="form.harga"
                  :disabled="statusmtc"
                  @keypress="isNumber($event)"
                  placeholder="Harga"/>
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group>
                <label for="sn-barang">Serial Number:</label>
                <b-form-input
                  id="sn-barang"
                  v-model="form.sn"
                  :disabled="statusmtc"
                  placeholder="Nomor Serial"/>
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group>
                <label for="merk">Merk:</label>
                <validation-provider
                  #default="{errors}"
                  name="Merk"
                  vid="invv-merk"
                  rules="required">
                  <b-input-group>
                    <b-form-select
                      id="merk"
                      v-model="form.merk"
                      :disabled="statusmtc"
                      class="form-control">
                      <b-form-select-option value="" disabled>Pilih...</b-form-select-option>
                      <b-form-select-option :value="list.id" v-for="list in list_merk" :key="list.id">{{ list.name }}</b-form-select-option>
                    </b-form-select>
                    <b-button
                      type="button"
                      @click="addMerk"
                      :variant="statusmtc? 'secondary' : 'primary'"
                      :disabled="statusmtc"
                      class="btn-icon">
                      <feather-icon icon="PlusIcon" />
                    </b-button>
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group>
                <label for="lokasi">Lokasi:</label>
                <validation-provider
                  #default="{errors}"
                  name="Lokasi"
                  vid="invv-lokasi"
                  rules="required">
                  <b-input-group>
                    <b-form-select
                      id="lokasi"
                      :disabled="statusmtc"
                      v-model="form.lokasi"
                      class="form-control">
                      <b-form-select-option value="" disabled>Pilih...</b-form-select-option>
                      <b-form-select-option :value="list.id" v-for="list in list_lokasi" :key="list.id">{{ list.name }}</b-form-select-option>
                    </b-form-select>
                    <b-button
                      type="button"
                      @click="addLocation"
                      :disabled="statusmtc"
                      :variant="statusmtc? 'secondary' : 'primary'"
                      class="btn-icon">
                      <feather-icon icon="PlusIcon" />
                    </b-button>
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group>
                <label for="status-brg">Status:</label>
                <validation-provider
                  #default="{errors}"
                  name="Status Barang"
                  vid="invv-statusbrg"
                  rules="required">
                  <div class="demo-inline-spacing" style="margin-top: -12px;">
                    <b-form-radio
                      name="opt-stock"
                      v-model="form.status"
                      :value="1">
                      Stock
                    </b-form-radio>
                    <b-form-radio
                      name="opt-user"
                      v-model="form.status"
                      :disabled="disableOptUser"
                      :value="2">
                      User
                    </b-form-radio>
                    <b-form-radio
                      name="opt-aksesoris"
                      v-model="form.status"
                      :disabled="disableOptAcc"
                      :value="3">
                      Properti
                    </b-form-radio>
                    <b-form-radio
                      name="opt-perawatan"
                      v-model="form.status"
                      :disabled="disableOptMtc"
                      :value="4">
                      Perawatan
                    </b-form-radio>
                  </div>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-form-group>
                <label for="spesifikasi">Spesifikasi:</label>
                <b-form-textarea
                  id="spesifikasi"
                  :disabled="statusmtc"
                  v-model="form.spesifikasi"
                  placeholder="Spesifikasi"
                  rows="3"/>
              </b-form-group>
            </b-col>
            <b-col cols="12" v-if="statusmtc">
              <b-form-group>
                <label for="mtc">Maintenance Note:</label>
                <validation-provider
                  #default="{errors}"
                  name="Maintenance Note"
                  vid="invv-mtcnote"
                  rules="required">
                  <b-form-textarea
                    id="mtc"
                    :disabled="false"
                    v-model="form.mtc_note"
                    placeholder="Info Perawatan"
                    rows="3"/>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="12" v-if="statususer">
              <b-row>
                <b-col cols="12" md="6">
                  <b-form-group>
                    <label for="user1">User 1:</label>
                    <validation-provider
                      #default="{errors}"
                      name="User-1"
                      vid="invv-user1"
                      rules="required">
                      <b-form-select
                        id="user1"
                        v-model="form.user1"
                        :disabled="false"
                        placeholder="Pilih User">
                        <b-form-select-option value="" disabled>Pilih...</b-form-select-option>
                        <b-form-select-option :value="list.id" v-for="list in list_user" :key="list.id">{{ list.name }}</b-form-select-option>
                      </b-form-select>
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col cols="12" md="6">
                  <b-form-group>
                    <label for="user2">User 2:</label>
                    <b-form-select
                      id="user2"
                      :disabled="false"
                      v-model="form.user2"
                      placeholder="Pilih User">
                      <b-form-select-option value="" disabled>Pilih...</b-form-select-option>
                      <b-form-select-option :value="list.id" v-for="list in list_user" :key="list.id">{{ list.name }}</b-form-select-option>
                    </b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>
            </b-col>
            <b-col cols="12" class="text-center mt-1">
              <b-button
                type="button"
                class="mr-1"
                :disabled="invalid || processing"
                @click="edit ? updateData() : submitData()"
                :variant="invalid ? 'outline-secondary' : 'outline-primary'"> 
                  {{ edit ? (processing? 'Proses':'Update') : (processing? 'Proses':'Simpan') }}
              </b-button>
              <b-button
                type="button"
                class="mr-1"
                :disabled="processing"
                @click="handleBack()"
                variant="danger">Batal
              </b-button>
            </b-col>
          </b-row>
        </validation-observer>
      </b-card>
    </b-overlay>
  </div>
</template>

<script>
import {
  BRow, BCol,
  BFormGroup, BFormInput, BForm, BFormTextarea, BInputGroup, BFormRadioGroup, BFormRadio,
  BButton,
  BContainer,
  BCard, BCardText,
  BFormDatepicker,
  BFormSelect, BFormSelectOption,
  BAlert,
  BOverlay,
  BIcon,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import flatPickr from 'vue-flatpickr-component'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'
import vSelect from 'vue-select'

export default {
  components: {
    BOverlay,
    ValidationProvider, ValidationObserver,
    BCard,
    flatPickr,
    BRow, BCol,
    BFormGroup, BFormInput, BFormRadioGroup, BFormRadio, BFormSelect, BFormSelectOption, BFormTextarea, BInputGroup,
    vSelect,
    BButton,
  },
  data(){
    return{
      required,
      showOverlay: false,
      form: {
        kode_brg: '',
        nama_brg: '',
        deptbeli: '',
        tglbeli: '',
        toko: '',
        harga: '',
        sn: '',
        status: '',
        merk: '',
        lokasi: '',
        spesifikasi: '',
        mtc_note: '',
        user1: '',
        user2: '',
      },
      edit: false,
      processing: false,
      statusmtc: false,
      statususer: false,
      list_merk: [],
      list_lokasi: [],
      list_user: [],
    }
  },
  methods: {
    isNumber(e){
      e = (e) ? e : window.event;
      let cCode = (e.which) ? e.which : e.kCode;
      if ((cCode > 31 && (cCode < 48 || cCode > 57)) && (cCode < 40 || cCode > 41)) {
        e.preventDefault();
      } else {
        return true;
      }
    },
    getlistmerk(){
      http
      .get('misc/list/merk')
      .then(({data}) => {
        this.list_merk = data
      })
      .catch((e) => { console.error(e) })
    },
    addMerk(){
      called.$emit('gainventarisOpenAddMerk')
    },
    getlistlokasi(){
      http
      .get('misc/list/location')
      .then(({data}) => {
        this.list_lokasi = data
      })
      .catch((e) => { console.error(e) })
    },
    addLocation(){
      called.$emit('gainventarisOpenAddLocation')
    },
    getlistuser(){
      http
      .get('misc/list/userlist')
      .then(({data}) => {
        this.list_user = data
      })
      .catch((e) => { console.error(e) })
    },
    resetform(){
      this.form = {
        kode_brg: '',
        nama_brg: '',
        deptbeli: '',
        tglbeli: '',
        toko: '',
        harga: '',
        sn: '',
        status: '',
        merk: '',
        lokasi: '',
        spesifikasi: '',
        mtc_note: '',
        user1: '',
        user2: '',
      }
    },
    handleBack(){
      this.resetform()
      called.$emit('inventarislistFetchData')
      this.$router.push({ name: 'apps-inventaris-list' })
    },
    submitData(){
      this.processing = true
      http
      .post('inventaris/new', this.form)
      .then((data) => {
        this.processing = false
        this.handleBack()
        this.$toast({
          component: ToastificationContent,
          props: { icon: 'CheckCircleIcon', variant: 'success', title: data.data.message },
        },{ position: 'top-right' })
      })
      .catch((e) => {
        console.error(e)
      })
    },
    updateData(){
      this.processing = true
      var id = this.$route.params.id
      http
      .put('inventaris/data/'+id, this.form, { params: { keyword: 'update-from-form' } })
      .then((data) => {
        console.log(data)
        this.processing = false
        this.handleBack()
        this.$toast({
          component: ToastificationContent,
          props: { icon: 'CheckCircleIcon', variant: 'success', title: data.data.message },
        },{ position: 'top-right' })
      })
      .catch((e) => {
        console.error(e)
      })
    },
    getdetail(){
      this.showOverlay = true
      var id = this.$route.query.q
      http
      .get('inventaris/data/'+id)
      .then((data) => {
        this.form.kode_brg = data.data.kd_brg
        this.form.nama_brg = data.data.nama_brg
        this.form.deptbeli = data.data.pemilik_id
        this.form.tglbeli = data.data.tgl_beli
        this.form.toko = data.data.toko
        this.form.harga = data.data.harga
        this.form.sn = data.data.serialnumber
        this.form.status = data.data.status_id
        this.form.merk = data.data.merk_id
        this.form.lokasi = data.data.lokasi_id
        this.form.spesifikasi = data.data.spesifikasi
        this.form.user1 = data.data.user1_id
        this.form.user2 = data.data.user2_id
        this.form.mtc_note = data.data.mtc_note
        this.showOverlay = false
      })
      .catch((e) => {
        console.error(e)
      })
    },
  },
  computed: {
    listresdept(){
      const userdata = JSON.parse(localStorage.getItem('userdata'))
      const isadmin = userdata.admin
      const rawoptions = [
        { value: 1, text: 'GA' },
        { value: 2, text: 'IT' },
        { value: 3, text: 'HSE' },
      ]
      if(isadmin !== 1){
        const userdept = userdata.detailuser.dept_id
        if(userdept === 6){
          return rawoptions.filter(item => item.value === 3)
        }else if(userdept === 8){
          return rawoptions.filter(item => item.value !== 3)
        }
      }else{
        return rawoptions
      }
    },
    disableOptMtc(){
      if('id' in this.$route.params){ return false }else{ return true }
    },
    disableOptUser(){
      if('id' in this.$route.params){
        if(this.form.status === 3) return true
      }
    },
    disableOptAcc(){
      if('id' in this.$route.params){
        if(this.form.status === 2) return true
      }
    },
  },
  mounted(){
    this.getlistmerk()
    this.getlistlokasi()
    this.getlistuser()
    called.$on('inventarisformListLokasi', () => { this.getlistlokasi() })
    called.$on('inventarisformListMerk', () => { this.getlistmerk() })
  },
  watch: {
    'form.status': {
      handler(n) {
        if(n === 2) { this.statususer = true }else{ this.statususer = false }
        if(n === 4) { this.statusmtc = true }else{ this.statusmtc = false }
      }
    },
  },
  created(){
    if('id' in this.$route.params){
      this.edit = true
      this.getdetail()
    }
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-flatpicker.scss';
  @import '@core/scss/vue/libs/vue-select.scss';
</style>