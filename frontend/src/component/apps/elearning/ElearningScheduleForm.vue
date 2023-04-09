<template>
  <b-card>
    <validation-observer
      ref="formdelivery"
      #default="{invalid}">
      <b-row>
        <!-- Judul -->
        <b-col cols="12">
          <b-form-group
            label="Judul"
            label-for="h-judul-soal"
            label-cols-md="4">
            <validation-provider
              #default="{errors}"
              name="Judul Soal"
              vid="elr-f-tqst"
              rules="required">
              <b-form-input
                v-model="form.title"
                id="h-note"/>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <!-- List Soal -->
        <b-col cols="12">
          <b-form-group
            label="Soal"
            label-for="h-soal"
            label-cols-md="4">
            <validation-provider
              #default="{errors}"
              name="Pilihan Soal"
              vid="elr-f-qst"
              rules="required">
              <v-select 
                v-model="form.soal"
                :options="qstlist"
                @input="setqstmax()"
                label="title" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <!-- Total Soal -->
        <b-col cols="12">
          <b-form-group
            label="Jumlah Soal"
            label-for="h-max-soal"
            label-cols-md="4">
              <b-row>
                <b-col cols="3">
                  <validation-provider
                    #default="{errors}"
                    name="Jumlah Soal"
                    vid="elr-f-max-qst"
                    rules="required">
                    <b-form-input
                      readonly
                      v-model="form.qstcount_exam_max"
                      class="w-100"
                      id="h-note"/>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-col>
                <b-col cols="3">
                  <validation-provider
                    #default="{errors}"
                    name="Jumlah Soal"
                    vid="elr-f-qstcount"
                    :rules="'required|max_value:'+`${form.qstcount_exam_max}`">
                    <b-form-input
                      v-model="form.qstcount_exam"
                      class="w-100"
                      id="h-note"/>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-col>
              </b-row>
          </b-form-group>
        </b-col>
        <!-- Durasi Ujian -->
        <b-col cols="12">
          <b-form-group
            label="Durasi Ujian (Menit)"
            label-for="h-duration"
            label-cols-md="4">
            <validation-provider
              #default="{errors}"
              name="Durasi Ujian"
              vid="elr-f-duration"
              rules="required">
              <b-form-input
                v-model="form.duration"
                class="w-50"
                id="h-note"/>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <!-- Durasi Ujian -->
        <b-col cols="12">
          <b-form-group
            label="Nilai Min."
            label-for="h-nilai_min"
            label-cols-md="4">
            <validation-provider
              #default="{errors}"
              name="Nilai Minimal"
              vid="elr-f-duration"
              rules="required|max_value:100">
              <b-form-input
                v-model="form.nilai_min"
                class="w-50"
                id="h-note"/>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <!-- Program -->
        <b-col cols="12">
          <b-form-group
            label="Program"
            label-for="h-tipe"
            label-cols-md="4">
            <validation-provider
              #default="{errors}"
              name="Pilihan Program"
              vid="elr-f-qst"
              rules="required">
              <b-form-radio-group 
                :options="tipeOptions"
                v-model="form.type"
                class="inline-spacing"/>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <!-- Note -->
        <b-col cols="12">
          <b-form-group
            label="Note"
            label-for="h-note"
            label-cols-md="4">
            <validation-provider
              #default="{errors}"
              name="Catatan"
              vid="elr-f-note"
              rules="required">
              <b-form-input
                v-model="form.note"
                id="h-note"/>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <!-- Waktu Mulai -->
        <b-col cols="12">
          <b-form-group
            label="Waktu Mulai"
            label-for="h-materi"
            label-cols-md="4">
            <validation-provider
              #default="{errors}"
              name="Waktu Mulai"
              vid="elr-f-start-date"
              rules="required">
              <flat-pickr
                v-model="form.s_datetime"
                :config="{ enableTime: true, dateFormat: 'd-m-Y H:i'}"
                class="form-control"/>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <!-- Waktu Akhir -->
        <b-col cols="12">
          <b-form-group
            label="Waktu Berakhir"
            label-for="h-materi"
            label-cols-md="4">
            <validation-provider
              #default="{errors}"
              name="Waktu Selesai"
              vid="elr-f-end-date"
              rules="required">
              <flat-pickr
                v-model="form.e_datetime"
                :config="{ enableTime: true, dateFormat: 'd-m-Y H:i'}"
                class="form-control" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <!-- List Peserta -->
        <b-col cols="12">
          <b-form-group
            label="Peserta"
            label-for="h-materi"
            label-cols-md="4">
            <validation-provider
              #default="{errors}"
              name="Pilihan Peserta"
              vid="elr-f-opt-user"
              rules="required">
              <v-select 
                v-model="form.participant_id"
                :options="participantList"
                :reduce="label=>label.value"
                multiple
                label="label" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <!-- Button -->
        <b-col cols="12">
          <b-form-group
            label=""
            label-for="h-materi"
            label-cols-md="4">
            <div>
              <b-button
                :disabled="invalid"
                @click="storeschedule()"
                :variant="invalid ? 'secondary' : 'primary'"
                class="mr-1">
                Tambah
              </b-button>
              <b-button
                @click="cancelbtn()"
                variant="danger"
                class="mr-1">
                Batal
              </b-button>
            </div>
          </b-form-group>
        </b-col>
      </b-row>
    </validation-observer>
  </b-card>
