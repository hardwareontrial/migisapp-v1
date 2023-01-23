<template>
  <div>
    <b-alert
      :show="alertprops.show"
      :variant="alertprops.variant">
      <div class="alert-body">
        <feather-icon
          class="mr-25"
          :icon="alertprops.icon"/>
        <span class="ml-25">{{ alertprops.message }}</span>
      </div>
    </b-alert>
    <b-row>
      <b-col
        v-for="(exam, i) in exams_schedules" 
        :key="i"
        cols="12"
        :sm="!exam.quizavailable.date ? '12' : '6'"
        :md="!exam.quizavailable.date ? '12' : '4'"
        :xl="!exam.quizavailable.date ? '12' : '3'">
        <b-overlay :show="!exam.quizavailable.time" variant="secondary" opacity="0.8" rounded="sm">
          <b-card
            v-if="exam.quizavailable.date"
            :style="`background-color: ${exam.type === 1 ? 'Orchid' : 'Orchid'}`">
            <h5 class="text-white"> {{ exam.dataquestion ? exam.dataquestion.title : '' }} </h5>
            <b-card-text class="font-small-3 text-white">
              <div>
                <feather-icon :icon="exam.type === 1 ? 'Edit2Icon' : 'RepeatIcon'" class="m-25"/>
                <span>{{ exam.type === 1 ? 'Ujian' : 'Remedial' }}</span>
              </div>
              <div>
                <feather-icon icon="ListIcon" class="m-25"/>
                <span>{{ exam.questions_count }} Soal</span>
              </div>
              <div>
                <feather-icon icon="ClockIcon" class="m-25"/>
                <span>{{ exam.dataquestion.duration }} Menit</span>
              </div>
            </b-card-text>
            <b-button
              :to="{ name: 'apps-elearning-quiz', params: { id: exam.id, slug: exam.dataquestion.slug, data: exam } }"
              variant="success">
              <feather-icon
                icon="CheckIcon"
                class="mr-50"/>
              <span class="align-middle">
                Mulai
              </span>
            </b-button>
            <b-img
              :src="require('@/assets/images/apps/elearning/examtest-2.svg')"
              class="congratulation-medal"
              width="100px"
              alt="Medal Pic"
            />
          </b-card>
          <template #overlay>
            <div class="text-center text-white">
              <feather-icon icon="AlertCircleIcon" size="18" class="mb-50"/>
              <p id="cancel-label">
                Ujian Tutup
              </p>
            </div>
          </template>
        </b-overlay>
        <b-alert
          :show="!exam.quizavailable.date"
          variant="primary">
          <div class="alert-body">
            <feather-icon
              class="mr-25"
              icon="InfoIcon"/>
            <span class="ml-25">Ujian tidak tersedia.</span>
          </div>
        </b-alert>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import http from '@/customs/axios'
import {
  BAlert,
  BCard, BCardText,
  BRow,BCol,
  BImg,
  BButton,
  BLink,
  BOverlay,
} from 'bootstrap-vue'

export default {
  components: {
    BCard, BCardText,
    BRow,BCol,
    BImg,
    BButton,
    BLink,
    BAlert,
    BOverlay,
  },
  data(){
    return{
      exams_schedules: [],
      alertprops: { show: false, variant: 'info', message: 'insert message', icon: 'InfoIcon' }
    }
  },
  methods: {
    getdata(){
      this.alertprops = { show: true, variant: 'primary', message: 'Sedang memuat ...', icon: 'InfoIcon' }
      http
      .get('okm/schedule/data', { 
        params: { frompage: 'elearningdashboard' }
      })
      .then((res) => {
        if(res.data.message.length > 0){
          this.alertprops = { show: false, variant: 'info', message: 'insert message', icon: 'InfoIcon' }
          // this.exams_schedules = res.data.message
          var z = res.data.message
          this.exams_schedules = z.map(d => ({...d, quizavailable: this.quizAvailableAttribute({today: this.$moment().format('YYYY-MM-DD HH:mm:ss'), startexam: d.startdate_exam, endexam: d.enddate_exam}) }))
          // console.log(this.exams_schedules)
        }else{
          this.alertprops = { show: true, variant: 'primary', message: 'Ujian belum tersedia.', icon: 'InfoIcon' }
        }
      })
      .catch((e) => { console.error(e) })
    },
    quizAvailableAttribute(options){
      // var beginningTime = this.$moment('2023-01-12 20:00:00', 'YYYY-MM-DD HH:mm:ss')
      // var startexam = this.$moment('2023-01-10 12:00:00', 'YYYY-MM-DD HH:mm:ss')
      // var endexam = this.$moment('2023-01-13 19:00:00', 'YYYY-MM-DD HH:mm:ss')
      var beginningTime = this.$moment(options.today, 'YYYY-MM-DD HH:mm:ss')
      var startexam = this.$moment(options.startexam, 'YYYY-MM-DD HH:mm:ss')
      var endexam = this.$moment(options.endexam, 'YYYY-MM-DD HH:mm:ss')
      var timetoday = this.getTimeMoment(beginningTime)
      var timestart = this.getTimeMoment(startexam)
      var timeend = this.getTimeMoment(endexam)
      var datetoday = this.getDateMoment(beginningTime)
      var datestart = this.getDateMoment(startexam)
      var dateend = this.getDateMoment(endexam)
      var timeava = timetoday.isBetween(timestart, timeend)
      var dateava = datetoday.isBetween(datestart, dateend)
      return {
        time: timeava,
        date: dateava,
        datetime: dateava && timeava
      }
    },
    getTimeMoment(m){
      return this.$moment({hour: m.hour(), minute: m.minute()})
    },
    getDateMoment(v){
      return this.$moment({year: v.year(), month: v.month(), date: v.date()})
    }
  },
  mounted(){
    this.getdata()
  },
  computed:{
    arrexams(){
      if(this.exams_schedules.length > 0){
        const arr = this.exams_schedules
        // const newArr = arr.map(d => ({...d, exam_ongoing: this.$moment(timenow).isSameOrAfter(this.$moment(d.startdate_exam, 'YYYY-MM-DD HH:mm:ss')) && this.$moment(timenow).isSameOrBefore(this.$moment(d.enddate_exam, 'YYYY-MM-DD HH:mm:ss')) }))
        // const newArr = arr.map(d => ({...d, quizavailable: this.quizAvailableAttribute({today: this.$moment().format('YYYY-MM-DD HH:mm:ss'), startexam: d.startdate_exam, endexam: d.enddate_exam}) }))
        // return newArr
      }
    },
  },
  beforeCreate() {
    this.$store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'vertical')
  },
}
</script>

<style lang="scss" scoped>
  
</style>
