<template>
  <div>
    <b-card no-body>
      <b-table-simple
        small
        style="width: 100%;">
        <b-thead>
          <b-tr>
            <b-th style="width: 40%;">Nama Ujian</b-th>
            <b-th class="text-center" style="width: 10%;">Nilai/Minimal</b-th>
            <b-th class="text-center" style="width: 20%;">Status</b-th>
            <b-th class="text-center" style="width: 20%;">Tanggal Pelaksanaan</b-th>
            <b-th class="text-center" style="width: 10%;">Opsi</b-th>
          </b-tr>
        </b-thead>
        <b-tbody v-for="(userexam, i) in dataexams" :key="i">
          <b-tr>
            <b-td>{{ userexam.schedule.title }}</b-td>
            <b-td class="text-center"> {{ userexam.score }}/{{ userexam.schedule.dataquestion.nilai_min }}</b-td>
            <b-td class="text-center"> 
              <b-badge
                :variant="userexam.ispassed === 1 ? 'light-success' : 'light-danger'"
                pill>
                {{ userexam.ispassed === 1 ? 'Lulus' : 'Tidak Lulus' }}
              </b-badge>
            </b-td>
            <b-td class="text-center">
              {{ userexam.user_end_exam ? $moment(userexam.user_end_exam).format('DD/MMM/YYYY'): '-' }}
            </b-td>
            <b-td class="text-center">
              <b-button-group>
                <b-button
                  @click="openDetailExam(userexam)"
                  class="btn-icon"
                  size="sm"
                  type="button"
                  variant="flat-primary">
                  <feather-icon icon="InfoIcon" size="18"/>
                </b-button>
              </b-button-group>
            </b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </b-card>
    <info-exam
      :show="infoexam.show"
      :title="infoexam.title"
      :size="infoexam.size">
      <template v-slot:modalbody>
        <!-- {{ infoexam.dataexam }} -->
        <b-table-simple bordered responsive="md">
          <b-thead>
            <b-tr>
              <b-th style="width: 5%;" class="text-center">No</b-th>
              <b-th style="width: 40%;" class="text-left">Soal</b-th>
              <b-th class="text-center" colspan="2">Jawaban</b-th>
            </b-tr>
          </b-thead>
          <b-tbody v-if="infoexam.dataexam">
            <b-tr v-for="(qst, iqst) in infoexam.dataexam.answers_user" :key="iqst">
              <b-td class="text-center">{{ iqst +1 }}</b-td>
              <b-td class="text-left">
                <div v-if="qst.question.image">
                  <b-img
                    v-bind="imgprops"
                    :src="qst.question.image" /><br>
                </div>
                {{ qst.question.text }}
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
          @click="closeDetailExam()"
          variant="primary">
          Tutup
        </b-button>
      </template>
    </info-exam>
  </div>
</template>

<script>
import { 
  BTableSimple, BThead, BTbody, BTh, BTd, BTr,
  BButton, BButtonGroup,
  BCard,
  BBadge,
  BImg,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import InfoExam from '@/component/utils/Modal.vue'

export default {
  components: {
    BTableSimple, BThead, BTbody, BTh, BTd, BTr,
    BButton, BButtonGroup,
    BCard,
    BBadge,
    BImg,
    InfoExam,
  },
  data(){
    return{
      dataexams: [],
      usernik: null,
      userID: null,
      infoexam: {
        show: false,
        title: 'Hasil Ujian',
        size: 'lg',
        dataexam: null,
      },
      imgprops: { width: 48, height: 36, class: 'm1' },
    }
  },
  methods: {
    fetchdata(){
      // called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      // called.$emit('hideloading')
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      http.get('okm/userexam/all', { params: {userdataNik: this.usernik} })
      .then((res) => {
        this.dataexams = res.data
        called.$emit('hideloading')
      })
      .catch((e) => { console.error(e) })
    },
    openDetailExam(val){
      var objs = val.answers_user
      var sortobjs = objs.sort((a,b)=> a.id - b.id)
      delete val['answers_user']
      val.answers_user = sortobjs
      this.infoexam.show = !this.infoexam.show
      this.infoexam.dataexam = val
    },
    closeDetailExam(){
      this.infoexam.dataexam = null
      this.infoexam.show = !this.infoexam.show
    },
  },
  mounted(){
    this.fetchdata();
  },
  created(){
    if(this.$route.params){
      this.usernik = this.$route.params.nik
      this.userID = this.$route.params.id
    }
  }
}
</script>

<style>

</style>