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
          v-if="dataschedule.isactive === 1 || $can('edit', 'AppOKMSchedule')"
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
      :title="infodataparticipant ? 'Info Hasil Ujian '+infodataparticipant.datauser.name : infoparticipantprops.title "
      :size="infoparticipantprops.size">
      <template v-slot:modalbody>
        <b-table-simple responsive small v-if="infodataparticipant">
          <b-tbody>
            <b-tr>
              <b-th sticky-column style="width: 25%; font-size: 10pt;"> Nilai </b-th>
              <b-td style="font-size: 10pt;"><strong>{{ infodataparticipant.score }}</strong></b-td>
            </b-tr>
            <b-tr>
              <b-th sticky-column style="width: 25%; font-size: 10pt;"> Status </b-th>
              <b-td style="font-size: 10pt;">
                <b-badge :variant="infodataparticipant.ispassed === 1 ? 'success' : 'danger' " v-if="infodataparticipant.isdone !== 3">
                  <feather-icon
                    :icon="infodataparticipant.ispassed === 1 ? 'CheckCircleIcon' : 'XCircleIcon' "
                    class="mr-25"
                  />
                  <span>{{infodataparticipant.ispassed === 1 ? 'Lulus' : 'Tidak Lulus'}}</span>
                </b-badge>
                <b-badge variant="success" v-else>
                  Proses
                </b-badge>
              </b-td>
            </b-tr>
            <b-tr>
              <b-th sticky-column style="width: 25%; font-size: 10pt;"> Tanggal Mulai </b-th>
              <b-td style="font-size: 10pt;">{{ infodataparticipant.user_start_exam ? $moment(infodataparticipant.user_start_exam).format('DD MMM YYYY, HH:mm') : '-' }}</b-td>
            </b-tr>
            <b-tr>
              <b-th sticky-column style="width: 25%; font-size: 10pt;"> Tanggal Selesai </b-th>
              <b-td style="font-size: 10pt;">{{ infodataparticipant.user_end_exam ? $moment(infodataparticipant.user_end_exam).format('DD MMM YYYY, HH:mm') : '-' }}</b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
        <b-table-simple bordered responsive="md">
          <b-thead>
            <b-tr>
              <b-th style="width: 5%;" class="text-center">No</b-th>
              <b-th style="width: 30%;" class="text-left">Soal</b-th>
              <b-th style="width: 32%;" class="text-center">Jawaban Benar</b-th>
              <b-th class="text-center" colspan="2">Jawaban User</b-th>
            </b-tr>
          </b-thead>
          <b-tbody v-if="infodataparticipant">
            <b-tr v-for="(qst, iqst) in infodataparticipant.answers_user" :key="iqst">
              <td class="text-center">{{ iqst +1 }}</td>
              <b-td>
                <div v-if="qst.question.image">
                  <b-img
                    v-bind="imgprops"
                    :src="qst.question.image" /><br>
                </div>
                {{ qst.question.text }}
              </b-td>
              <b-td>
                <div v-for="(ao, iao) in qst.answer_options" :key="iao">
                  <div v-if="ao.value === qst.answer_key">
                    <div v-if="ao.image">
                      <b-img
                        v-bind="imgprops"
                        :src="ao.image" /><br>
                    </div>
                    {{ ao.text }}
                  </div>
                </div>
              </b-td>
              <b-td>
                <div v-for="(ao, iao) in qst.answer_options" :key="iao">
                  <div v-if="ao.value === qst.value">
                    <div v-if="ao.image">
                      <b-img
                        v-bind="imgprops"
                        :src="ao.image" /><br>
                    </div>
                    {{ ao.text }}
                  </div>
                </div>
              </b-td>
              <b-td style="width: 5%;" class="text-center">
                <b-icon 
                  :icon="qst.answer_key === qst.value ? 'check-circle-fill' : 'x-circle-fill'"
                  :class="qst.answer_key === qst.value ? 'text-success' : 'text-danger'"/>
              </b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </template>
      <template v-slot:modalfooter>
        <b-button
          @click="infoparticipantprops.show = !infoparticipantprops.show"
          variant="primary">
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
  BTableSimple, BThead, BTbody, BTr, BTh, BTd,
  BImg, BIcon,
} from 'bootstrap-vue'
import ScheduleParticipantTable from './_ParticipantTable.vue'
import AddParticipant from '@/component/utils/Modal.vue'
import vSelect from 'vue-select'
import InfoParticipant from '@/component/utils/Modal.vue'

export default {
  props: {
    participantList: { type: Array }
  },
  components:{
    DetailSchedule,
    BRow, BCol,
    BCard,
    ScheduleParticipantTable,
    BButton, BButtonGroup,
    AddParticipant,
    vSelect,
    BBadge,
    InfoParticipant,
    BTableSimple, BThead, BTbody, BTr, BTh, BTd,
    BImg, BIcon,
  },
  data(){
    return{
      dataschedule: {
        scheduleid: null,
        questionid: null,
        title: '',
        title_question: '',
        total_questions: 0,
        total_participants: 0,
        participants: [],
        isactive: null,
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
        size: 'lg'
      },
      infodataparticipant: null,
      imgprops: { width: 48, height: 36, class: 'm1' },
    }
  },
  methods: {
    getdata(val){
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      http
      .get('okm/schedule/detail/'+val)
      .then((res) => {
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
        }
        this.getcounting(res.data.participants_exam)
        this.setBreadcrumbs(this.dataschedule.title)
        called.$emit('hideloading')
      })
      .catch((e) => { console.error(e) })
    },
    setBreadcrumbs(title){
      this.breadcrumbs = [
        { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
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
      this.infodataparticipant = val
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