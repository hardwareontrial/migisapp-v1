<template>
  <div>
    <b-card>
      <validation-observer
        ref="formdelivery"
        #default="{invalid}">
        <b-row>
          <b-col cols="12">
            <b-form-group
              label="SAP/PO Nomor"
              label-for="sj-po"
              label-cols-md="3">
              <b-form-input
                id="sj-po"
                v-model="form.ponumber"
                placeholder="Nomor PO"/>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="SAP/DO Nomor"
              label-for="sj-do"
              label-cols-md="3">
              <b-form-input
                id="sj-do"
                v-model="form.donumber"
                placeholder="Nomor DO"/>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Nama Customer"
              label-for="sj-cust"
              label-cols-md="3">
              <validation-provider
                #default="{errors}"
                name="Nama Customer"
                vid="sjv-cust"
                rules="required">
                <b-form-input
                  :state="errors.length > 0 ? false:null"
                  id="sj-cust"
                  v-model="form.customernama"
                  placeholder="Nama Penerima"/>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Alamat Customer"
              label-for="sj-address"
              label-cols-md="3">
              <validation-provider
                #default="{errors}"
                name="Alamat Customer"
                vid="sjv-address"
                rules="required">
                <b-form-textarea
                  :state="errors.length > 0 ? false:null"
                  id="sj-address"
                  v-model="form.customeralamat"
                  placeholder="Alamat"/>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Kota"
              label-for="sj-city"
              label-cols-md="3">
              <validation-provider
                #default="{errors}"
                name="Kota Tujuan"
                vid="sjv-city"
                rules="required">
                <b-form-input
                  :state="errors.length > 0 ? false:null"
                  id="sj-city"
                  v-model="form.customerkota"
                  placeholder="Kota Tujuan"/>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Nomor Kendaraan"
              label-for="sj-vehicle"
              label-cols-md="3">
              <validation-provider
                #default="{errors}"
                name="Nomor Kendaraan"
                vid="sjv-vehicle"
                rules="required">
                <b-form-input
                  :state="errors.length > 0 ? false:null"
                  id="sj-vehicle"
                  v-model="form.nopol"
                  placeholder="Nopol"/>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Nama Pengemudi"
              label-for="sj-driver"
              label-cols-md="3">
              <validation-provider
                #default="{errors}"
                name="Nama Pengemudi"
                vid="sjv-driver"
                rules="required">
                <b-form-input
                  :state="errors.length > 0 ? false:null"
                  id="sj-driver"
                  v-model="form.drivernama"
                  placeholder="Nama Pengemudi"/>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Item"
              label-for="sj-item"
              label-cols-md="3">
              <validation-provider
                #default="{errors}"
                name="Item"
                vid="sjv-item"
                rules="required">
                <b-form-radio-group
                  :options="items"
                  v-model="form.item"
                  class="inline-spacing"/>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Quantity"
              label-for="sj-qty"
              label-cols-md="3">
              <validation-provider
                #default="{errors}"
                name="Kuantiti"
                vid="sjv-qty"
                rules="required">
                <b-form-input
                  :state="errors.length > 0 ? false:null"
                  id="sj-qty"
                  v-model="form.qty"
                  @keypress="isNumber($event)"
                  placeholder="Quantity"/>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Unit"
              label-for="sj-uom"
              label-cols-md="3">
              <validation-provider
                #default="{errors}"
                name="Uom"
                vid="sjv-uom"
                rules="required">
                <b-form-radio-group
                  :options="uoms"
                  v-model="form.uom"
                  class="inline-spacing"/>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Tanggal Pengiriman"
              label-for="sj-date"
              label-cols-md="3">
              <validation-provider
                #default="{errors}"
                name="Tanggal Kirim"
                vid="sjv-date"
                rules="required">
                <flat-pickr
                  :state="errors.length > 0 ? false:null"
                  v-model="form.tanggalkirim"
                  class="form-control"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col offset-md="3" class="mt-1">
            <hr v-show="$store.getters['app/currentBreakPoint'] === 'xs'"/>
            <b-button
              @click="edit ? updatedata(1) : storedata(1)"
              :disabled="invalid || processing"
              type="button"
              style="margin-right: 10px; margin-bottom: 10px;"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'"
              variant="outline-primary">
              <feather-icon icon="PrinterIcon" class="mr-50"/>
              <span>Cetak</span>
            </b-button>
            <b-button
              @click="edit ? updatedata(2) : storedata(2)"
              :disabled="invalid || processing"
              type="button"
              variant="outline-primary"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'"
              style="margin-right: 10px; margin-bottom: 10px;">
              <feather-icon icon="SaveIcon" class="mr-50"/>
              <span> {{ edit ? 'Update' : 'Simpan' }} Draft </span>
            </b-button>
            <b-button
              @click="cancelform()"
              :disabled="processing"
              type="button"
              style="margin-right: 10px; margin-bottom: 10px;"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'"
              variant="danger">
              <feather-icon icon="XCircleIcon" class="mr-50"/>
              <span>Batal</span>
            </b-button>
          </b-col>
        </b-row>
      </validation-observer>
    </b-card>
  </div>
