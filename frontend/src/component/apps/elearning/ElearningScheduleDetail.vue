<template>
  <div>
    <detail-schedule>
      <template v-slot:cstmtitledetail>
        <div>
          <feather-icon icon="InfoIcon" size="18"/>
          <span class="mx-50">INFO</span>
        </div>
      </template>
      <template v-slot:datadetail>
        <b-row>
          <b-col cols="4">
            <p class="small hint-text m-0"><b>Nama Soal</b></p>
            <p class="font-montserrat bold">
              <feather-icon icon="FileIcon" class="mx-25"/>
              {{ dataschedule.title_question }}
            </p>
          </b-col>
          <b-col cols="4">
            <p class="small hint-text m-0"><b>Jumlah Soal</b></p>
            <p class="font-montserrat bold">
              <feather-icon icon="FileIcon" class="mx-25"/>
              {{ dataschedule.total_questions }} Soal
            </p>
          </b-col>
          <b-col cols="4">
            <p class="small hint-text m-0"><b>Jumlah Peserta</b></p>
            <p class="font-montserrat bold">
              <feather-icon icon="FileIcon" class="mx-25"/>
              {{ dataschedule.total_participants }} Peserta
            </p>
          </b-col>
          <b-col cols="4">
            <p class="small hint-text m-0"><b>Status</b></p>
            <p class="font-montserrat bold">
              <!-- <feather-icon icon="FileIcon" class="mx-25"/>
              {{ dataschedule.isactive }} -->
              <b-badge :variant="dataschedule.isactive === 1 ? 'success' : 'danger' ">
                <feather-icon
                  :icon="dataschedule.isactive === 1 ? 'CheckCircleIcon' : 'XCircleIcon' "
                  class="mr-25"
                />
                <span>{{dataschedule.isactive === 1 ? 'Aktif' : 'Non-Aktif'}}</span>
              </b-badge>
            </p>
          </b-col>
          <b-col cols="4" v-if="dataschedule.hasCertificate">
            <p class="small hint-text m-0"><b>Sertifikat</b></p>
            <p class="font-montserrat bold">
              <b-badge 
                variant="light-success" 
                v-b-tooltip.hover.v-success
                :title="`Sign by ${dataschedule.hasCertificate.signer_name}`">
                <feather-icon icon="AwardIcon" class="mr-25"/>
                <span>Tersedia</span>
              </b-badge>
            </p>
          </b-col>
        </b-row>
      </template>
    </detail-schedule>
    
    <div class="d-flex justify-content-between">
      <div>
        <b-button-group class="mb-1">
          <b-button
            v-for="(btn, i) in toolbarbtns"
            :key="i"
            @click="setcounting(btn.cat)"
            size="sm"
            type="button"
            variant="outline-dark">
            <feather-icon
              :icon="btn.icon"
              class="mr-50 align-middle"/>
            <span class="align-middle">{{ btn.text }} </span>
            <b-badge variant="light-primary">{{ btn.count }}</b-badge>
          </b-button>
        </b-button-group>
      </div>
      <div>
        <b-button
          v-if="dataschedule.isactive === 1 || $can('update', 'AppOKMSchedule')"
          @click="addparticipantprops.show = !addparticipantprops.show"
          size="sm"
          type="button"
          variant="flat-primary">
          <feather-icon
            badge
            badge-classes="badge-info"
            icon="PlusCircleIcon" size="18"/>
        </b-button>
      </div>
    </div>

    <b-card no-body>
      <schedule-participant-table
        @infodata="getInfoParticipant"
        @deletedata="processDelParticipant"
        :data="filtereddata">
      </schedule-participant-table>
    </b-card>

    <add-participant
      :show="addparticipantprops.show"
      :title="addparticipantprops.title"
      :size="addparticipantprops.size">
      <template v-slot:modalbody>
        <v-select 
          v-model="formap.additionalparticipants"
          :options="participantList"
          :reduce="label=>label.value"
          multiple
          label="label" />
      </template>
      <template v-slot:modalfooter>
        <b-button
          @click="processAddParticipant"
          variant="outline-primary"
          class="mr-1">
          Proses
        </b-button>
        <b-button
          @click="addparticipantprops.show = !addparticipantprops.show"
          variant="danger">
          Batal
        </b-button>
      </template>
    </add-participant>

    <info-participant
      :show="infoparticipantprops.show"
      :title="infoparticipantprops.title "
      :size="infoparticipantprops.size">
      <template v-slot:modalbody>
        <table-result :user-data-exam="infoparticipantprops.data"></table-result>
      </template>
      <template v-slot:modalfooter>
        <b-button
          @click="infoparticipantprops.show = !infoparticipantprops.show;infoparticipantprops.data={}"
          variant="flat-primary">
          Tutup
        </b-button>
      </template>
    </info-participant>

  </div>
