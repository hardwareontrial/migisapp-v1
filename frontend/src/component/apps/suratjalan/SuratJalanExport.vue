<template>
  <div>
    <b-card>
      <b-row>
        <b-col cols="12" lg="4">
          <b-form-group
            label="Pembuat"
            label-for="v-sjexport-creator">
            <v-select
              id="v-sjexport-creator"
              :options="userlist"
              :reduce="name=>name.id"
              multiple
              label="name"
              v-model="creatorid"
            />
          </b-form-group>
        </b-col>
        <b-col cols="12" lg="4">
          <b-form-group
            label="Tanggal Kirim"
            label-for="v-sjexport-sentdate">
            <flat-pickr
              id="v-sjexport-sentdate"
              v-model="sentdate"
              class="form-control"
              :config="{ 
                mode: 'range',
                onChange: ([start, end]) => {
                  if(start && end){
                    this.sentstartdate = this.$moment(start).format('YYYY-MM-DD')+' 00:00:00'
                    this.sentenddate = this.$moment(end).format('YYYY-MM-DD')+' 23:59:59'
                  }
                }, 
              }"
            />
          </b-form-group>
        </b-col>
        <b-col cols="12" lg="4">
          <b-form-group
            label="Tanggal Dibuat"
            label-for="v-sjexport-createdate">
            <flat-pickr
              id="v-sjexport-createdate"
              v-model="createdate"
              class="form-control"
              :config="{ 
                mode: 'range',
                onChange: ([start, end]) => {
                  if(start && end){
                    this.createstartdate = this.$moment(start).format('YYYY-MM-DD')
                    this.createenddate = this.$moment(end).format('YYYY-MM-DD')
                  }
                }, 
              }"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12" class="d-flex align-content-justify">
          <hr />
          <b-button
            v-show="validationrequest"
            type="button"
            @click="requestExcel"
            class="mx-1"
            variant="primary">
            Export
          </b-button>
          <b-button
            @click="handleBack"
            type="button"
            variant="outline-secondary">
            Batal
          </b-button>
        </b-col>
      </b-row>
    </b-card>
  </div>
</template>

<script>
import { 
  BCard,
  BRow, BCol,
  BFormGroup, BFormInput, BFormCheckbox, BForm,
  BButton,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import http from '@/customs/axios'
import flatPickr from 'vue-flatpickr-component'

export default {
  components: {
    BCard,
    BRow, BCol,
    BFormGroup, BFormInput, BFormCheckbox, BForm,
    BButton,
    vSelect,
    flatPickr,
  },
  data(){
    return{
      q: '',
      creatorid: [],
      sentdate: [],
      sentstartdate: this.$moment().startOf('month').format('YYYY-MM-DD'),
      sentenddate: this.$moment().format('YYYY-MM-DD'),
      createdate: [],
      createstartdate: this.$moment().startOf('month').format('YYYY-MM-DD'),
      createenddate: this.$moment().format('YYYY-MM-DD'),
      userlist: [],
      btndisable: false
    }
  },
  methods: {
    getuserlist(){
      http
      .get('misc/list/userlist')
      .then((res) => {
        // console.log(res)
        this.userlist = res.data
      })
      .catch((e) => {
        console.error(e)
      })
    },
    requestExcel(){
      http
      .get('deliverynote/export', { 
        params: { 
          creator: JSON.stringify(this.creatorid), 
          sentstartdate: this.sentstartdate,
          sentenddate: this.sentenddate,
          createstartdate: this.createstartdate,
          createenddate: this.createenddate,
        },
        responseType: 'arraybuffer'
      })
      .then((res) => { 
        // console.log(res)
        var fileURL = window.URL.createObjectURL(new Blob([res.data]));
        var fileLink = document.createElement('a');
        var filename = 'sjexport_'+this.$moment().format('YYMMDD_hhmm')+'.xlsx'
        fileLink.href = fileURL;
        fileLink.setAttribute('download', filename);
        document.body.appendChild(fileLink);
        fileLink.click();
      })
      .catch((e) => { console.error(e) })
    },
    handleBack(){
      this.$router.replace({ name: 'apps-sj-list' })
    }
  },
  mounted(){
    this.getuserlist()
    this.sentdate = [this.sentstartdate, this.sentenddate]
    this.createdate = [this.createstartdate, this.createenddate]
  },
  computed: {
    validationrequest(){
      if(this.sentdate.length === 0 || this.createdate.length === 0){
        return false
      }
      return true
    }
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
  @import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>