</template>

<script>
import {
  BCard, BCardBody,
  BRow, BCol,
  BFormGroup, BFormInput, BFormRadioGroup, BFormSelect, BFormSelectOption, BFormSelectOptionGroup,
  BButton,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import flatPickr from 'vue-flatpickr-component'
import http from '@/customs/axios'
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate'
import { required, max } from '@validations'

export default {
  props: {
    participantList: { type: Array }
  },
  components: {
    BCard, BCardBody,
    BRow, BCol,
    BFormGroup, BFormInput, BFormRadioGroup, BFormSelect, BFormSelectOption, BFormSelectOptionGroup,
    BButton,
    vSelect,
    flatPickr,
    ValidationProvider, ValidationObserver
  },
  data(){
    return{
      tipeOptions: [
        { value: 1, text: 'Ujian' },
        { value: 2, text: 'Remedial' },
      ],
      form: {
        title: '',
        soal: null,
        soal_id: null,
        type: null,
        s_datetime: null,
        e_datetime: null,
        participant_id: [],
        note: '',
        qstcount_exam: null,
        qstcount_exam_max: 0,
        frompage: 'elearningscheduleform',
        duration: null,
        nilai_min: null,
      },
      qstlist: [],
      required, max
    }
  },
  methods: {
    getqstlist(){
      http
      .get('okm/question/list')
      .then((res) => { this.qstlist = res.data })
      .catch((e) => { console.error(e) })
    },
    storeschedule(){
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      let schedule = new FormData()
      schedule.append('title', this.form.title)
      schedule.append('soal_id', this.form.soal_id)
      schedule.append('type', this.form.type)
      schedule.append('s_datetime', this.form.s_datetime)
      schedule.append('e_datetime', this.form.e_datetime)
      schedule.append('participant_id', JSON.stringify(this.form.participant_id))
      schedule.append('note', this.form.note)
      schedule.append('qstnumrandoms', this.form.qstcount_exam)
      schedule.append('frompage', this.form.frompage)
      schedule.append('duration', this.form.duration)
      schedule.append('nilai_min', this.form.nilai_min)
      http
      .post('okm/schedule/new', schedule)
      .then((res) => {
        // console.log(res.data)
        this.$router.push({name: 'apps-elearning-schedule'})
        called.$emit('hideloading')
      })
      .catch((e) => { console.error(e) })
    },
    cancelbtn(){
      this.form = {
        soal_id: null,
        type: null,
        s_datetime: null,
        e_datetime: null,
        participant_id: [],
        note: '',
        qstcount_exam: null,
        qstcount_exam_max: 0,
        frompage: 'elearningscheduleform',
      },
      this.$router.push({ name: 'apps-elearning-schedule'})
    },
    setqstmax(){
      if(this.form.soal){
        this.form.soal_id = this.form.soal.id
        this.form.qstcount_exam_max = this.form.soal.questionscount
      }else{
        this.form.soal_id = null
        this.form.qstcount_exam_max = 0
      }
    },
  },
  mounted(){
    this.getqstlist()
    // this.getparticipant()
  },
  computed: {
    disablebtn(){
      if(this.form.soal_id && this.form.type && this.form.s_datetime && this.form.e_datetime && (this.form.participant_id.length > 0)){
        return false
      }
      return true
    }
  },
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
  @import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>