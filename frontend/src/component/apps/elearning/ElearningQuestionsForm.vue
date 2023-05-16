<template>
  <div>
    <form-wizard
      ref="tabaddquestion"
      color="#048cdb"
      error-color="#E0144C"
      :title="null"
      :subtitle="null"
      :start-index.sync="stepIndex"
      @on-complete="handlestore"
      class="mb-3">
      <!-- Detail Soal -->
      <tab-content
        title="Detail Soal"
        icon="feather icon-info">
        <b-row>
          <!-- Judul -->
          <b-col cols="12" md="6">
            <b-form-group
              label="Judul Soal"
              label-for="i-title">
              <b-form-input
                v-model="form.exam_title"
                id="i-title"
                placeholder="Judul Soal"/>
            </b-form-group>
          </b-col>
          <!-- Materi -->
          <b-col cols="12" md="6">
            <b-form-group
              label="Materi"
              label-for="i-material">
              <v-select
                :clearable="false"
                v-model="form.exam_material"
                :options="materialsList"
                :reduce="m_title=>m_title.id"
                label="m_title"
                id="exam-material"
                placeholder="Pilih Materi">
                <template #option="{ m_title, deptname}">
                  <b-badge
                    variant="light-warning"
                    class="align-middle mr-50">
                    {{ deptname.name }}
                  </b-badge>
                  <span> {{ m_title }} </span>
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <!-- Durasi -->
          <!-- <b-col cols="12" md="6">
            <b-form-group
              label="Durasi (Menit)"
              label-for="i-duration">
              <b-form-input
                v-model="form.exam_duration"
                @keypress="isNumber($event)"
                id="i-duration"
                placeholder="Menit"/>
            </b-form-group>
          </b-col> -->
          <!-- Level Soal -->
          <b-col cols="12" md="6">
            <b-form-group
              label="Level Soal"
              label-for="i-level">
              <select
                v-model="form.exam_level"
                id="selectlevel"
                class="form-control">
                <option
                  :value="opt"
                  :key="i"
                  v-for="(opt, i) in levelOptions[0]">
                  {{ levelOptions[1][opt] }}
                </option>
              </select>
            </b-form-group>
          </b-col>
          <!-- Nilai Min -->
          <!-- <b-col cols="12" md="6">
            <b-form-group
              label="Nilai Minimum"
              label-for="i-point-min">
              <b-form-input
                v-model="form.exam_point"
                @keypress="isNumber($event)"
                id="i-point-min"
                placeholder="Nilai Minimum"/>
            </b-form-group>
          </b-col> -->
        </b-row>
      </tab-content>
      <!-- Soal -->
      <tab-content
        title="Tambah Soal"
        icon="feather icon-upload">
        <b-row>
          <b-col cols="12">
            <b-alert
              :show="!qstsource"
              variant="danger">
              <div class="alert-body">
                <feather-icon
                  class="mr-25"
                  icon="AlertCircleIcon"/>
                <span class="ml-25">Pilih salah satu sumber soal.</span>
              </div>
            </b-alert>
          </b-col>
          <b-col cols="12">
            <qst-file-upload
              @sendfile="handlefile"
              @deletefile="handledelfile"
              acceptext=".xlsx"
              ref="uploadfile"/>
          </b-col>
          <b-col cols="12" class="mt-50">
            <b-link
              @click="showaddqstmodal()"
              class="text-primary">
              Tambah Manual ?
            </b-link>
          </b-col>
          <b-col cols="12" class="mt-50">
            <a href="/data-app/app-elearning/files/TemplateSoalOKM_v2.xlsx" download>
              <feather-icon icon="FileIcon" /> Unduh Templete Disini!
            </a>
          </b-col>
        </b-row>
      </tab-content>
      <!-- Summary/Ringkasan -->
      <tab-content
        title="Ringkasan"
        icon="feather icon-send">
        <b-row>
          <b-col cols="12">
            <b-form-group
              label="Judul Soal"
              label-for="s-title"
              label-cols-md="4">
              <b-form-input
                :value="form.exam_title"
                plaintext
                id="s-title"/>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Materi"
              label-for="s-material"
              label-cols-md="4">
              <b-form-input
                id="s-material"
                :value="materiname"
                plaintext/>
            </b-form-group>
          </b-col>
          <!-- <b-col cols="12">
            <b-form-group
              label="Durasi (Menit)"
              label-for="s-duration"
              label-cols-md="4">
              <b-form-input
                id="s-duration"
                :value="form.exam_duration"
                plaintext/>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Nilai Minimum"
              label-for="s-point"
              label-cols-md="4">
              <b-form-input
                id="s-point"
                :value="form.exam_point"
                plaintext/>
            </b-form-group>
          </b-col> -->
          <b-col cols="12" v-if="detailfile">
            <b-form-group
              label="Nama File"
              label-for="s-question"
              label-cols-md="4">
              <b-form-input
                id="s-total-qst"
                :value="detailfile.nama"
                plaintext/>
            </b-form-group>
            <b-form-group
              label="Ukuran File"
              label-for="s-question"
              label-cols-md="4">
              <b-form-input
                id="s-total-qst"
                :class="detailfile.size > 8388608 ? 'text-danger':'text-secondary'"
                :value="detailfile.size"
                plaintext/>
            </b-form-group>
            <b-form-group
              label="Type File"
              label-for="s-question"
              label-cols-md="4">
              <b-form-input
                id="s-total-qst"
                :class="detailfile.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ? 'text-secondary' : 'text-danger'"
                :value="detailfile.type"
                plaintext/>
            </b-form-group>
          </b-col>
          <b-col cols="12" v-show="lengthmanualsoal !== 0">
            <b-form-group
              label="Jumlah Soal"
              label-for="s-total-qst"
              label-cols-md="4">
              <b-form-input
                id="s-total-qst"
                :value="lengthmanualsoal"
                plaintext/>
            </b-form-group>
          </b-col>
        </b-row>
      </tab-content>
      <b-button
        variant="primary"
        size="sm"
        slot="prev">
        <feather-icon icon="ChevronsLeftIcon"/>
      </b-button>
      <b-button
        variant="primary"
        size="sm"
        slot="next">
        <feather-icon icon="ChevronsRightIcon"/>
      </b-button>
      <b-button
        :disabled="disabledprocess"
        size="sm"
        :variant="disabledprocess ? 'secondary' : 'primary'"
        slot="finish">
        <feather-icon icon="SendIcon"/> Proses
      </b-button>
    </form-wizard>

    <div v-show="(form.exam_add_manual.length > 0) && (stepIndex === 1) && !editquestionmodalprop.show">
      <b-button
        @click="form.exam_add_manual = []"
        class="my-25"
        size="sm"
        variant="secondary">
        Reset Soal
      </b-button>
      <b-card no-body>
        <table-question
          :data="form.exam_add_manual"
          @editquestion="editqsttmp"
          @deletequestion="delqsttmp"
          ref="tablequestion"/>
      </b-card>
    </div>

    <add-quetions-modal
      :size="addqstmodalprop.size"
      :title="addqstmodalprop.title"
      :show="addqstmodalprop.show"
      :hidefooter="addqstmodalprop.hidefooter"
      ref="addqstmodalprop">
      <template v-slot:modalbody>
        <question-form
          @addquestion="addquestiontmp"
          @cancelform="addqstmodalprop.show = !addqstmodalprop.show"
          ref="questionform"/>
      </template>
    </add-quetions-modal>

    <edit-quetions-modal
      :size="editquestionmodalprop.size"
      :title="editquestionmodalprop.title"
      :show="editquestionmodalprop.show"
      :hidefooter="editquestionmodalprop.hidefooter"
      ref="editquestionmodalprop">
      <template v-slot:modalbody>
        <question-form
          :dataqst="editquestionmodalprop.dataqst"
          :editmode.sync="editquestionmodalprop.edit"
          @cancelform="canceleditqsttmp"
          @updateform="updateqsttmp"
          ref="editquestionform"/>
      </template>
    </edit-quetions-modal>

    <!-- {{ form }} <br>
    button disabled: {{ disabledprocess }} <br>
    sumber soal: {{ qstsource }} <br>
    validasi upload: {{ validationfile }} -->
  </div>
