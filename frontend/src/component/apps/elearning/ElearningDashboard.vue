<template>
  <div>

    <b-alert
      :show="alertprops.show"
      :variant="alertprops.variant">
      <div class="alert-body">
        <feather-icon
          class="mr-25"
          :icon="alertprops.icon"/>
        <span class="ml-25"><b> {{ alertprops.message }} </b></span>
      </div>
    </b-alert>
    
    <b-row>
      <b-col
        v-for="(exam, i) in exams_schedules"
        :key="i"
        cols="12" sm="6" md="4" lg="4" xl="3">
        <b-card :style="`background-color: ${exam.type === 1 ? '#A5DD55' : '#5BCDD7'}`">
          <h5 class="text-white">{{ exam.title }}</h5>
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
              <span>{{ exam.duration }} Menit</span>
            </div>
          </b-card-text>
          <b-link
            v-if="exam.isdone !== 1"
            :to="{ name: 'apps-elearning-quiz', params: { id: exam.id, slug: exam.dataquestion.slug, data: exam } }"
            class="card-link">
            <feather-icon
              icon="ArrowUpRightIcon"
              size="18"
              class="mr-50"/> Mulai
          </b-link>
          <b-img
            :src="require('@/assets/images/apps/elearning/examtest-2.svg')"
            class="congratulation-medal"
            width="100px"
            alt="Medal Pic"
          />
        </b-card>
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
import { InfoIcon } from 'vue-feather-icons'

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
      alertprops: { show: false, variant: 'info', message: 'insert message', icon: 'InfoIcon' },
    }
  },
  methods: {
    getdata(){
      var userdataNik = this.userdata.nik
      var userdataAdmin = this.userdata.admin
      this.alertprops = { show: true, variant: 'primary', message: 'Sedang memuat ...', icon: 'ClockIcon' }
      http
        .get('okm/schedule/data', { 
          params: { frompage: 'elearningdashboard' }
        })
        .then((res) => {
          let z = res.data.message
          if(userdataAdmin === 0 && userdataNik < 8000000){
            // let filtered = z.filter(x => x.participants_exam.find(function(el) { return parseInt(el.user_nik) === userdataNik}))
            let filtered = z.filter(x => x.participants_exam.some(y => parseInt(y.user_nik) === userdataNik))
            let insertIsDone = filtered.map(d => ({...d, isdone: d.participants_exam[0].isdone }))
            this.exams_schedules = insertIsDone
          }else if(userdataAdmin === 1){ this.exams_schedules = z }
          if(this.exams_schedules.length > 0){ this.alertprops = { show: false, variant: 'primary', message: 'Sedang memuat ...', icon: 'ClockIcon' } }
          else{ this.alertprops = { show: true, variant: 'primary', message: 'Ujian tidak tersedia.', icon: 'InfoIcon' } }
        })
        .catch((e) => {
          this.alertprops = { show: true, variant: 'danger', message: 'Internal Server Error', icon: 'AlertCircleIcon' }
          console.error(e)
        })
    },
    testfilter(data){
      // let userdataNik = this.userdata.nik
      // let userdataAdmin = this.userdata.admin
      // let datares = data
      // let filter
      // if(userdataAdmin === 0 && userdataNik < 8000000){
      //   filter = datares.filter(x => x.participants_exam.some(y => parseInt(y.user_nik) === userdataNik))
      //   return filter
      // }else if(userdataAdmin === 1){
      //   return datares
      // }
    },
    // quizAvailableAttribute(options){
    //   let today = options.today
    //   let s_exam = new Date(options.startexam).getTime()
    //   let e_exam = new Date(options.endexam).getTime()
    //   let test = new Date()
    //   return {
    //     today: today,
    //     start_exam: s_exam,
    //     end_exam: e_exam,
    //     test: test
    //     // between: isBetween
    //     // time: '',
    //     // date: '',
    //     // datetime: ''
    //   }
    // },
    // testt(){
    //   console.log('OK')
    // }
    // getTimeMoment(m){
    //   return this.$moment({hour: m.hour(), minute: m.minute()})
    // },
    // getDateMoment(v){
    //   return this.$moment({year: v.year(), month: v.month(), date: v.date()})
    // }
  },
  mounted(){
    this.getdata()
  },
  // computed:{
  //   filteredexam(){
  //     let data = this.exams_schedules
  //     let userdataNik = this.userdata.nik
  //     let userdataAdmin = this.userdata.admin
  //     let filter, filtered
  //     if(userdataAdmin === 0 && userdataNik < 8000000){
  //       filter = data.filter(x => x.participants_exam.some(y => parseInt(y.user_nik) === userdataNik))
  //       return filter
  //     }else if(userdataAdmin === 1){
  //       return data
  //     }
  //   },
  // },
  beforeCreate() {
    this.$store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'vertical')
  },
  created(){
    // setInterval(()=> { this.testt() },5000)
  }
}
</script>

<style lang="scss" scoped>
  a {color: #335209;}
  a:hover {color: #335209;}
</style>
