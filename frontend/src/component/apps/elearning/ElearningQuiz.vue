<template>
  <div>
    <!-- <quiz-detail v-show="stage.intro">
      <template v-slot:cstmtitledetail>
        <div>
          <feather-icon icon="InfoIcon" size="18"/>
          <span class="mx-50">INFO</span>
        </div>
      </template>
      <template v-slot:datadetail>
        <b-row>
          <b-col cols="4">
            <p class="small hint-text m-0"><b>Tipe</b></p>
            <p class="font-montserrat bold">
              <feather-icon :icon="dataexam.type === 1 ? 'Edit3Icon' : 'RepeatIcon'" class="mx-25"/>
              {{ dataexam.type === 1 ? 'Ujian' : 'Remedial' }}
            </p>
          </b-col>
          <b-col cols="4">
            <p class="small hint-text m-0"><b>Jumlah Soal</b></p>
            <p class="font-montserrat bold">
              <feather-icon icon="MenuIcon" class="mx-25"/>
              {{ dataexam.qstcount }} Soal
            </p>
          </b-col>
          <b-col cols="4">
            <p class="small hint-text m-0"><b>Waktu</b></p>
            <p class="font-montserrat bold">
              <feather-icon icon="ClockIcon" class="mx-25"/>
              {{ dataexam.duration }} Menit
            </p>
          </b-col>
        </b-row>
      </template>
    </quiz-detail> -->
    
    <b-card v-if="isadmin">
      <material-reader :file="dataexam.materials" v-if="dataexam.materials"/>
      <span v-else>File Materi Kosong</span>
    </b-card>
    <div 
      v-for="(participantdata, i) in filteredparticipants" 
      :key="i">
      <div class="justify-content-center" v-if="stage.intro">
        <b-card
          class="text-center">
          <b-card-title class="text-left" v-show="isadmin">
            {{ participantdata.datauser.name }}
          </b-card-title>
          <b-card-body>
            <b-row>
              <b-col cols="12">
                <material-reader :file="dataexam.materials" v-if="dataexam.materials && !isadmin"></material-reader>
                <span v-else>File Materi Kosong</span>
              </b-col>
              <b-col cols="12">
                <b-button-group>
                  <b-button
                    @click="participantdata.isdone === 2 ? setquiz(participantdata) : setContinueQuiz(participantdata)"
                    :variant="participantdata.isdone === 2 ? 'flat-primary' : 'flat-success'">
                    <b-icon icon="box-arrow-up-right" class="mr-50" scale="0.8"></b-icon>
                    <span class="align-middle">{{ participantdata.isdone === 2 ? 'Mulai' : 'Lanjutkan' }}</span>
                  </b-button>
                </b-button-group>
              </b-col>
            </b-row>
          </b-card-body>
        </b-card>
      </div>
      
      <div class="d-flex justify-content-center" v-if="stage.quiz">
        <b-card
          style="width: 75%;">
          <b-card-text>
            <div class="d-flex justify-content-between">
              <div>
                <timer :time-minute="quiz.timer" v-bind:timeleft.sync="timeleft" @timeisup="submitquiz"/>
              </div>
              <div>
                <label class="text-muted" for="pagelist">Halaman</label>
                <v-select
                  @input="changepage"
                  v-model="quiz.currentQuestion"
                  :options="pagelist"
                  label="text"
                  :reduce="text=>text.value"
                  :clearable="false"
                  id="pagelist"
                  class="per-page-selector d-inline-block mx-50"/>
              </div>
            </div>
          </b-card-text>
          <b-card-title class="text-center">
            <b-row>
              <b-col
                class="mb-50"
                cols="12" 
                v-show="quiz.userqstlist[quiz.currentQuestion].question.image">
                <b-img
                  :src="quiz.userqstlist[quiz.currentQuestion].question.image"
                  v-bind="imgQstProps"/>
              </b-col>
              <b-col cols="12">
                <span>{{ quiz.userqstlist[quiz.currentQuestion].question.text }}</span>
              </b-col>
            </b-row>
          </b-card-title>
          <b-card-text>
            <b-form-radio-group
              v-model="quiz.choosenoption[quiz.currentQuestion]"
              id="rd-ans-opt">
              <b-row>
                <b-col cols="6" v-for="(ao, iao) in quiz.userqstlist[quiz.currentQuestion].answer_options" 
                :key="iao">
                <b-form-radio
                  :value="{id: quiz.userqstlist[quiz.currentQuestion].id, value: ao.value}"
                  class="m-1">
                  <div>
                    <b-row v-if="ao.image" class="mb-50">
                      <b-col cols="12">
                        <b-img
                        :src="ao.image"
                        v-bind="imgAnsProps" />
                      </b-col>
                    </b-row>
                    <b-row>
                      <b-col cols="12">
                        <span>{{ ao.text }}</span>
                      </b-col>
                    </b-row>
                  </div>
                </b-form-radio>
                </b-col>
              </b-row>
            </b-form-radio-group>
          </b-card-text>
          <template #footer>
            <b-row class="text-center">
              <b-col cols="4">
                <b-button
                  @click="prevbtn(quiz.currentQuestion)"
                  v-show="quiz.currentQuestion !== 0"
                  size="sm" 
                  variant="primary" >
                  <feather-icon icon="ChevronsLeftIcon" />
                </b-button>
              </b-col>
              <b-col cols="4">
              </b-col>
              <b-col cols="4">
                <b-button
                  v-if="quiz.currentQuestion !== (quiz.userqstlist.length -1)"
                  @click="nextbtn(quiz.currentQuestion)"
                  size="sm"
                  variant="primary">
                  <feather-icon icon="ChevronsRightIcon" />
                </b-button>
                <b-button 
                  v-else
                  @click="submitquiz()"
                  size="sm" 
                  variant="success">
                  <feather-icon icon="SendIcon" />
                </b-button>
              </b-col>
            </b-row>
          </template>
        </b-card>
      </div>
      
      <div class="d-flex justify-content-center" v-if="stage.result">
        <b-card
          class="text-center"
          style="width: 50%;">
          <b-card-text>
            <b-row>
              <b-col cols="12" class="mb-5 mt-1">
                <span class="h4 text-muted">{{ result.title }}</span>
              </b-col>
              <b-col cols="12" class="mb-4 mt-3">
                <b-icon :icon="result.icon.name" :variant="result.icon.variant" scale="10"/>
              </b-col>
              <b-col cols="12" class="mb-1 mt-3">
                <p class="h4 mb-1">{{ result.message }}</p>
                <p :class="`h1 ${result.score.variant}`"><strong>{{ result.score.value }}</strong></p>
              </b-col>
              <b-col cols="12" class="mb-0 mt-1">
                <b-button variant="flat-primary" :to="{ name: 'apps-elearning-dashboard'}">
                  Tutup
                </b-button>
              </b-col>
            </b-row>
          </b-card-text>
        </b-card>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/customs/axios'