</template>

<script>
import { FormWizard, TabContent } from 'vue-form-wizard'
import {
  BRow, BCol,
  BFormGroup, BFormInput,
  BButton,
  BBadge,
  BLink,
  BAlert,
  BCard,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import AddQuetionsModal from '@/component/utils/Modal.vue'
import QstFileUpload from '@/component/utils/UploadFile.vue'
import TableQuestion from './_QuestionTable.vue'
import EditQuetionsModal from '@/component/utils/Modal.vue'
import QuestionForm from './_QuestionForm.vue'
import http from '@/customs/axios'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  props: {
    materialsList:{ type: Array },
    levelOptions: { type: Array }
  },
  components:{
    FormWizard, TabContent, BRow, BCol, BFormGroup, BFormInput, BButton, BBadge, BLink, BAlert, BCard,
    vSelect, AddQuetionsModal, QstFileUpload, TableQuestion, EditQuetionsModal, QuestionForm,
  },
  data(){
    return{
      stepIndex: 0,
      form: {
        exam_title: '',
        exam_material: null,
        exam_point: '',
        exam_duration: '',
        exam_add_upload: null,
        exam_add_manual: [],
        exam_level: null,
      },
      addqstmodalprop: {
        show: false,
        size: 'md',
        title: 'Tambah Soal',
        hidefooter: true
      },
      editquestionmodalprop: {
        show: false,
        size: 'lg',
        title: 'Edit Soal',
        hidefooter: true,
        edit: false,
        dataqst: null,
      },
      detailfile: null,
    }
  },
  methods:{
    isNumber(e){
      e = (e) ? e : window.event;
      let charCode = (e.which) ? e.which : e.kCode;
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        e.preventDefault();
      } else {
        return true;
      }
    },
    handlestore(){
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      let questionform = new FormData()
      questionform.append('exam_title' ,this.form.exam_title)
      questionform.append('exam_material' ,this.form.exam_material)
      questionform.append('exam_point' ,this.form.exam_point)
      questionform.append('exam_duration' ,this.form.exam_duration)
      questionform.append('exam_add_upload' ,this.form.exam_add_upload)
      questionform.append('exam_add_manual' , JSON.stringify(this.form.exam_add_manual))
      questionform.append('exam_add_manual_length' ,this.lengthmanualsoal)
      questionform.append('exam_level', this.form.exam_level)
      http
      .post('okm/question/new', questionform, {
        headers: { 'content-type': 'multipart/form-data' }
      })
      .then((res) => {
        // console.log(res)
        called.$emit('hideloading')
        this.$toast({
          component: ToastificationContent,
          props: { icon: 'CheckCircleIcon', variant: 'success', title: res.data.message },
        }, { position: 'top-right' })
        this.$router.push({
          name: 'apps-elearning-questions'
          // name: 'apps-elearning-questions-detail',
          // params: { id: res.data.id, slug: res.data.slug }
        })
      })
      .catch((e) => {
        console.error(e)
      })
    },
    handlefile(options){
      this.form.exam_add_upload = options.file
      this.detailfile = options.detail
    },
    handledelfile(){
      this.form.exam_add_upload = null
      this.detailfile = null
    },
    showaddqstmodal(){
      this.addqstmodalprop = {
        show: true,
        size: 'lg',
        title: 'Tambah Soal',
        hidefooter: true
      }
    },
    addquestiontmp(options){
      this.form.exam_add_manual.push(options.data)
      this.addqstmodalprop.show = false
    },
    editqsttmp(options){
      this.editquestionmodalprop.show = !this.editquestionmodalprop.show
      this.editquestionmodalprop.hidefooter = true
      this.editquestionmodalprop.edit = true
      this.editquestionmodalprop.title = 'Edit Soal'
      this.editquestionmodalprop.dataqst = options
    },
    updateqsttmp(options){
      this.form.exam_add_manual.splice(options.index, 1, options.data)
      this.editquestionmodalprop = {
        show: false,
        edit: false,
        dataqst: null,
      }
    },
    delqsttmp(options){
      this.form.exam_add_manual.splice(options.index, 1)
    },
    canceleditqsttmp(){
      this.editquestionmodalprop = {
        show: false,
        hidefooter: true,
        dataqst: null,
      }
    },
    downloadTemplate(){
      alert('downloadTemplate')
    },
  },
  computed:{
    disabledprocess(){
      /**
       * exam_add_upload && exam_add_manual = true
       * !exam_add_upload && exam_add_manual = false
       * exam_add_upload && !exam_add_manual = false
       * !exam_add_upload && !exam_add_manual = true
       */
      if(this.form.exam_title &&
        this.form.exam_material &&
        // this.form.exam_point &&
        // this.form.exam_duration &&
        this.form.exam_level &&
        this.qstsource &&
        this.validationfile){
        return false
      }
      return true
    },
    qstsource(){
      if((this.form.exam_add_upload && this.form.exam_add_manual.length > 0) || (!this.form.exam_add_upload && this.form.exam_add_manual.length === 0)){
        return false
      }else{
        return true
      }
    },
    lengthmanualsoal(){
      return this.form.exam_add_manual.length
    },
    materiname(){
      if(this.form.exam_material){
        var materi = this.materialsList.filter((x) => (x.id === this.form.exam_material))
        return materi[0].m_title
      }
      return null
    },
    validationfile(){
      if(this.detailfile){
        let type = this.detailfile.type
        let size = this.detailfile.size
        if(size <= 8388608 && type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
          return true
        }else{
          return false
        }
      }else{
        return true
      }
    }
  },
  mounted(){
    called.$emit('loadMaterial')
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-wizard.scss';
  @import '@core/scss/vue/libs/vue-select.scss';
</style>
