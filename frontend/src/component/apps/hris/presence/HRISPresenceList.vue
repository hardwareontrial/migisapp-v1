<template>

  <div>
    <!-- <div class="text-center">
      <h1 class="mt-5">
        Sync Presensi Finger ke GreetDay
      </h1>
    </div> -->

    <b-row class="pricing-card">
      <b-col
        offset-sm-2
        sm="10"
        md="12"
        offset-lg="2"
        lg="10"
        class="mx-auto"
      >
        <b-row>
          <b-col md="12">
            <div class="demo-spacing-0 mb-2">
              <b-alert
                :variant="alert.variant"
                :show="alert.show">
                <div class="alert-body ">
                  <span v-html="alert.message" /> 
                </div>
              </b-alert>
            </div>
            <b-card>
              <b-card-text>SYNC DATA ABSEN</b-card-text>
              <b-row class="mb-2">
                <b-col cols="12" md="6">
                  <b-form-group
                    label="Tanggal & Jam Mulai"
                    label-for="v-start-date">
                    <flat-pickr
                      v-model="form.startdate"
                      class="form-control"
                      :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"/>
                  </b-form-group>
                </b-col>
                <b-col cols="12" md="6">
                  <b-form-group
                    label="Tanggal & Jam Akhir"
                    label-for="v-end-date">
                    <flat-pickr
                      v-model="form.enddate"
                      class="form-control"
                      :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"
                    />
                  </b-form-group>
                </b-col>
                <b-col cols="12">
                  <b-button
                    type="button"
                    @click="handleSync"
                    variant="primary"
                    class="mr-1">
                    <feather-icon
                      icon="RefreshCwIcon"
                      class="mr-50"
                    />
                    <span class="align-middle">Sync</span>
                  </b-button>
                </b-col>
              </b-row>
              <hr class="mb-2">
              <b-card-text>GENERATE .txt FILE</b-card-text>
              <b-row>
                <b-col cols="12" md="6">
                  <b-form-group
                    label="Tanggal & Jam Mulai"
                    label-for="v-start-date">
                    <flat-pickr
                      v-model="form.startdate"
                      class="form-control"
                      :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"/>
                  </b-form-group>
                </b-col>
                <b-col cols="12" md="6">
                  <b-form-group
                    label="Tanggal & Jam Akhir"
                    label-for="v-end-date">
                    <flat-pickr
                      v-model="form.enddate"
                      class="form-control"
                      :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"
                    />
                  </b-form-group>
                </b-col>
                <b-col cols="12">
                  <b-button
                    type="button"
                    variant="primary"
                    @click="handleGetGenerate"
                    class="mr-1">
                    <feather-icon
                      icon="DownloadIcon"
                      class="mr-50"/>
                    <span class="align-middle">Txt File</span>
                  </b-button>
                </b-col>
              </b-row>
            </b-card>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
  </div>

</template>

<script>
import { 
  BAlert,
  BRow, BCol,
  BCard, BCardText,
  BFormGroup, BFormInput, BForm,
  BButton,
} from 'bootstrap-vue'
import flatPickr from 'vue-flatpickr-component'
import http from '@/customs/axios'

export default {
  components: {
    BAlert,
    BRow, BCol,
    BCard, BCardText,
    BFormGroup, BFormInput, BForm,
    BButton,
    flatPickr,
  },
  data(){
    return{
      form: {
        startdate: this.$moment().format('YYYY-MM-DD HH:mm'),
        enddate: this.$moment().format('YYYY-MM-DD HH:mm'),
      },
      dataabsensi: [],
      alert: {
        show: true,
        variant: 'primary',
        message: '<b-icon icon="stopwatch" font-scale="1.5" animation="fade" variant="primary" class="mr-1"> Sedang Memuat...</b-icon>'
      },
      generatedata: [],
    }
  },
  methods: {
    fetchinfo(){
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      this.alert = { show: false, variant: '', message: '' }
      http
      .get('hr/attendace/sync-data')
      .then((data) => {
        this.dataabsensi = data.data
        if(this.dataabsensi.length === 0){
          this.alert = {
            show: true,
            variant: 'danger',
            message: '<strong>Data tidak ditemukan!</strong>'
          }
        }else{
          const last = this.dataabsensi[0]
          this.alert = {
            show: true,
            variant: 'primary',
            message: `
              <ul>
                <li>Data Terakhir: <strong>${this.$moment(last.scan_date).format('DD-MMM-YYYY, HH:mm')}</strong></li>
                <li>Sync Terakhir pada: <strong>${this.$moment(last.created_at).format('DD-MMM-YYYY, HH:mm')}</strong></li>
                <li>Total Data: <strong>${this.dataabsensi.length}</strong></li>
              </ul>`
          }
          this.form.startdate = this.$moment(last.scan_date).format('YYYY-MM-DD HH:mm')
          this.form.enddate = this.$moment().format('YYYY-MM-DD HH:mm')
        }
        called.$emit('hideloading')
      })
      .catch((e) => { 
        console.error(e)
        this.alert = {
          show: true,
          variant: 'danger',
          message: '<strong>Error</strong>'
        }
      })
    },
    handleSync(){
      // console.log(this.form)
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      this.alert = { show: false, variant: '', message: '' }
      http
      .get('hr/attendace/sync', { params: this.form })
      .then((data) => { 
        // console.log(data.data.message) 
        this.fetchinfo()
        this.alert = {
          show: show,
          variant: 'primary',
          message: '<b-icon icon="stopwatch" font-scale="1.5" animation="fade" variant="primary" class="mr-1"> Sedang Memuat...</b-icon>'
        }
        called.$emit('hideloading')
      })
      .catch((e) => { 
        this.alert = {
          show: true,
          variant: 'danger',
          message: e
        } 
      })
    },
    handleGetGenerate(){
      this.alert = { show: false, variant: '', message: '' }
      var filename = this.form.startdate.replace(" ","").replace(":","").replace("-","").replace("-","")+'-'+this.form.enddate.replace(" ","").replace(":","").replace("-","").replace("-","")+'.txt'
      http
      .get('hr/attendace/generate-data', { params: this.form })
      .then((data) => {
        // console.log(data.data)
        this.generatedata = data.data
        if(this.generatedata.length > 0){
          this.handleToTxtFile(this.generatedata, filename)
          location.reload
        }else{
          this.alert = {
            show: true,
            variant: 'danger',
            message: '<strong>Data tidak ditemukan!</strong>'
          }
        }
      })
      .catch((e) => {
        this.alert = {
          show: true,
          variant: 'danger',
          message: e
        } 
      })
    },
    handleToTxtFile(arr, filename){
      var content = "";
      for (let i=0; i<arr.length; i++){
        content += arr[i].pin;
        content += ",";
        content += arr[i].name;
        content += ",";
        content += this.$moment(arr[i].scan_date).format('DD/MM/YYYY HH:mm');
        content += ",";
        content += "\n";
      }
      var uri = "data:application/octet-stream," + encodeURIComponent(content);
      // location.href = uri;

      var link = document.createElement('a')
      if(typeof link.download === 'string'){
        document.body.appendChild(link)
        link.download = filename
        link.href = uri
        link.click()
        document.body.removeChild(link)
      }else{
        location.replace(uri)
      }
    }
  },
  mounted(){
    this.fetchinfo()
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>