</template>

<script>
import http from '@/customs/axios'
import DetailSchedule from '@/component/utils/CardDetail.vue'
import {
  BRow, BCol,
  BCard,
  BButton, BButtonGroup,
  BBadge,
<<<<<<< Updated upstream
=======
// <<<<<<< HEAD
//   BTableSimple,
//   BThead,
//   BTbody,
//   BTr,
//   BTh,
//   BTd,
//   BImg,
//   BIcon,
// } from "bootstrap-vue";
// import ScheduleParticipantTable from "./_ParticipantTable.vue";
// import AddParticipant from "@/component/utils/Modal.vue";
// import vSelect from "vue-select";
// import InfoParticipant from "@/component/utils/Modal.vue";
// import TableResult from "./_ParticipantResult";
// =======
>>>>>>> Stashed changes
  BTableSimple, BThead, BTbody, BTr, BTh, BTd,
  BImg, BIcon, 
  VBTooltip,
} from 'bootstrap-vue'
import ScheduleParticipantTable from './_ParticipantTable.vue'
import AddParticipant from '@/component/utils/Modal.vue'
import vSelect from 'vue-select'
import InfoParticipant from '@/component/utils/Modal.vue'
import TableResult from "./_ParticipantResult"
<<<<<<< Updated upstream
=======
// >>>>>>> elearning
>>>>>>> Stashed changes

