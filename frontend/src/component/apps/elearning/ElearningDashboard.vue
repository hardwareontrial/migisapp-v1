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
        v-for="(exam, i) in filteredexam"
        :key="i"
        cols="12"
        sm="6"
        md="4"
        lg="4"
        xl="3">
        <b-overlay :show="!exam.quizavailable.date" variant="secondary" opacity="0.8" rounded="sm">
          <b-card
            :style="`background-color: ${exam.type === 1 ? 'DarkSeaGreen' : 'LightSeaGreen'}`">
            <h5 class="text-white"> {{ exam.title }} </h5>
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
              :disabled="!exam.quizavailable.time"
              :to="{ name: 'apps-elearning-quiz', params: { id: exam.id, slug: exam.dataquestion.slug, data: exam } }"
              :variant="exam.quizavailable.time ? 'success' : 'secondary'">
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
  props:{
    userdata: {
      type: Object,
    }
  },
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
          let z = res.data.message
          this.exams_schedules = z.map(d => ({...d, quizavailable: this.quizAvailableAttribute({today: this.$moment().format('YYYY-MM-DD HH:mm:ss'), startexam: d.startdate_exam, endexam: d.enddate_exam}) }))
        }else{
          this.alertprops = { show: true, variant: 'primary', message: 'Ujian belum tersedia.', icon: 'InfoIcon' }
        }
      })
      .catch((e) => { console.error(e) })
    },
    quizAvailableAttribute(options){
      let beginningTime = this.$moment(options.today, 'YYYY-MM-DD HH:mm:ss')
      let startExam = this.$moment(options.startexam, 'YYYY-MM-DD HH:mm:ss')
      let endExam = this.$moment(options.endexam, 'YYYY-MM-DD HH:mm:ss')
      let timeToday = this.getTimeMoment(beginningTime)
      let timeStart = this.getTimeMoment(startExam)
      let timeEnd = this.getTimeMoment(endExam)
      let dateToday = this.getDateMoment(beginningTime)
      let dateStart = this.getDateMoment(startExam)
      let dateEnd = this.getDateMoment(endExam)
      let timeAva = timeToday.isBetween(timeStart, timeEnd)
      let dateAva = dateToday.isSameOrAfter(dateStart) && dateToday.isSameOrBefore(dateEnd)
      return {
        time: timeAva,
        date: dateAva,
        datetime: dateAva && timeAva
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
    filteredexam(){
      let data = this.exams_schedules
      let userdataNik = this.userdata.nik
      let userdataAdmin = this.userdata.admin
      let filter
      if(userdataAdmin === 0 && userdataNik < 8000000){
        filter = data.filter(x => x.participants_exam.some(y => parseInt(y.user_nik) === userdataNik))
        return filter
      }else if(userdataAdmin === 1){
        return data
      }
    }
  },
  beforeCreate() {
    this.$store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'vertical')
  },
}
</script>

<style lang="scss" scoped>
  
</style>