</template>

<script>
import {
  BCard,
  BContainer,
  BRow, BCol,
  BFormGroup, BFormInput, BForm, BFormTextarea, BFormRadioGroup,
  BButton,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import flatPickr from 'vue-flatpickr-component'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'

export default {
  components: {
    BCard,
    BRow, BCol,
    BFormGroup, BFormInput, BForm, BFormTextarea, BFormRadioGroup,
    flatPickr,
    ValidationProvider, ValidationObserver,
    BButton,
  },
  data(){
    return{
      required,
      form: {
        donumber: '',
        ponumber: '',
        customernama: '',
        customeralamat: '',
        customerkota: '',
        nopol: '',
        drivernama: '',
        item: '',
        qty: '',
        uom: '',
        tanggalkirim: '',
        btnRequest: '',
      },
      items: [
        { text: 'Liquid CO2', value: 'LIQUID CO2' },
        { text: 'Dry Ice', value: 'DRY ICE' },
        { text: 'Dry Ice (pellete)', value: 'DRY ICE (pallete)' },
        { text: 'Oxygen', value: 'OXYGEN' },
        { text: 'Argon', value: 'ARGON' },
        { text: 'Nitrogen', value: 'NITROGEN' },
      ],
      uoms: [
        { text: 'KG', value: 'KG' },
        { text: 'LITER', value: 'LITER' },
        { text: 'CRADDLE', value: 'CV' },
        { text: 'TABUNG', value: 'BT' },
      ],
      edit: false,
      processing: false,
    }
  },
  methods: {
    isNumber(e){
      e = (e) ? e : window.event;
      let cCode = (e.which) ? e.which : e.kCode;
      if ((cCode > 31 && (cCode < 48 || cCode > 57))) {
        e.preventDefault();
      } else {
        return true;
      }
    },
    resetform(){
      this.form = {
        donumber: '',
        ponumber: '',
        customernama: '',
        customeralamat: '',
        customerkota: '',
        nopol: '',
        drivernama: '',
        item: '',
        qty: '',
        uom: '',
        tanggalkirim: '',
        btnRequest: '',
      }
      this.btnreq = ''
      this.edit = false
    },
    cancelform(){
      this.resetform()
      this.$router.replace({ name: 'apps-sj-list' })
    },
    storedata(val){
      this.$refs.formdelivery.validate().then(success => {
        if(success){
          this.processing = true
          this.form.btnRequest = val
          http.post('deliverynote/new', this.form)
          .then((res) => {
            this.processing = false
            if(this.form.btnRequest === 1){ called.$emit('suratjalanPrintShow', res.data.result, false) }
            this.cancelform()
            this.$toast({
              component: ToastificationContent,
              props: { icon: 'CheckCircleIcon', variant: 'success', title: res.data.message },
            }, { position: 'top-right' })
          })
          .catch((e) => { console.error(e) })
        }
      })
    },
    editdata(val){
      http.get('deliverynote/data/'+val)
      .then((res) => {
        this.form = {
          donumber: res.data.do_no,
          ponumber: res.data.po_no,
          customernama: res.data.detail.detail_customer,
          customeralamat: res.data.detail.detail_address,
          customerkota: res.data.detail.detail_city,
          nopol: res.data.detail.detail_nopol,
          drivernama: res.data.detail.detail_driver,
          item: res.data.detail.detail_item,
          qty: res.data.detail.detail_qty,
          uom: res.data.detail.detail_uom,
          tanggalkirim: res.data.detail.detail_sending_date,
        }
      })
      .catch((e) => { console.error(e) })
    },
    updatedata(val){
      this.$refs.formdelivery.validate().then(success => {
        if(success){
          this.processing = true
          this.form.btnRequest = val
          http.post('deliverynote/data/'+this.$route.params.id, this.form, {params: {keyword: 'update-form-sj'} })
          .then((res) => {
            this.processing = false
            if(this.form.btnRequest === 1){ called.$emit('suratjalanPrintShow', res.data.result, false) }
            this.cancelform()
            this.$toast({
              component: ToastificationContent,
              props: { icon: 'CheckCircleIcon', variant: 'success', title: res.data.message },
            }, { position: 'top-right' })
          })
          .catch((e) => { console.error(e) })
        }
      })
    }
  },
  created(){
    if('id' in this.$route.params){
      this.edit = true
      this.editdata(this.$route.params.id)
    }
  }
}
</script>

<style lang="scss" scoped>
  @import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>