export default {
  props: {
    participantList: { type: Array }
  },
  components:{
    BRow, BCol, BCard, BButton, BButtonGroup, BBadge, BTableSimple, BThead, BTbody, BTr, BTh, BTd,
    BImg, BIcon,
    DetailSchedule, ScheduleParticipantTable, AddParticipant, vSelect, InfoParticipant, TableResult,
  },
<<<<<<< Updated upstream
=======
// <<<<<<< HEAD
//   data() {
//     return {
// =======
>>>>>>> Stashed changes
  directives: {
    'b-tooltip': VBTooltip,
  },
  data(){
    return{
<<<<<<< Updated upstream
=======
// >>>>>>> elearning
>>>>>>> Stashed changes
      dataschedule: {
        scheduleid: null,
        questionid: null,
        title: '',
        title_question: '',
        total_questions: 0,
        total_participants: 0,
        participants: [],
        isactive: null,
<<<<<<< Updated upstream
        hasCertificate: null,
=======
// <<<<<<< HEAD
        score_min: null,
// =======
        hasCertificate: null,
// >>>>>>> elearning
>>>>>>> Stashed changes
      },
      breadcrumbs: [],
      toolbarbtns: [],
      counting: null,
      filter: {
        done: null,
        passed: null,
      },
      addparticipantprops: {
        show: false,
        title: 'Tambah Peserta',
        size: 'lg'
      },
      formap: {
        additionalparticipants: [],
        frompage: 'elearningscheduledetail',
      },
      infoparticipantprops: {
        show: false,
        title: 'Info Hasil Ujian User',
        size: 'lg',
        data: {},
      },
      infodataparticipant: null,
      imgprops: { width: 48, height: 36, class: 'm1' },
    }
  },
  methods: {
    getdata(val){
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      http
<<<<<<< Updated upstream
=======
// <<<<<<< HEAD
//         .get("okm/schedule/detail/" + val)
//         .then((res) => {
//           this.dataschedule = {
//             scheduleid: res.data.id,
//             questionid: res.data.question_id,
//             title: res.data.title,
//             title_question: res.data.dataquestion
//               ? res.data.dataquestion.title
//               : "",
//             // total_questions: res.data.dataquestion ? res.data.dataquestion.qstcount : 0,questions_count
//             total_questions: res.data.questions_count,
//             total_participants: res.data.participants_exam
//               ? res.data.participantscount
//               : 0,
//             participants:
//               res.data.participants_exam.length > 0
//                 ? res.data.participants_exam
//                 : [],
//             isactive: res.data.isactive,
//             score_min: res.data.nilai_min,
//           };
//           this.getcounting(res.data.participants_exam);
//           this.setBreadcrumbs(this.dataschedule.title);
//           called.$emit("hideloading");
//         })
//         .catch((e) => {
//           console.error(e);
//         });
// =======
>>>>>>> Stashed changes
      .get('okm/schedule/detail/'+val)
      .then((res) => {
        // console.log(res.data)
        this.dataschedule = {
          scheduleid: res.data.id,
          questionid: res.data.question_id,
          title: res.data.title,
          title_question: res.data.dataquestion ? res.data.dataquestion.title : '',
          // total_questions: res.data.dataquestion ? res.data.dataquestion.qstcount : 0,questions_count
          total_questions: res.data.questions_count,
          total_participants: res.data.participants_exam ? res.data.participantscount : 0,
          participants: res.data.participants_exam.length > 0 ? res.data.participants_exam : [],
          isactive: res.data.isactive,
          hasCertificate: res.data.certificate_data
        }
        this.getcounting(res.data.participants_exam)
        this.setBreadcrumbs(this.dataschedule.title)
        called.$emit('hideloading')
      })
      .catch((e) => { console.error(e) })
<<<<<<< Updated upstream
=======
// >>>>>>> elearning
>>>>>>> Stashed changes
    },
    setBreadcrumbs(title){
      this.breadcrumbs = [
        { text: 'OKM', to: { name: 'apps-elearning-schedule'} },
        { text: 'Jadwal Ujian', to: { name: 'apps-elearning-schedule'} },
        { text: title, active: true },
      ]
      called.$emit('appbreadcrumbcustomBreadCrumbs', this.breadcrumbs)
    },
    getcounting(val){
      var iscount = { all: 0, done: 0, process: 0, not_taken: 0, passed: 0, not_passed: 0 }
      if(val){
        var data = val
        var isdone_array = data.map((d,i) => { return d.isdone })
        var ispassed_array = data.map((d,i) => { return d.ispassed })
        isdone_array.forEach(function(i) { 
          if(i == 3){ iscount['process'] = (iscount['process'] || 0) +1; }
          else if(i == 2){ iscount['not_taken'] = (iscount['not_taken'] || 0) +1; }
          else{ iscount['done'] = (iscount['done'] || 0) +1; }
        })
        ispassed_array.forEach(function(j) { 
          if(j == 2){ iscount['not_passed'] = (iscount['not_passed'] || 0) +1; }
          else if(j == 1){ iscount['passed'] = (iscount['passed'] || 0) +1; }
        })
        iscount['all'] = isdone_array.length
      }
      this.counting = iscount
      this.toolbarbtns = []
      this.toolbarbtns.push({ text: 'Semua', icon: 'UsersIcon', count: this.counting.all, cat: { done: null, passed: null } })
      this.toolbarbtns.push({ text: 'Selesai', icon: 'CheckIcon', count: this.counting.done, cat: { done: 1, passed: null } })
      this.toolbarbtns.push({ text: 'Belum Dikerjakan', icon: 'CircleIcon', count: this.counting.not_taken, cat: { done: 2, passed: null } })
      this.toolbarbtns.push({ text: 'Proses', icon: 'Edit2Icon', count: this.counting.process, cat: { done: 3, passed: null } })
      this.toolbarbtns.push({ text: 'Lulus', icon: 'UserCheckIcon', count: this.counting.passed, cat: { done: null, passed: 1 } })
      this.toolbarbtns.push({ text: 'Tidak Lulus', icon: 'UserXIcon', count: this.counting.not_passed, cat: { done: null, passed: 2 } })
    },
    setcounting(val){
      this.filter = { done: val.done, passed: val.passed }
    },
    processAddParticipant(){
      let newparticipant = new FormData()
      newparticipant.append('soal_id', this.dataschedule.questionid)
      newparticipant.append('schedule_id', this.dataschedule.scheduleid)
      newparticipant.append('participant_id', JSON.stringify(this.formap.additionalparticipants))
      newparticipant.append('frompage', this.formap.frompage)
      newparticipant.append('qstnumrandoms', this.dataschedule.total_questions) 
      http
      .post('okm/schedule/new', newparticipant)
      .then((res) => {
        // console.log(res.data)
        this.formap.additionalparticipants = []
        this.addparticipantprops.show = false
        this.getdata(this.$route.params.id)
      })
      .catch((e) => { console.error(e) })
    },
    processDelParticipant(val){
      this.$swal({
        text: 'Hapus peserta?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        customClass: {
          confirmButton: 'btn btn-outline-primary',
          cancelButton: 'btn btn-danger ml-1',
        },
        buttonsStyling: false,
      }).then(result => {
        if(result.value){
          called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
          http
          .delete('okm/schedule/participant/'+val)
          .then((res) => {
            this.getdata(this.$route.params.id)
          })
          .catch((e) => { console.error(e) })
        }
      })
    },
    getInfoParticipant(val){
      // console.log(val)
      var objs = val.answers_user
      var sortobjs = objs.sort((a,b)=> a.id - b.id)
      delete val['answers_user']
      val.answers_user = sortobjs
      this.infoparticipantprops.show = true
      this.infoparticipantprops.data = val
    }
  },
  mounted(){
    if(this.$route.params.id){
      let id = this.$route.params.id
      this.getdata(id)
    }
  },
  computed: {
    filtereddata(){
      var passed_status = this.filter.passed
      var done_status = this.filter.done
      var tett = this.dataschedule.participants.filter(function (el){
        if(passed_status){ return el.ispassed === passed_status }
        if(done_status){ return el.isdone === done_status }
        return el
      })
      return tett
    },
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
</style>