import QuizDetail from '@/component/utils/CardDetail.vue'
import { 
  BRow, BCol,
  BCard, BCardText, BCardHeader, BCardFooter, BCardTitle, BCardBody,
  BButtonGroup, BButton,
  BIcon,
  BContainer,
  BFormRadioGroup, BFormGroup, BFormRadio,
  BImg,
} from 'bootstrap-vue'
import MaterialReader from './_MaterialReader.vue'
import FeatherIcon from '@/@core/components/feather-icon/FeatherIcon.vue'
import vSelect from 'vue-select'
import Timer from './_Countdown.vue'

export default {
  components: {
    QuizDetail,
    BRow, BCol,
    BCard, BCardText, BCardHeader, BCardFooter, BCardTitle, BCardBody,
    BButtonGroup, BButton,
    BIcon,
    BContainer,
    MaterialReader,
    FeatherIcon,
    vSelect,
    BFormRadioGroup, BFormGroup, BFormRadio,
    BImg,
    Timer,
  },
  data(){
    return{
      usernik: null,
      isadmin: null,
      dataexam: {
        title: null,
        type: null,
        period: { start: null, end: null },
        qstcount: null,
        duration: null,
        scoremin: null,
        materials: null,
        participants: null,
      },
      breadcrumbs: [],
      stream: 'http://'+process.env.VUE_APP_API_ENDPOINT+'/storage/ViewerJS/#',
      stage: { intro: false, quiz: false, result: false, },
      quiz: {
        idparticipant: null,
        userqstlist: [],
        currentQuestion: 0,
        choosenoption: null,
        score: null,
        done: null,
        passed: null,
        timer: 0,
        datetimeuserstart: null,
        datetimeuserend: null
      },
      result: {
        title: '',
        message: '',
        icon: { name: null, variant: 'info' },
        score: { value: 0, variant: 'text-danger'}
      },
      timeleft: 0,
      imgQstProps: { 
        width: 768, 
        height: 480,
        class: 'm1'
      },
      imgAnsProps: { 
        width: 200,
        height: 125,
        class: 'm1'
      },
      menuHidden: this.$store.state.appConfig.layout.menu.hidden,
      layoutType: this.$store.state.appConfig.layout.type
    }
  },
  methods: {
    getdata(){
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      http
      .get('okm/schedule/detail/'+this.$route.params.id)
      .then((res) => {
        // console.log(res.data)
          this.dataexam = {
            title: res.data.dataquestion.title,
            type: res.data.type,
            period: {
              start: res.data.startdate_exam,
              end: res.data.enddate_exam
            },
            qstcount: res.data.dataquestion.qstcount,
            duration: res.data.dataquestion.duration,
            scoremin: res.data.dataquestion.nilai_min,
            // materials: this.stream+res.data.dataquestion.material.materialfile.filematerial,
            participants: res.data.participants_exam,
          }
          if(res.data.dataquestion.material.materialfile !== null){
            this.dataexam.materials = this.stream+res.data.dataquestion.material.materialfile.filematerial
          }
          this.setbreadcrumb(res.data.dataquestion.title)
          this.$store.commit('appConfig/UPDATE_NAV_MENU_HIDDEN', true)
          this.stage = { intro: true, quiz: false, result: false, }
        called.$emit('hideloading')
      })
      .catch((e) => { console.error(e) })
    },
    setbreadcrumb(title){
      this.breadcrumbs = [
        { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
        { text: ' QUIZ - '+title, active: true },
      ]
      called.$emit('appbreadcrumbcustomBreadCrumbs', this.breadcrumbs)
    },
    setquiz(val){
      var timenow = this.$moment.now()
      var seconds = this.dataexam.duration *60
      this.quiz = {
        idparticipant: val.id,
        userqstlist: val.qstpattern,
        currentQuestion: 0,
        choosenoption: [],
        score: 0,
        passed: val.ispassed,
        done: 3,
        timer: seconds,
        datetimeuserstart: this.$moment(timenow).format('YYYY-MM-DD HH:mm:ss'),
        datetimeuserend: ''
      }
      this.stage = { intro: false, quiz: true, result: false, }
      this.updatedata({timeleft: this.quiz.timer})
    },
    nextbtn(index){
      this.quiz.done = 3
      this.updatedata({timeleft: this.timeleft})
      this.quiz.currentQuestion = index +1
    },
    prevbtn(index){
      this.quiz.currentQuestion = index -1
    },
    submitquiz(){
      var timenow = this.$moment.now()
      this.quiz.datetimeuserend = this.$moment(timenow).format('YYYY-MM-DD HH:mm:ss')
      this.quiz.done = 1
      this.updatedata({timeleft: this.timeleft})
      this.result = { 
        message: this.quiz.score >= this.dataexam.scoremin ? 'Selamat Anda LULUS!' : 'Anda TIDAK LULUS.',
        icon: { 
          name: this.quiz.score >= this.dataexam.scoremin ? 'patch-check' : 'x-circle',
          variant: this.quiz.score >= this.dataexam.scoremin ? 'success' : 'danger',
        },
        score: { 
          value: this.quiz.score, 
          variant: this.quiz.score >= this.dataexam.scoremin ? 'text-success' : 'text-danger',
        },
        title: this.timeleft <= 0 ? 'Waktu Habis' : 'Selesai'
      }
      this.stage = { intro: false, quiz: false, result: true, }
    },
    updatedata(options){
      var ispassed = this.dataexam.scoremin <= this.quiz.score ? 1 : 2
      let dataupdate = new FormData()
      dataupdate.append('answersuser', JSON.stringify(this.quiz.choosenoption))
      dataupdate.append('userstartexam', this.quiz.datetimeuserstart)
      dataupdate.append('userendexam', this.quiz.datetimeuserend)
      dataupdate.append('timeleft', options.timeleft)
      dataupdate.append('isdone', this.quiz.done)
      dataupdate.append('ispassed', ispassed)
      dataupdate.append('score', this.quiz.score)
      http
      .post('okm/schedule/participant/'+this.quiz.idparticipant, dataupdate)
      .then((res) => { console.log() })
      .catch((e) => { console.error(e) })
    },
    setContinueQuiz(val){
      console.log(val)
      this.quiz = {
        idparticipant: val.id,
        userqstlist: val.qstpattern,
        currentQuestion: val.answers_user.length >= 0 ? val.answers_user.length -1 : 0,
        score: val.scored,
        done: val.isdone,
        passed: val.ispassed,
        timer: val.timeleft_seconds,
        datetimeuserstart: val.user_start_exam,
        datetimeuserend: ''
      }
      if(val.answers_user.length >= 0){
        this.quiz.choosenoption = val.answers_user.map(data => {
          return {id: data.id, value: data.value}
        })
      }else{ this.quiz.choosenoption = [] }
      this.stage = { intro: false, quiz: true, result: false}
    },
    changepage(){
      this.quiz.done = 3
      this.updatedata({timeleft: this.timeleft})
    }
  },
  mounted(){
    this.getdata()
  },
  computed: {
    filteredparticipants(){
      var datapart = this.dataexam.participants
      if(datapart !== null){
        if(this.isadmin){
          return datapart
        }
      }
      // if(this.isadmin){
      //   if(datapart){
      //     var filter = datapart.filter(item => 
      //       item.isdone === 2 || item.isdone === 3
      //     )
      //     return filter
      //   }
      // }else{
      //   if(datapart){
      //     var filter = datapart.filter(item => 
      //       (parseInt(item.user_nik) === this.usernik) && (item.isdone === 2 || item.isdone === 3)
      //     )
      //   }
      // }
      // return filter
    },
    pagelist(){
      if(this.quiz.userqstlist.length > 0){
        var tmplist = []
        this.quiz.userqstlist.forEach((data, i) => {
          tmplist.push({value: i, text: i +1})
        })
        return tmplist
      }
      return [{value: 0, text: 1}]
    },
  },
  watch: {
    'quiz.choosenoption': {
      immediate: true,
      deep: true,
      handler(newValue){
        if(newValue){
          var answuser = newValue
          var answuserlength = newValue.length
          var qstlist = this.quiz.userqstlist
          var qstlistlength = this.quiz.userqstlist.length
          var point = 100/qstlistlength
          var correct = 0
          if(answuserlength > 0){
            this.quiz.score = 0
            answuser.forEach((ans) => {
              if(ans){
                var qstl = qstlist.find(qst => { return qst.id === ans.id })
                if(ans.value === qstl.answer_key){ correct ++ }
              }
            })
            this.quiz.score = Math.round(correct*point)
          }
        }
      }
    }
  },
  beforeDestroy(){
    if(this.quiz.done === 3){ 
      this.updatedata({timeleft: this.timeleft})
    }
    this.$store.commit('appConfig/UPDATE_NAV_MENU_HIDDEN', false)
    // this.$store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'vertical')
  },
  created(){
    var user = JSON.parse(localStorage.getItem('userdata'))
    this.isadmin = user.admin
    this.usernik = user.nik
  },
  beforeCreate(){
    this.$store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'horizontal')
  },
  destroy(){
    //    
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
  @keyframes fadeInOpacity {
    0% {opacity: 0;}
    100% {opacity: 1;}
  }
  .fade-in-opacity {
    opacity: 1;
    animation-name: fadeInOpacity;
    animation-iteration-count: 1;
    animation-timing-function: ease-in;
    animation-duration: 2s;
  }
</style>